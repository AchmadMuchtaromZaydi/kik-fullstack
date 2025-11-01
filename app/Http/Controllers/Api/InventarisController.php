<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

use App\Models\Inventaris;
use Illuminate\Http\Request;

class InventarisController extends Controller
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
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'jumlah' => 'required|numeric',
            'pembelian_th' => 'required|numeric',
            'kondisi' => 'in:Baru,Bekas,Rusak',
            'organisasi_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 'error',
                "message" => $validator->errors()->all(),
                "data" => null
            ], 400);
        }

        $inventaris = new Inventaris;

        $inventaris->nama = $request->nama;
        $inventaris->jumlah = $request->jumlah;
        $inventaris->pembelian_th = $request->pembelian_th;
        $inventaris->kondisi = $request->kondisi;
        $inventaris->keterangan = $request->keterangan;
        $inventaris->organisasi_id = $request->organisasi_id;

        $inventaris->save();


        return response()->json([
            "status" => 'success',
            "message" => null,
            "data" => $inventaris
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
        $list = Inventaris::where('organisasi_id', $id)->get();

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
            'jumlah' => 'required|numeric',
            'pembelian_th' => 'required|numeric',
            'kondisi' => 'in:Baru,Bekas,Rusak',
            'organisasi_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 'error',
                "message" => $validator->errors()->all(),
                "data" => null
            ], 400);
        }

        $inventaris = Inventaris::find($id);

        if (!$inventaris) {
            return response()->json([
                "status" => 'error',
                "message" => [
                    "Data tidak ditemukan!"
                ],
                "data" => null
            ], 400);
        }

        $inventaris->nama = $request->nama;
        $inventaris->jumlah = $request->jumlah;
        $inventaris->pembelian_th = $request->pembelian_th;
        $inventaris->kondisi = $request->kondisi;
        $inventaris->keterangan = $request->keterangan;
        $inventaris->organisasi_id = $request->organisasi_id;
        $inventaris->validasi = $request->validasi;

        $inventaris->save();


        return response()->json([
            "status" => 'success',
            "message" => null,
            "data" => $inventaris
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
        $inventaris = Inventaris::find($id);

        if (!$inventaris) {
            return response()->json([
                "status" => 'error',
                "message" => [
                    "Data tidak ditemukan!"
                ],
                "data" => null
            ], 400);
        }

        $inventaris->delete();

        return response()->json([
            "status" => 'success',
            "message" => null,
            "data" => null
        ], 200);
    }
}
