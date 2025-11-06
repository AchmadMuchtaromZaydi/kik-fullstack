<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Verifikasi;
use App\Models\Organisasi;

class CekVerifikasiUser
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Ambil organisasi user
        $organisasi = Organisasi::where('user_id', $user->id)->first();

        if ($organisasi) {
            $verifikasi = Verifikasi::where('organisasi_id', $organisasi->id)->first();

            // Jika sudah diverifikasi → kunci akses
            if ($verifikasi && $verifikasi->status === 'terverifikasi') {
                return redirect()->route('dashboard')
                    ->with('error', 'Data Anda telah diverifikasi dan tidak dapat diubah lagi.');
            }
        }

        return $next($request);
    }
}
