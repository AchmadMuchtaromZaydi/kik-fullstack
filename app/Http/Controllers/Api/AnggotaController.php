<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

use App\Models\Anggota;
use App\Models\AnggotaJoinOrganisasi;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;


class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function tambahAnggotaOrgLain(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'organisasi_id' => 'required',
            'anggota_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 'error',
                "message" => $validator->errors()->all(),
                "data" => null
            ], 400);
        }

        $saveJoin = new AnggotaJoinOrganisasi();
        $saveJoin->organisasi_id = $request->organisasi_id;
        $saveJoin->anggota_id = $request->anggota_id;
        $saveJoin->save();

        $anggota = Anggota::find($request->anggota_id);

        return response()->json([
            "status" => 'success',
            "message" => null,
            "data" => $anggota
        ], 201);
    }

    public function store(Request $request)
    {
        //
        $checkAnggota = Anggota::where("nik", $request->nik)->first();

        if ($checkAnggota && $checkAnggota->organisasi_id == $request->organisasi_id) {
            return response()->json([
                "status" => 'error',
                "message" => "Data sudah digunakan",
                "data" => null
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'organisasi_id' => 'required',
            'nama' => 'required|string',
            'nik' => 'required',
            'jabatan' => 'required|in:Ketua,Wakil,Penasehat,Sekretaris,Bendahara,Anggota',
            'organisasi_id' => 'required|string',
            'tanggal_lahir' => 'date',
            'jenis_kelamin' => 'in:L,P'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 'error',
                "message" => $validator->errors()->all(),
                "data" => null
            ], 400);
        }

        $anggota = Anggota::where("nik", $request->nik)->first();

        if ($anggota) {
            $checkJoin = AnggotaJoinOrganisasi::where("anggota_id", $anggota->id)->where("organisasi_id", $request->organisasi_id)->first();

            if ($checkJoin) {
                return response()->json([
                    "status" => 'error',
                    "message" => ["Data anggota sudah ditambahkan"],
                    "data" => null
                ], 400);
            } else {

                if ($anggota->jabatan == "Anggota") {
                    return response()->json([
                        "status" => 'error',
                        "message" => ["Anggota Organisasi Lain."],
                        "data" => $anggota
                    ], 400);
                } else {
                    return response()->json([
                        "status" => 'error',
                        "message" => ["Data anggota sudah ditambahkan"],
                        "data" => null
                    ], 400);
                }
            }
        }

        $anggota = new Anggota;

        $anggota->nama = $request->nama;
        $anggota->nik = $request->nik;
        $anggota->tanggal_lahir = $request->tanggal_lahir;
        $anggota->jenis_kelamin = $request->jenis_kelamin;
        $anggota->pekerjaan = $request->pekerjaan;
        $anggota->alamat = $request->alamat;
        $anggota->whatsapp = $request->whatsapp;
        $anggota->telepon = $request->telepon;
        $anggota->jabatan = $request->jabatan;

        $anggota->save();

        $saveJoin = new AnggotaJoinOrganisasi();
        $saveJoin->anggota_id = $anggota->id;
        $saveJoin->organisasi_id = $request->organisasi_id;
        $saveJoin->save();

        return response()->json([
            "status" => 'success',
            "message" => null,
            "data" => $anggota
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // list anggota dari organisasi
        // $list = Anggota::where('organisasi_id', $id)->get();
        $list = DB::table('kik_anggota')
            ->join('kik_organisasi_anggota', 'kik_anggota.id', '=', 'kik_organisasi_anggota.anggota_id')
            ->select('kik_anggota.*')
            ->where('kik_organisasi_anggota.organisasi_id', $id)
            ->get();

        if ($list) {
            return response()->json([
                'status' => 'success',
                'data' => $list
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'data' => null
            ], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'nik' => 'required|unique:kik_anggota,nik,' . $id,
            'jabatan' => 'required|in:Ketua,Wakil,Penasehat,Sekretaris,Bendahara,Anggota',
            'organisasi_id' => 'required|string',
            'tanggal_lahir' => 'date',
            'jenis_kelamin' => 'in:L,P',
            'whatsapp' => ['numeric', 'digits_between:10,15']
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 'error',
                "message" => $validator->errors()->all(),
                "data" => null
            ], 400);
        }

        $anggota = Anggota::find($id);

        if (!$anggota) {
            return response()->json([
                "status" => 'error',
                "message" => [
                    "Data tidak ditemukan!"
                ],
                "data" => null
            ], 400);
        }

        $checkJoin = AnggotaJoinOrganisasi::where("anggota_id", $anggota->id)->count();

        if ($checkJoin > 1) {

            if ($request->jabatan != "Anggota") {
                return response()->json([
                    "status" => 'error',
                    "message" => [
                        "Data Anggota terkait dengan organisasi lain!"
                    ],
                    "data" => null
                ], 400);
            }
        }


        $anggota->nik = $request->nik;
        $anggota->nama = $request->nama;
        $anggota->tanggal_lahir = $request->tanggal_lahir;
        $anggota->jenis_kelamin = $request->jenis_kelamin;
        $anggota->pekerjaan = $request->pekerjaan;
        $anggota->alamat = $request->alamat;
        $anggota->whatsapp = $request->whatsapp;
        $anggota->telepon = $request->telepon;
        $anggota->jabatan = $request->jabatan;
        $anggota->save();

        return response()->json([
            "status" => 'success',
            "message" => null,
            "data" => $anggota
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $anggota = Anggota::find($id);

        if (!$anggota) {
            return response()->json([
                "status" => 'error',
                "message" => [
                    "Data tidak ditemukan!"
                ],
                "data" => null
            ], 400);
        }

        $anggota->delete();

        return response()->json([
            "status" => 'success',
            "message" => null,
            "data" => null
        ], 200);
    }
}
