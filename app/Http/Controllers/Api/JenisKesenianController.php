<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

use App\Models\JenisKesenian;
use Illuminate\Http\Request;

class JenisKesenianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data_parent_sub = [];
        $data = JenisKesenian::whereNull("parent")->get();

        foreach ($data as $j) {
            $sub = JenisKesenian::select("id", "nama")->where("parent", $j->id)->get();

            $item = [
                "id" => $j->id,
                "nama" => $j->nama,
                "sub" => $sub,
            ];
            array_push($data_parent_sub, $item);
        }

        return response($data_parent_sub);
    }

    public function jenisKesenianAll() {
        $data = JenisKesenian::all();

        return response()->json([
            "status" => 'success',
            "data" => $data
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'parent' => 'numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 'error',
                "message" => $validator->errors()->all(),
                "data" => null
            ], 400);
        }

        if ($request->parent) {
            $check = JenisKesenian::where("id", $request->parent)->first();

            if (!$check) {
                return response()->json([
                    "status" => 'error',
                    "message" => ["Data parent kesenian tidak ditemukan!"],
                    "data" => null
                ], 400);
            }
        }

        $jeniskesenian = new JenisKesenian;

        $jeniskesenian->nama = $request->nama;
        $jeniskesenian->parent = $request->parent;

        $jeniskesenian->save();

        return response()->json([
            "status" => 'success',
            "message" => ["Data berhasil dibuat"],
            "data" => $jeniskesenian
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
        //
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
            'parent' => 'numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 'error',
                "message" => $validator->errors()->all(),
                "data" => null
            ], 400);
        }

        if ($request->parent) {
            $check = JenisKesenian::where("id", $request->parent)->first();

            if (!$check) {
                return response()->json([
                    "status" => 'error',
                    "message" => ["data parent kesenian tidak ditemukan!"],
                    "data" => null
                ], 400);
            }
        }

        $jeniskesenian = JenisKesenian::find($id);

        if (!$jeniskesenian) {
            return response()->json([
                "status" => 'error',
                "message" => ["data kesenian tidak ditemukan!"],
                "data" => null
            ], 400);
        }

        $jeniskesenian->nama = $request->nama;
        $jeniskesenian->parent = $request->parent;

        $jeniskesenian->save();

        return response()->json([
            "status" => 'success',
            "message" => ["Data berhasil di update"],
            "data" => $jeniskesenian
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jeniskesenian = JenisKesenian::find($id);

        if (!$jeniskesenian) {
            return response()->json([
                "status" => 'error',
                "message" => [
                    "Data tidak ditemukan!"
                ],
                "data" => null
            ], 400);
        }

        $jeniskesenian->delete();

        return response()->json([
            "status" => 'success',
            "message" => "Data berhasil dihapus",
            "data" => null
        ], 200);
    }
}
