<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
// Menggunakan Facade V3 Laravel
use Intervention\Image\Laravel\Facades\Image;
use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon; // <-- Diperlukan untuk tanggal

class KartuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function generateKartu($id)
    {
        try {
            // 🔹 Ambil data organisasi (termasuk relasi untuk alamat)
            $org = Organisasi::with(['jenisKesenianObj', 'kecamatanWilayah', 'desaWilayah'])->findOrFail($id);

            // 🔹 Path folder organisasi
            $orgPath = public_path("storage/uploads/organisasi/{$org->id}");
            if (!File::exists($orgPath)) {
                File::makeDirectory($orgPath, 0777, true);
            }

            // --- PERUBAHAN 1: GANTI TEMPLATE ---
            // Kita gunakan 'contoh-1.jpeg' agar layoutnya sesuai
            $templatePath = public_path('images/template-2.jpeg');
            $qrcodePath = public_path('images/qrcode.png');

            if (!File::exists($templatePath)) {
                Log::error("Template kartu tidak ditemukan di: " . $templatePath);
                return back()->with('error', 'Template kartu (contoh-1.jpeg) tidak ditemukan.');
            }

            // --- SINTAKS V3 ---
            $image = Image::read($templatePath);

            // 🔹 Tambahkan QR code (sesuai layout contoh-1)
            if (File::exists($qrcodePath)) {
                // Koordinat & ukuran dari VerifikasiController
                $qr = Image::read($qrcodePath)->resize(150, 150);
                $image->place($qr, 'bottom-center', 30, 150);
            }

            // --- PERUBAHAN 2: GANTI KOORDINAT & DATA ---
            // (Menggunakan koordinat & data dari VerifikasiController agar sesuai contoh-1.jpeg)

            $image->text($org->nama_ketua ?? '-', 450, 400, function ($font) {
                $font->file(public_path('fonts/OpenSans-Bold.ttf'));
                $font->size(42);
                $font->color('#0B2E83');
            });

            $image->text($org->nama ?? '-', 450, 410, function ($font) {
                $font->file(public_path('fonts/OpenSans-Regular.ttf'));
                $font->size(28);
                $font->color('#C70000');
            });

            // Data Nomor Induk ditambahkan
            $image->text($org->nomor_induk ?? '-', 450, 440, function ($font) {
                $font->file(public_path('fonts/OpenSans-Regular.ttf'));
                $font->size(30);
                $font->color('#222');
            });

            // --- PERBAIKAN PARSE ERROR ---
            // 1. Siapkan variabel alamat terlebih dahulu
            $namaDesa = $org->desaWilayah->nama ?? '';
            $namaKecamatan = $org->kecamatanWilayah->nama ?? '';
            $alamat = "{$org->alamat}, {$namaDesa}, {$namaKecamatan}, Banyuwangi";

            // 2. Gunakan variabel $alamat yang sudah bersih
            $image->text($alamat, 120, 600, function ($font) {
                $font->file(public_path('fonts/OpenSans-Regular.ttf'));
                $font->size(26);
                $font->color('#002C72');
            });
            // --- SELESAI PERBAIKAN ---

            // Data Tanggal Expired ditambahkan
            $masa = "Aktif Sampai " . ($org->tanggal_expired ? Carbon::parse($org->tanggal_expired)->format('d.m.Y') : '-');
            $image->text($masa, 700, 720, function ($font) {
                $font->file(public_path('fonts/OpenSans-Regular.ttf'));
                $font->size(22);
                $font->color('#000');
            });

            // --- PERUBAHAN 3: HAPUS PAS FOTO ---
            // $fotoPath = ... (dihapus)
            // if (File::exists($fotoPath)) { ... } (dihapus)

            // 🔹 Simpan hasil di folder organisasi
            $outputPath = "{$orgPath}/kartu_induk_generated.png";
            $image->save($outputPath, 90);

            // 🔹 Kembalikan file langsung ke browser (Sesuai permintaan Anda)
            return response()->file($outputPath);

        } catch (\Exception $e) {
            Log::error("Gagal generate kartu: " . $e->getMessage() . ' di baris ' . $e->getLine());
            return back()->with('error', 'Gagal membuat kartu: ' . $e->getMessage());
        }
    }
}
