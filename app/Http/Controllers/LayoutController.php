<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LayoutController extends Controller
{
    /**
     * Get user role for layout
     */
    public function getUserRole()
    {
        return auth()->user()->role ?? 'guest';
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return auth()->user()->role === 'admin';
    }

    /**
     * Check if user is user-kik
     */
    public function isUserKik()
    {
        return auth()->user()->role === 'user-kik';
    }
}
