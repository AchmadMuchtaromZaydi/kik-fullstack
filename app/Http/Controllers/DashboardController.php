<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Organisasi;

class DashboardController extends Controller
{
    public function index()
    {
        // cek apakah user sudah memiliki data organisasi
        $organisasi = Organisasi::where('user_id', Auth::id())->first();

        return view('user.dashboard', compact('organisasi'));
    }
}
