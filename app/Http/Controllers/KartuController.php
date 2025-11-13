<?php

namespace App\Http\Controllers;

// 1. TAMBAHKAN 'use Illuminate\Http\Request;'
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use App\Models\Organisasi;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class KartuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 2. TAMBAHKAN 'Request $request' SEBAGAI PARAMETER
    public function generateKartu(Request $request, $id)
    {
        try {
            // 🔹 Ambil data organisasi dengan relasi yang diperlukan
            $org = Organisasi::with([
                'jenisKesenianObj',
                'kecamatanWilayah',
                'desaWilayah',
                'dataPendukung',
                'ketua' // Relasi ini sudah Anda panggil, bagus!
            ])->findOrFail($id);

            // 🔹 Path folder organisasi
            $orgPath = public_path("storage/uploads/organisasi/{$org->id}");
            if (!File::exists($orgPath)) {
                File::makeDirectory($orgPath, 0777, true);
            }

            $templatePath = public_path('images/template-2.jpeg');
            $qrcodePath = public_path('images/qrcode.png');

            if (!File::exists($templatePath)) {
                Log::error("Template kartu tidak ditemukan di: " . $templatePath);
                return back()->with('error', 'Template kartu tidak ditemukan.');
            }

            $image = Image::read($templatePath);

            // 🔹 Tambahkan QR code
            if (File::exists($qrcodePath)) {
                $qr = Image::read($qrcodePath)->resize(150, 150);
                $image->place($qr, 'bottom-center', 30, 150);
            }

            // =================================================================
            // ✅ PERBAIKAN DI SINI:
            // =================================================================
            $image->text($org->nama_ketua, 440, 290, function ($font) {
                $font->file(public_path('fonts/OpenSans-Bold.ttf'));
                $font->size(60);
                $font->color('#006296FF');
                $font->align('left');
            });

            $namaOrganisasi = "Ketua\n\n" . ($org->nama ?? '-'); // Gabungkan string di sini

            $image->text($namaOrganisasi, 440, 405, function ($font) {
                $font->file(public_path('fonts/OpenSans-SemiBold.ttf'));
                $font->size(35);
                $font->color('#C70000');
            });
            $image->text($org->nomor_induk ?? '-', 440, 530, function ($font) {
                $font->file(public_path('fonts/OpenSans-Regular.ttf'));
                $font->size(50);
                $font->color('#222');
            });

            // 🔹 TAMBAHKAN: Foto ketua (pas foto)
            $fotoKetua = $org->dataPendukung->where('tipe', 'photo')->first();

            if ($fotoKetua) {
                $fotoPath = $org->getFilePath($fotoKetua);

                if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                    $fullFotoPath = storage_path("app/public/{$fotoPath}");

                    if (File::exists($fullFotoPath)) {
                        try {
                            $foto = Image::read($fullFotoPath);
                            $foto->resize(150, 180); // Ukuran pas foto
                            $image->place($foto, 'top-left', 100, 150);
                            Log::info("Foto ketua berhasil ditambahkan");
                        } catch (\Exception $e) {
                            Log::error("Gagal memproses foto ketua: " . $e->getMessage());
                        }
                    }
                }
            }

            // 🔹 Alamat (DENGAN WORD WRAP)
            $namaDesa = $org->desaWilayah->nama ?? '';
            $namaKecamatan = $org->kecamatanWilayah->nama ?? '';
            $alamat = "{$org->alamat}, {$namaDesa}, {$namaKecamatan}, Banyuwangi";

            $characterLimit = 45;
            $wrappedAlamat = wordwrap($alamat, $characterLimit, "\n", true);
            $lines = explode("\n", $wrappedAlamat);

            $startY = 620;
            $lineHeight = 36;

            foreach ($lines as $index => $line) {
                $yPos = $startY + ($index * $lineHeight);
                $image->text(trim($line), 120, $yPos, function ($font) {
                    $font->file(public_path('fonts/OpenSans-Regular.ttf'));
                    $font->size(26);
                    $font->color('#002C72');
                });
            }

            // 🔹 Masa aktif
            $masa = "Aktif Sampai " . ($org->tanggal_expired ? Carbon::parse($org->tanggal_expired)->format('d.m.Y') : '-');
            $image->text($masa, 700, 720, function ($font) {
                $font->file(public_path('fonts/OpenSans-Regular.ttf'));
                $font->size(22);
                $font->color('#000');
            });

            // 🔹 Simpan hasil
            $outputPath = "{$orgPath}/kartu_induk_generated.png";
            $image->save($outputPath, 90);

            // =================================================================
            // ✅ PERUBAHAN LOGIKA OUTPUT
            // =================================================================

            // 3. Cek query parameter 'download'
            if ($request->query('download') == 'true') {
                // Jika ?download=true, paksa browser untuk mengunduh file
                $filename = 'kartu_induk_' . ($org->nomor_induk ?? $org->id) . '.png';
                return response()->download($outputPath, $filename);
            }

            // 4. Perilaku default: kembalikan file untuk ditampilkan di browser
            return response()->file($outputPath);

        } catch (\Exception $e) {
            Log::error("Gagal generate kartu: " . $e->getMessage() . ' di baris ' . $e->getLine());
            return back()->with('error', 'Gagal membuat kartu: ' . $e->getMessage());
        }
    }
}
