<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Organisasi;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $organisasi = Organisasi::where('user_id', $user->id)->first();

        // Flag apakah user boleh akses sidebar
        $canAccessSidebar = $organisasi ? true : false;

        return view('dashboard', compact('canAccessSidebar'));
    }
}
