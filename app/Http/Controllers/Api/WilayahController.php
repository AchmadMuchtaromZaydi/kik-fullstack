<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WilayahController extends Controller
{

    public function index(Request $request)
    {
        if ($request->id) {
            $wil = array(
                2 => array(5, 'Kota/Kabupaten', 'kab'),
                5 => array(8, 'Kecamatan', 'kec'),
                8 => array(13, 'Kelurahan', 'kel')
            );

            $n = strlen($request->id);
            $length =  $wil[$n][0];

            $data = Wilayah::select("kode", "nama")
                ->where(DB::raw("LENGTH(kode)"), '=', $length)
                ->where(DB::raw("LEFT(kode, $n)"), '=', $request->id)
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        } else {
            // return province
            $length = 2;
            $data = Wilayah::select("kode", "nama")->where(function ($query) use ($length) {
                $query->where(DB::raw('LENGTH(kode)'), $length);
            })->get();

            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        }
    }

    public function getWilayahNama(Request $request)
    {
        $data = Wilayah::where("nama", $request->nama)->first();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function getWilayahAll()
    {
        $keyword = "35.10.";
        $data = Wilayah::where('kode', 'like',  $keyword . '%')->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}
