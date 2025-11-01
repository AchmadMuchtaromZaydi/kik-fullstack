<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organisasi; // ganti model sesuai
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // ambil data sesuai model Anda
        $dataKesenian = Organisasi::orderBy('id','desc')->get();
        return view('app', compact('dataKesenian'));
    }
}
