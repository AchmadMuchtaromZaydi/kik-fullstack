<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

use App\Models\DataPendukung;
use App\Models\Organisasi;
use Illuminate\Http\Request;

class DataPendukungController extends Controller
{

    public function getDocuments($organisasi_id)
    {
        $data_pendukung = DataPendukung::where("organisasi_id", $organisasi_id)->get();

        return response()->json([
            "status" => 'success',
            "message" => "url image : /uploads/organisasi/{organisasi_id}/{image-from-db}",
            "data" => $data_pendukung
        ], 200);
    }

    public function uploadDocument(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipe' => 'in:KTP,BANNER,PAS-FOTO,FOTO-KEGIATAN,KARTU',
            'organisasi_id' => 'required|string',
            'image' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 'error',
                "message" => $validator->errors()->all(),
                "data" => null
            ], 400);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // $newFileName = $request->tipe . '.' . $image->getClientOriginalExtension();
            if ($request->tipe == "KARTU") { 
                $newFileName = $request->tipe . "-" . $this->generateRandomString(8) . ".jpg";
            } else if ($request->tipe == "FOTO-KEGIATAN") {
                $newFileName = $request->tipe . "-" . $this->generateRandomString(8) . ".jpg";
            } else {
                $newFileName = $request->tipe . '.jpg';
            }
            $image->move(public_path('uploads/organisasi/' . $request->organisasi_id), $newFileName);

            $image = DataPendukung::where("tipe", $request->tipe)->where("organisasi_id", $request->organisasi_id)->first();

            if ($request->tipe == "KARTU") {

                $image = new DataPendukung();
                $image->tipe = $request->tipe;
                $image->image = $newFileName;
                $image->organisasi_id = $request->organisasi_id;

                $image->save();

                $organisasi = Organisasi::find($request->organisasi_id);
                $organisasi->kartu = $newFileName;
                $organisasi->save();
            } else if ($image && $image->tipe != "FOTO-KEGIATAN") {
                $image->tipe = $request->tipe;
                $image->image = $newFileName;
                $image->organisasi_id = $request->organisasi_id;

                $image->save();
            } else {
                $image = new DataPendukung();
                $image->tipe = $request->tipe;
                $image->image = $newFileName;
                $image->organisasi_id = $request->organisasi_id;

                $image->save();
            }

            return response()->json([
                "status" => 'success',
                "message" => ["success upload"],
                "data" => $image
            ], 200);
        }

        return response()->json([
            "status" => 'error',
            "message" => ["No image selected"],
            "data" => null
        ], 400);
    }

    public function deleteDocument($id)
    {
        $detail = DataPendukung::find($id);

        if ($detail) {
            $imagePath = '/uploads/organisasi/' . $detail->organisasi_id . "/" . $detail->image; // Replace with the actual path to your image file

            if (file_exists(public_path($imagePath))) {
                // echo 'Image exists.';
                unlink(public_path($imagePath));
            } else {
                // echo 'Image does not exist.';
            }

            if ($detail) {
                $detail->delete();

                return response()->json([
                    "status" => 'success',
                    "data" => true
                ], 200);
            }
        } else {

            return response()->json([
                "status" => 'error',
                "data" => false,
            ], 400);
        }
    }

    public function generateRandomString($length = 10)
    {
        $bytes = random_bytes($length);
        return bin2hex($bytes);
    }
}
