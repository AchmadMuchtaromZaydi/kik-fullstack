<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Homepage untuk guest
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Dashboard untuk user yang sudah login
     */
    public function dashboard()
    {
        return view('dashboard');
    }

    /**
     * Dashboard admin
     */
    public function adminDashboard()
    {
        return view('admin.dashboard');
    }
}
