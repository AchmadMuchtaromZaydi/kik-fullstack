<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use App\Models\Organisasi; // pastikan model ini sesuai dengan yang kamu punya

class KartuController extends Controller
{
    public function generateKartu($id)
    {
        // 🔹 Ambil data organisasi dari database
        $org = Organisasi::find($id);

        if (!$org) {
            return response()->json(['error' => 'Data organisasi tidak ditemukan'], 404);
        }

        // 🔹 Path folder organisasi
        $orgPath = public_path("storage/uploads/organisasi/{$org->id}");
        if (!File::exists($orgPath)) {
            File::makeDirectory($orgPath, 0777, true);
        }

        // 🔹 Template & QR statis
        $templatePath = public_path('images/template_kartu.png');
        $qrcodePath = public_path('images/qrcode.png');

        // 🔹 Load template dasar
        $image = Image::read($templatePath);

        // 🔹 Tambahkan QR code (resize & tempatkan kanan bawah)
        $qr = Image::read($qrcodePath)->resize(120, 120);
        $image->place($qr, 'bottom-right', 50, 50);

        // 🔹 Tambahkan teks dari data organisasi
        $image->text("KARTU INDUK KESENIAN", 600, 80, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->size(42);
            $font->color('#000000');
            $font->align('center');
        });

        $image->text("Nama Organisasi : " . ($org->nama ?? '-'), 100, 230, function ($font) {
            $font->filename(public_path('fonts/Roboto-Regular.ttf'));
            $font->size(30);
            $font->color('#111111');
        });

        $image->text("Jenis Kesenian : " . ($org->jenis_kesenian ?? '-'), 100, 290, function ($font) {
            $font->filename(public_path('fonts/Roboto-Regular.ttf'));
            $font->size(28);
            $font->color('#111111');
        });

        $image->text("Nama Ketua : " . ($org->nama_ketua ?? '-'), 100, 350, function ($font) {
            $font->filename(public_path('fonts/Roboto-Regular.ttf'));
            $font->size(28);
            $font->color('#111111');
        });

        $image->text("Alamat : " . ($org->alamat ?? '-'), 100, 410, function ($font) {
            $font->filename(public_path('fonts/Roboto-Regular.ttf'));
            $font->size(26);
            $font->color('#222222');
        });

        // 🔹 Tambahkan foto PAS-FOTO jika ada
        $fotoPath = "{$orgPath}/PAS-FOTO.jpg";
        if (File::exists($fotoPath)) {
            $pasFoto = Image::read($fotoPath)->resize(200, 230)->cover(200, 230);
            $image->place($pasFoto, 'top-left', 80, 180);
        }

        // 🔹 Simpan hasil di folder organisasi
        $outputPath = "{$orgPath}/kartu_induk.png";
        $image->save($outputPath, quality: 100);

        // 🔹 Kembalikan file langsung ke browser
        return $image->response('png');
    }
}
