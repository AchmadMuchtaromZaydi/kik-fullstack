<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Show register form
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    /**
     * Show resend code form
     */
    public function showResendForm(Request $request)
    {
        $email = $request->query('email');
        return view('auth.resend-code', compact('email'));
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            // ADMIN: Bypass verification check
            if ($user->role === 'admin') {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }

            // USER-KIK: Harus verifikasi email
            if ($user->code_verified !== 1) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Email belum diverifikasi. Silakan cek email Anda untuk kode verifikasi.'
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    /**
     * Handle register request
     */
    public function register(Request $request)
    {
        // Cek dulu apakah email sudah ada tapi belum terverifikasi
        $existingUser = User::where('email', $request->email)
            ->where('code_verified', '!=', 1)
            ->first();

        // Jika email sudah ada tapi belum terverifikasi, hapus user lama
        if ($existingUser) {
            $existingUser->delete();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'whatsapp' => 'nullable|string|max:15',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Generate verification code
        $verificationCode = rand(100000, 999999);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'password' => Hash::make($request->password),
            'role' => 'user-kik', // Auto set sebagai user-kik
            'isActive' => 0, // NON-AKTIF sampai verifikasi email
            'code_verified' => $verificationCode
        ]);

        // --- REKOMENDASI PERBAIKAN DIMULAI DI SINI ---

        // Kirim email verifikasi dan tangkap statusnya
        $emailSent = $this->sendVerificationEmail($user, $verificationCode);

        // Periksa apakah email berhasil terkirim
        if (!$emailSent) {
            // Jika gagal, hapus user yang baru dibuat agar mereka bisa mendaftar lagi
            $user->delete();

            // Kembalikan dengan pesan error
            return back()->withErrors([
                'email' => 'Pendaftaran gagal. Kami tidak dapat mengirim email verifikasi saat ini. Silakan coba lagi nanti.'
            ])->withInput();
        }

        // --- REKOMENDASI PERBAIKAN SELESAI ---

        // Jika email berhasil terkirim, baru lanjutkan
        return redirect()->route('auth.verify')->with([
            'success' => 'Pendaftaran Berhasil, Silahkan cek email anda untuk verifikasi akun!',
            'email' => $user->email
        ]);
    }

    /**
     * Show verify form
     */
    public function showVerifyForm(Request $request)
    {
        $email = $request->session()->get('email');

        if (!$email) {
            return redirect()->route('auth.register');
        }

        return view('auth.verify', compact('email'));
    }

    /**
     * Handle verify code
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|numeric'
        ]);

        $user = User::where('email', $request->email)
            ->where('code_verified', $request->code)
            ->first();

        if ($user) {
            // Update user menjadi verified dan AKTIF
            $user->update([
                'code_verified' => 1, // 1 berarti sudah terverifikasi
                'isActive' => 1 // AKTIFKAN user setelah verifikasi
            ]);

            // Login user secara otomatis setelah verifikasi
            Auth::login($user);

            $request->session()->regenerate();

            // Redirect based on role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Verifikasi email berhasil!');
            }
            return redirect()->route('dashboard')->with('success', 'Verifikasi email berhasil! Akun Anda sekarang aktif.');
        }

        return back()->withErrors(['code' => 'Kode verifikasi salah.'])->withInput();
    }

    /**
     * Handle resend code
     */
    public function resendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && $user->code_verified !== 1) {
            // Generate new verification code
            $newCode = rand(100000, 999999);

            $user->update([
                'code_verified' => $newCode
            ]);

            // Kirim email verifikasi baru
            $emailSent = $this->sendVerificationEmail($user, $newCode);

            if ($emailSent) {
                return back()->with('success', 'Kode verifikasi baru telah dikirim ke email Anda.');
            } else {
                return back()->with('email_error', 'Gagal mengirim email verifikasi. Silakan coba lagi.');
            }
        }

        return back()->withErrors(['email' => 'Email tidak ditemukan atau sudah terverifikasi.']);
    }

    /**
     * Send verification email - hanya kirim ke email, tidak tampilkan di mana pun
     */
    private function sendVerificationEmail($user, $code)
    {
        try {
            // Data untuk email
            $emailData = [
                'subject' => 'Verifikasi Email - Kartu Induk Kesenian Banyuwangi',
                'recipient' => $user->email,
                'recipient_name' => $user->name,
                'pesan' => 'Gunakan kode verifikasi berikut untuk mengaktifkan akun Anda:',
                'code' => $code
            ];

            // Coba kirim email
            Mail::send('emails.verification', $emailData, function ($message) use ($emailData) {
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->to($emailData['recipient'])
            ->subject($emailData['subject']);
        });

            Log::info("Verification email sent to: {$user->email}");

            return true;

        } catch (\Exception $e) {
            Log::error("Failed to send verification email to {$user->email}: " . $e->getMessage());
            Log::error("Email verification failed for: {$user->email}");

            return false;
        }
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // ===== PERUBAHAN DI SINI =====
        // Diubah dari 'home' ke 'auth.login' karena rute 'home' sudah dihapus.
        return redirect()->route('auth.login');
    }

    /**
     * Display a listing of the users (for admin)
     */
    public function usersIndex()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user (for admin)
     */
    public function usersCreate()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.users.create');
    }

    /**
     * Store a newly created user (for admin)
     */
    public function usersStore(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'whatsapp' => 'nullable|string|max:15',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:user-kik,admin',
            'isActive' => 'required|in:0,1'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Tentukan status verifikasi berdasarkan role
        $codeVerified = ($request->role === 'admin') ? 1 : null;
        $isActive = ($request->role === 'admin') ? 1 : $request->isActive;

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'isActive' => $isActive,
            'code_verified' => $codeVerified // Admin auto verified, user-kik perlu verifikasi
        ]);

        $message = ($request->role === 'admin')
            ? 'Admin berhasil ditambahkan dan sudah aktif.'
            : 'User berhasil ditambahkan. Silakan verifikasi email.';

        return redirect()->route('admin.users')->with('success', $message);
    }

    /**
     * Show the form for editing the specified user (for admin)
     */
    public function usersEdit(User $user)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user (for admin)
     */
    public function usersUpdate(Request $request, User $user)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'whatsapp' => 'nullable|string|max:15',
            'role' => 'required|in:user-kik,admin',
            'isActive' => 'required|in:0,1'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Jika role berubah dari user-kik ke admin, auto verifikasi dan aktifkan
        $codeVerified = $user->code_verified;
        $isActive = $request->isActive;

        if ($user->role === 'user-kik' && $request->role === 'admin') {
            $codeVerified = 1;
            $isActive = 1;
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'role' => $request->role,
            'isActive' => $isActive,
            'code_verified' => $codeVerified
        ]);

        return redirect()->route('admin.users')->with('success', 'User berhasil diupdate.');
    }

    /**
     * Remove the specified user (for admin)
     */
    public function usersDestroy(User $user)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Prevent admin from deleting themselves
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users')->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus.');
    }

    /**
     * Update user status (for admin)
     */
    public function usersUpdateStatus(Request $request, User $user)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'isActive' => 'required|in:0,1'
        ]);

        $user->update([
            'isActive' => $request->isActive
        ]);

        return back()->with('success', 'Status user berhasil diupdate.');
    }

    /**
     * Reset verification code (for admin)
     */
    public function usersResetVerification(User $user)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Hanya reset untuk user-kik, admin tidak perlu verifikasi
        if ($user->role === 'user-kik') {
            $user->update([
                'code_verified' => null
            ]);

            return back()->with('success', 'Kode verifikasi berhasil direset. User perlu verifikasi email kembali.');
        }

        return back()->with('error', 'Admin tidak perlu verifikasi email.');
    }
}
