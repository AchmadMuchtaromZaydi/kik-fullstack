<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Models\Organisasi;
use App\Models\Anggota;
use App\Models\DataPendukung;
use App\Models\Inventaris;
use App\Models\Verifikasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrganisasiController extends Controller
{

    public function index()
    {
        $data = Organisasi::all();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function checkOrganisasi(Request $request)
    {
        $organisasi = Organisasi::where("nomor_induk", $request->nomor_induk)->where("nama_ketua", $request->nama_ketua)->whereNull("user_id")->first();

        if ($organisasi) {
            return response()->json([
                'status' => 'success',
                'message' => "",
                'data' => $organisasi
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                "message" => "Data not found",
                'data' => null
            ], 404);
        }
    }

    public function get_organisasi_user($userid)
    {
        $detail = Organisasi::where('user_id', $userid)->first();

        if ($detail) {
            return response()->json([
                'status' => 'success',
                'data' => $detail
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'data' => null
            ], 400);
        }
    }

    public function show($id)
    {
        $detail = Organisasi::find($id);

        if ($detail) {
            return response()->json([
                'status' => 'success',
                'data' => $detail
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'data' => null
            ], 400);
        }
    }

    public function save_organisasi_user(Request $request)
    {
        // CREATE OR UPDATE

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'tanggal_berdiri' => 'required|date',
            'tanggal_daftar' => 'date',
            'alamat' => 'required|string',
            'jenis_kesenian' => 'required|string',
            // 'logo' => 'required|string',
            'user_id' => 'required|unique:kik_organisasi,user_id,' . $request->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 'error',
                "message" => $validator->errors()->all(),
                "data" => null
            ], 400);
        }

        $organisasi = Organisasi::where("id", $request->id)->first();

        if ($organisasi) {
            $organisasi->nomor_induk = $request->nomor_induk;
            $organisasi->nama = $request->nama;
            $organisasi->tanggal_berdiri = $request->tanggal_berdiri;
            $organisasi->tanggal_daftar = $request->tanggal_daftar ? $request->tanggal_daftar : Carbon::now();
            $organisasi->alamat = $request->alamat;
            $organisasi->desa = $request->desa;
            $organisasi->kecamatan = $request->kecamatan;
            $organisasi->kabupaten = $request->kabupaten;
            $organisasi->jenis_kesenian = $request->jenis_kesenian;
            $organisasi->sub_kesenian = $request->sub_kesenian;
            $organisasi->jumlah_anggota = $request->jumlah_anggota;
            $organisasi->logo = $request->logo;
            $organisasi->status = $request->status;
            $organisasi->user_id = $request->user_id;

            $organisasi->save();
        } else {
            $organisasi = new Organisasi;

            $organisasi->nomor_induk = $request->nomor_induk;
            $organisasi->nama = $request->nama;
            $organisasi->tanggal_daftar = Carbon::now();
            $organisasi->tanggal_berdiri = $request->tanggal_berdiri;
            $organisasi->alamat = $request->alamat;
            $organisasi->desa = $request->desa;
            $organisasi->kecamatan = $request->kecamatan;
            $organisasi->kabupaten = $request->kabupaten;
            $organisasi->jenis_kesenian = $request->jenis_kesenian;
            $organisasi->sub_kesenian = $request->sub_kesenian;
            $organisasi->jumlah_anggota = $request->jumlah_anggota;
            $organisasi->logo = $request->logo;
            $organisasi->status = $request->status;
            $organisasi->user_id = $request->user_id;

            $organisasi->save();
        }

        return response()->json([
            "status" => 'success',
            "message" => null,
            "data" => $organisasi
        ], 200);
    }

    public function destroy($id)
    {
        Anggota::where("organisasi_id", $id)->delete();
        Inventaris::where("organisasi_id", $id)->delete();
        Verifikasi::where("organisasi_id", $id)->delete();
        DataPendukung::where("organisasi_id", $id)->delete();

        $organisasi = Organisasi::find($id);

        if ($organisasi) {
            User::where("id", $organisasi->user_id)->delete();

            $organisasi->delete();
        }

        return response()->json([
            "status" => 'success',
            "message" => true,
            "data" => ""
        ], 200);
    }

    public function getImage(Request $request)
    {
        $imagePath = public_path($request->url);

        // Check if the file exists
        if (file_exists($imagePath)) {
            $file = file_get_contents($imagePath);

            // Return the image with the appropriate content type
            return (new Response($file, 200))
                ->header('Content-Type', 'image/jpeg')->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');;
        } else {
            // If the image file does not exist, return a 404 response
            return response('Image not found', 404);
        }
    }

    public function importData(Request $request)
    {
        try {
            foreach ($request->data as $data) {
                $organisasi = Organisasi::where("nomor_induk", $data["nomor_induk"])->first();

                if (!$organisasi) {
                    $saveOrganisasi = new Organisasi();

                    $saveOrganisasi->nomor_induk = $data["nomor_induk"];
                    $saveOrganisasi->nama = $data["nama"];
                    $saveOrganisasi->nama_ketua = $data["nama_ketua"];
                    $saveOrganisasi->no_telp_ketua = $data["no_telp_ketua"];
                    $saveOrganisasi->tanggal_berdiri = $data["tanggal_berdiri"];
                    $saveOrganisasi->tanggal_daftar = $data["tanggal_daftar"];
                    $saveOrganisasi->tanggal_cetak_kartu = $data["tanggal_cetak_kartu"];
                    $saveOrganisasi->tanggal_expired = $data["tanggal_expired"];
                    $saveOrganisasi->alamat = $data["alamat"];
                    $saveOrganisasi->desa = $data["desa"];
                    $saveOrganisasi->kecamatan = $data["kecamatan"];
                    $saveOrganisasi->kabupaten = $data["kabupaten"];
                    $saveOrganisasi->nama_kecamatan = $data["nama_kecamatan"];
                    $saveOrganisasi->nama_desa = $data["nama_desa"];
                    $saveOrganisasi->jenis_kesenian = $data["jenis_kesenian"];
                    $saveOrganisasi->sub_kesenian = $data["sub_kesenian"];
                    $saveOrganisasi->nama_jenis_kesenian = $data["nama_jenis_kesenian"];
                    $saveOrganisasi->nama_sub_kesenian = $data["nama_sub_kesenian"];
                    $saveOrganisasi->jumlah_anggota = $data["jumlah_anggota"];
                    $saveOrganisasi->status = $data["status"];
                    $saveOrganisasi->keterangan = $data["keterangan"];

                    $saveOrganisasi->save();
                }
            }
        } catch (\Exception $e) {
            // Handle the exception, you might log it or show an error message
            return response('Import data error : ' . $e->getMessage(), 400);
        }
    }
}
