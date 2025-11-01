<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Organisasi;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::all();

        return response($data, 200);
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'role' => 'required|in:admin,admin-desa,user-kik',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 'error',
                "message" => $validator->errors()->all(),
                "data" => null
            ], 400);
        }

        $userCreated = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role
        ]);

        $response = [
            "status" => 'success',
            "message" => null,
            "data" => $userCreated
        ];

        return response($response, 201);
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
            'name' => 'required|string',
            'role' => 'required|in:admin,admin-desa,user-kik',
            'email' => 'required|string|unique:users,email,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 'error',
                "message" => $validator->errors()->all(),
                "data" => null
            ], 400);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                "status" => 'error',
                "message" => [
                    "Data tidak ditemukan!"
                ],
                "data" => null
            ], 400);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        $response = [
            "status" => 'success',
            "message" => null,
            "data" => $user
        ];

        return response($response, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $checkOrganisasi = Organisasi::where("user_id", $id)->first();

        if ($checkOrganisasi) {
            return response()->json([
                "status" => 'error',
                "message" => [
                    "Data user sedang digunakan!"
                ],
                "data" => null
            ], 400);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                "status" => 'error',
                "message" => [
                    "Data tidak ditemukan!"
                ],
                "data" => null
            ], 400);
        }

        $user->delete();

        return response()->json([
            "status" => 'success',
            "message" => null,
            "data" => null
        ], 200);
    }
}
