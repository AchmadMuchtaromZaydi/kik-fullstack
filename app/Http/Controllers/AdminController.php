<?php
// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Organisasi;
use App\Models\Anggota;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Hitung statistik
        $stats = [
            'total_kesenian' => Organisasi::count(),
            'kesenian_aktif' => Organisasi::where('status', 'Allow')->count(),
            'kesenian_tidak_aktif' => Organisasi::where('status', '!=', 'Allow')->count(),
            'total_users' => User::count(),
            'users_aktif' => User::where('isActive', 1)->count(),
            'users_tidak_aktif' => User::where('isActive', 0)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function getStatDetail($type)
    {
        switch ($type) {
            case 'total-kesenian':
                $data = Organisasi::all();
                $title = 'Detail Semua Data Kesenian';
                $view = 'admin.partials.stats.kesenian-list';
                break;

            case 'kesenian-aktif':
                $data = Organisasi::where('status', 'Allow')->get();
                $title = 'Detail Kesenian Aktif';
                $view = 'admin.partials.stats.kesenian-list';
                break;

            case 'kesenian-tidak-aktif':
                $data = Organisasi::where('status', '!=', 'Allow')->get();
                $title = 'Detail Kesenian Tidak Aktif';
                $view = 'admin.partials.stats.kesenian-list';
                break;

            case 'total-users':
                $data = User::all();
                $title = 'Detail Semua User';
                $view = 'admin.partials.stats.users-list';
                break;

            case 'users-aktif':
                $data = User::where('isActive', 1)->get();
                $title = 'Detail User Aktif';
                $view = 'admin.partials.stats.users-list';
                break;

            case 'users-tidak-aktif':
                $data = User::where('isActive', 0)->get();
                $title = 'Detail User Tidak Aktif';
                $view = 'admin.partials.stats.users-list';
                break;

            default:
                return response()->json(['error' => 'Type not found'], 404);
        }

        $content = view($view, compact('data'))->render();

        return response()->json([
            'title' => $title,
            'content' => $content
        ]);
    }

    public function laporan()
    {
        $stats = [
            'total_organisasi' => Organisasi::count(),
            'organisasi_aktif' => Organisasi::where('status', 'Allow')->count(),
            'total_anggota' => Anggota::count(),
            'total_users' => User::count(),
            'organisasi_per_status' => Organisasi::groupBy('status')
                ->selectRaw('status, count(*) as total')
                ->get(),
        ];

        return view('admin.laporan', compact('stats'));
    }
}
