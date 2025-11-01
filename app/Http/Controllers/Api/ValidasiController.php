<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Mail\SendMailClass;
use Illuminate\Support\Facades\Validator;

use App\Models\Organisasi;
use App\Models\Verifikasi;
use App\Models\Wilayah;
use App\Models\JenisKesenian;
use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ValidasiController extends Controller
{

    public function updateStatus(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'organisasi_id' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 'error',
                "message" => $validator->errors()->all(),
                "data" => null
            ], 400);
        }

        $organisasi = Organisasi::findOrFail($request->organisasi_id);
        $user = User::find($organisasi->user_id);

        if ($request->status == "Allow") {
            $organisasi->status = "Allow";

            $carbonInstance = Carbon::now();
            $year = $carbonInstance->year;
            $organisasi->nomor_induk = "430/" . $organisasi->id . '.' . $organisasi->jenis_kesenian . '.' . $organisasi->sub_kesenian . "/429.110/"  . $year;

            $kec = Wilayah::where("kode", $organisasi->kecamatan)->first();
            $desa = Wilayah::where("kode", $organisasi->desa)->first();

            $organisasi->nama_kecamatan = $kec ? $kec->nama : "";
            $organisasi->nama_desa = $desa ? $desa->nama : "";

            $jenis_kesenian = JenisKesenian::where("id", $organisasi->jenis_kesenian)->first();
            $sub_kesenian = JenisKesenian::where("id", $organisasi->sub_kesenian)->first();

            $organisasi->nama_jenis_kesenian = $jenis_kesenian ? $jenis_kesenian->nama : "";
            $organisasi->nama_sub_kesenian = $sub_kesenian ? $sub_kesenian->nama : "";

            $anggota = DB::table('kik_anggota')
                ->join('kik_organisasi_anggota', 'kik_anggota.id', '=', 'kik_organisasi_anggota.anggota_id')
                ->select('kik_anggota.*')
                ->where('kik_organisasi_anggota.organisasi_id', $organisasi->id)
                ->where('kik_anggota.jabatan', "Ketua")
                ->first();

            if ($anggota) {
                $organisasi->nama_ketua = $anggota ? $anggota->nama : "";
                $organisasi->no_telp_ketua = $anggota->telepon ? $anggota->telepon : $anggota->whatsapp;
            } else if (isset($request->nama_ketua) && isset($request->no_telp_ketua)) {
                $organisasi->nama_ketua = $request->nama_ketua;
                $organisasi->no_telp_ketua = $request->no_telp_ketua;
            }

            $organisasi->kode_kartu = $organisasi->kode_kartu ? $organisasi->kode_kartu : $this->generateRandomString(16);

            if ($request->tanggal_cetak_kartu) {
                if ($request->tanggal_cetak_kartu != $organisasi->tanggal_cetak_kartu) {
                    $organisasi->kode_kartu = $this->generateRandomString(16);
                }

                $organisasi->tanggal_cetak_kartu = $request->tanggal_cetak_kartu;
                $organisasi->tanggal_expired = $this->getDate($organisasi->tanggal_cetak_kartu);
            }

            $organisasi->save();

            if ($user) {
                $this->sendDataNotif($user->whatsapp, "Selamat Kartu induk kesenian sudah selesai. Anda dapat mengakses kartu induk kesenian digital melalui website https://kesenianbanyuwangi.com");

                // email
                $this->sendEmail($user, "Selamat Kartu induk kesenian sudah selesai. Anda dapat mengakses kartu induk kesenian digital melalui website https://kesenianbanyuwangi.com", "Sudah Aktif");
            }
        } else if ($request->status == "Denny") {
            $organisasi->status = "Denny";

            $organisasi->save();

            // send notifikasi
            if ($user) {
                $this->sendDataNotif($user->whatsapp, "Pengajuan kartu induk anda masih ditolak. Silahkan update kembali data anda dengan cara mengakses melalui website https://kesenianbanyuwangi.com");

                $this->sendEmail($user, "Pengajuan kartu induk anda masih ditolak. Silahkan update kembali data anda dengan cara mengakses melalui website https://kesenianbanyuwangi.com", "Ditolak");
            }
        } else if ($request->status == "Update") {

            $organisasi->status = "Allow";

            if ($request->tanggal_cetak_kartu) {
                if ($request->tanggal_cetak_kartu != $organisasi->tanggal_cetak_kartu) {
                    $organisasi->perpanjangan_ke = $organisasi->perpanjangan_ke + 1;
                    $organisasi->kode_kartu = $this->generateRandomString(16);
                }

                $organisasi->tanggal_cetak_kartu = $request->tanggal_cetak_kartu;
                $organisasi->tanggal_expired = $this->getDate($organisasi->tanggal_cetak_kartu);
            }

            $organisasi->save();

            // send notifikasi
            if ($user) {
                $this->sendDataNotif($user->whatsapp, "Kartu induk kesenian anda sudah diperbarui. Anda dapat mengakses kartu induk kesenian digital melalui website https://kesenianbanyuwangi.com");

                $this->sendEmail($user, "Kartu induk kesenian anda sudah diperbarui. Anda dapat mengakses kartu induk kesenian digital melalui website https://kesenianbanyuwangi.com", "Sukses diperpanjang");
            }
        } else if ($request->status == "Request") {

            $organisasi->status = "Request";
            $organisasi->save();

            // send notifikasi
            $this->sendDataNotif("081249407021", "Ada pendaftaran baru Kartu induk kesenian. Silahkan cek melalui website https://kesenianbanyuwangi.com dan login sebagai admin");


            $dataAdmin = User::where("role", "admin")->get();

            foreach ($dataAdmin as $d) {
                $this->sendEmail($d, "Ada pendaftaran baru Kartu induk kesenian. Silahkan cek melalui website https://kesenianbanyuwangi.com dan login sebagai admin", "Pendaftaran Baru");
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $organisasi
        ], 200);
    }

    public function generateRandomString($length = 10)
    {
        $bytes = random_bytes($length);
        return bin2hex($bytes);
    }

    public function sendEmail($receiver, $message, $status)
    {
        try {
            $emailData = [
                'subject' => 'Email Verification',
                'recipient' => $receiver->email,
                'recipient_name' => $receiver->name,
                'message' => $message,
                'status' => $status,
                // Add any other data you need for the email template
            ];

            dispatch(new SendEmailJob(new SendMailClass($emailData)));
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email gagal dikirim',
                'data' => $e->getMessage()
            ], 400);
        }
    }

    public function sendDataNotif($receiver, $message)
    {
        $nomor = $receiver; // Replace this with your actual string
        $nomor_valid = "";

        if (substr($nomor, 0, 2) === "08") {
            // echo "The string starts with '08'.";
            $oldString = "08";
            $newString = "628";
            $nomor_valid = preg_replace('/' . $oldString . '/', $newString, $nomor, 1);
        } else {
            return response()->json([
                "status" => 'error',
                "message" => [
                    "Recipient number is invalid. Format : 08xxxxxxxxx"
                ]
            ], 400);
        }

        $body = array(
            "api_key" => "4339af6b970edd4010d4fb1aa094207ebccb9e16",
            "receiver" => $nomor_valid,
            "data" => array("message" => $message)
        );

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://whatsapp.kesenianbanyuwangi.com/api/send-message",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_HTTPHEADER => [
                "Accept: */*",
                "Content-Type: application/json",
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            // echo "cURL Error #:" . $err;
            return response()->json([
                "status" => 'error',
                "message" => [
                    $err
                ],
            ], 400);
        } else {
            // echo $response;
            return response()->json([
                "status" => 'success',
                "message" => [
                    "Pesan berhasil terkirim"
                ],
            ], 200);
        }
    }

    public function sendNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receiver' => 'required|numeric', 'digits_between:10,15',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 'error',
                "message" => $validator->errors()->all()
            ], 400);
        }

        $nomor = $request->receiver; // Replace this with your actual string
        $nomor_valid = "";

        if (substr($nomor, 0, 2) === "08") {
            // echo "The string starts with '08'.";
            $oldString = "08";
            $newString = "628";
            $nomor_valid = preg_replace('/' . $oldString . '/', $newString, $nomor, 1);
        } else {
            return response()->json([
                "status" => 'error',
                "message" => [
                    "Recipient number is invalid. Format : 08xxxxxxxxx"
                ]
            ], 400);
        }

        $body = array(
            "api_key" => "4339af6b970edd4010d4fb1aa094207ebccb9e16",
            "receiver" => $nomor_valid,
            "data" => array("message" => $request->message)
        );

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://whatsapp.kesenianbanyuwangi.com/api/send-message",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_HTTPHEADER => [
                "Accept: */*",
                "Content-Type: application/json",
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            // echo "cURL Error #:" . $err;
            return response()->json([
                "status" => 'error',
                "message" => [
                    $err
                ],
            ], 400);
        } else {
            // echo $response;
            return response()->json([
                "status" => 'success',
                "message" => [
                    "Pesan berhasil terkirim"
                ],
            ], 200);
        }
    }

    public function getDate($specificDate)
    {
        $carbonDate = Carbon::parse($specificDate);
        $threeYearsAgo = $carbonDate->addYears(2);
        $result = $threeYearsAgo->format('Y-m-d');

        return $result;
    }

    public function index(Request $request)
    { }

    public function show($id)
    {
        $list = Verifikasi::where('organisasi_id', $id)->get();

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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'organisasi_id' => 'required|string',
            'tipe' => 'required|string',
            'status' => 'required|in:valid,tdk_valid',
            // 'keterangan' => 'required',
            'tanggal_review' => 'date',
            'userid_review' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 'error',
                "message" => $validator->errors()->all(),
                "data" => null
            ], 400);
        }

        $verifikasi = new Verifikasi();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // $newFileName = $request->tipe . '.' . $image->getClientOriginalExtension();
            $newFileName = $request->tipe . "-REVIEW.jpg";
            $image->move(public_path('uploads/organisasi/' . $request->organisasi_id), $newFileName);

            $verifikasi->foto = $newFileName;
        }

        $verifikasi->organisasi_id = $request->organisasi_id;
        $verifikasi->tipe = $request->tipe;
        $verifikasi->status = $request->status;
        $verifikasi->keterangan = $request->keterangan;
        $verifikasi->tanggal_review = $request->tanggal_review;
        $verifikasi->userid_review = $request->userid_review;

        $verifikasi->save();

        return response()->json([
            "status" => 'success',
            "message" => null,
            "data" => $verifikasi
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'organisasi_id' => 'required|string',
            'tipe' => 'required|string',
            'status' => 'required|in:valid,tdk_valid',
            // 'keterangan' => 'required',
            'tanggal_review' => 'date',
            'userid_review' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 'error',
                "message" => $validator->errors()->all(),
                "data" => null
            ], 400);
        }

        $verifikasi = Verifikasi::find($id);

        if (!$verifikasi) {
            return response()->json([
                "status" => 'error',
                "message" => [
                    "Data tidak ditemukan!"
                ],
                "data" => null
            ], 400);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // $newFileName = $request->tipe . '.' . $image->getClientOriginalExtension();
            $newFileName = $request->tipe . "-REVIEW.jpg";
            $image->move(public_path('uploads/organisasi/' . $request->organisasi_id), $newFileName);

            $verifikasi->foto = $newFileName;
        }

        $verifikasi->organisasi_id = $request->organisasi_id;
        $verifikasi->status = $request->status;
        $verifikasi->keterangan = $request->keterangan;
        $verifikasi->tanggal_review = $request->tanggal_review;
        $verifikasi->userid_review = $request->userid_review;
        $verifikasi->save();

        return response()->json([
            "status" => 'success',
            "message" => null,
            "data" => $verifikasi
        ], 200);
    }

    public function destroy($id)
    {
        $verifikasi = Verifikasi::find($id);

        if (!$verifikasi) {
            return response()->json([
                "status" => 'error',
                "message" => [
                    "Data tidak ditemukan!"
                ],
                "data" => null
            ], 400);
        }

        $verifikasi->delete();

        return response()->json([
            "status" => 'success',
            "message" => null,
            "data" => true
        ], 200);
    }
}
