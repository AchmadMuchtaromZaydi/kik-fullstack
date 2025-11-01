<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'whatsapp' => 'nullable|string|max:15',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:user-kik,admin',
            'isActive' => 'required|in:0,1'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'isActive' => $request->isActive,
            'code_verified' => 1 // Auto verified untuk admin created users
        ]);

        return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'whatsapp' => 'nullable|string|max:15',
            'role' => 'required|in:user-kik,admin',
            'isActive' => 'required|in:0,1'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'role' => $request->role,
            'isActive' => $request->isActive
        ]);

        return redirect()->route('admin.users')->with('success', 'User berhasil diupdate.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus.');
    }

    /**
     * Update user status (active/inactive)
     */
    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'isActive' => 'required|in:0,1'
        ]);

        $user->update([
            'isActive' => $request->isActive
        ]);

        return response()->json(['success' => 'Status user berhasil diupdate.']);
    }

    /**
     * Reset verification code
     */
    public function resetVerification(User $user)
    {
        $user->update([
            'code_verified' => null
        ]);

        return back()->with('success', 'Kode verifikasi berhasil direset. User perlu verifikasi email kembali.');
    }
}
