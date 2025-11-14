<?php

namespace App\Http\Controllers;

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

    public function generateKartu(Request $request, $id)
    {
        try {
            $org = Organisasi::with([
                'jenisKesenianObj',
                'kecamatanWilayah',
                'desaWilayah',
                'dataPendukung',
                'ketua'
            ])->findOrFail($id);

            $orgPath = public_path("storage/uploads/organisasi/{$org->id}");
            if (!File::exists($orgPath)) {
                File::makeDirectory($orgPath, 0777, true);
            }

            $templatePath = public_path('images/template-2.jpeg');
            $qrcodePath = public_path('images/qrcode.png');

            if (!File::exists($templatePath)) {
                return back()->with('error', 'Template kartu tidak ditemukan.');
            }

            $image = Image::read($templatePath);

// ===========================================================
            // 🧍‍♂️ PAS FOTO — (PERUBAHAN DI SINI SESUAI PERMINTAAN ANDA)
            // ===========================================================
            $fotoKetua = $org->dataPendukung->where('tipe', 'PAS-FOTO')->first();
            if ($fotoKetua) {
                $namaFileFoto = $fotoKetua->image;
                $fotoPathRelatif = "uploads/organisasi/{$org->id}/{$namaFileFoto}";

                if (Storage::disk('public')->exists($fotoPathRelatif)) {
                    $fullFotoPath = Storage::disk('public')->path($fotoPathRelatif);
                    try {
                        // --- AWAL BLOK PERBAIKAN (SESUAI KODE ANDA) ---
                        $foto = Image::read($fullFotoPath);
                        $foto->resize(287, 325); // Ukuran 3x4
                        // Membulatkan 116.5 -> 117 dan 237.5 -> 238
                        $image->place($foto, 'top-left', 114, 244); // Posisi kotak foto
                        // --- AKHIR BLOK PERBAIKAN ---

                    } catch (\Exception $e) {
                        Log::error("Gagal memproses foto ketua: " . $e->getMessage());
                    }
                }
            }

            // ===========================================================
            // 🧾 TEKS INFORMASI (kanan)
            // ===========================================================
            $image->text(strtoupper($org->nama_ketua ?? '-'), 440, 300, function ($font) {
                $font->file(public_path('fonts/OpenSans-Bold.ttf'));
                $font->size(50);
                $font->color('#004080');
                $font->align('left');
            });

            $image->text("Ketua\n" . ($org->nama ?? '-'), 440, 390, function ($font) {
                $font->file(public_path('fonts/OpenSans-SemiBold.ttf'));
                $font->size(36);
                $font->color('#C70000');
                $font->align('left');
            });

            $image->text($org->nomor_induk ?? '-', 440, 510, function ($font) {
                $font->file(public_path('fonts/OpenSans-Regular.ttf'));
                $font->size(55);
                $font->color('#000000');
            });

            // ===========================================================
            // 🏠 ALAMAT — versi lama (kanan bawah)
            // ===========================================================
            $namaDesa = $org->desaWilayah->nama ?? '';
            $namaKecamatan = $org->kecamatanWilayah->nama ?? '';
            $alamat = "{$org->alamat}, {$namaDesa}, {$namaKecamatan}, Banyuwangi";

            $characterLimit = 30;
            $wrappedAlamat = wordwrap($alamat, $characterLimit, "\n", true);
            $lines = explode("\n", $wrappedAlamat);

            $startY = 630;
            $lineHeight = 38;

            foreach ($lines as $index => $line) {
                $yPos = $startY + ($index * $lineHeight);
                $image->text(trim($line), 120, $yPos, function ($font) {
                    $font->file(public_path('fonts/OpenSans-Regular.ttf'));
                    $font->size(37);
                    $font->color('#002C72');
                    $font->align('left');
                });
            }

            // ===========================================================
            // 🕒 MASA AKTIF
            // ===========================================================
            $masa = "Aktif sampai " . ($org->tanggal_expired ? Carbon::parse($org->tanggal_expired)->format('d.m.Y') : '-');
            $image->text($masa, 820, 700, function ($font) {
                $font->file(public_path('fonts/OpenSans-SemiBold.ttf'));
                $font->size(30);
                $font->color('#000');
            });

            // ===========================================================
            // 📱 QR CODE di pojok kanan bawah
            // ===========================================================
            if (File::exists($qrcodePath)) {
                $qr = Image::read($qrcodePath)->resize(215, 215);
                $image->place($qr, 'bottom-center', 190, 100);
            }

            // ===========================================================
            // 💾 Simpan hasil
            // ===========================================================
            $outputPath = "{$orgPath}/kartu_induk_generated.png";
            $image->save($outputPath, 90);

            if ($request->query('download') == 'true') {
                $filename = 'kartu_induk_' . ($org->nomor_induk ?? $org->id) . '.png';
                return response()->download($outputPath, $filename);
            }

            return response()->file($outputPath);

        } catch (\Exception $e) {
            Log::error("Gagal generate kartu: " . $e->getMessage() . ' di baris ' . $e->getLine());
            return back()->with('error', 'Gagal membuat kartu: ' . $e->getMessage());
        }
    }
}
