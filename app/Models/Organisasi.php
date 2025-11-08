<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Organisasi extends Model
{
    protected $table = 'kik_organisasi';

    protected $fillable = [
        'uuid', 'nomor_induk', 'nama', 'nama_ketua', 'no_telp_ketua',
        'tanggal_berdiri', 'tanggal_daftar', 'tanggal_expired',
        'alamat', 'desa', 'kecamatan', 'kabupaten', 'jenis_kesenian',
        'sub_kesenian', 'jumlah_anggota', 'status', 'user_id', 'keterangan',
        'nama_jenis_kesenian', 'nama_kecamatan', 'nama_desa',
        'kabupaten_kode', 'kecamatan_kode', 'desa_kode'
    ];

    protected $casts = [
        'tanggal_berdiri' => 'date',
        'tanggal_daftar' => 'date',
        'tanggal_expired' => 'date',
    ];

    protected $appends = [
        'jenis_kesenian_nama',
        'sub_kesenian_nama',
        'status_badge',
        'nama_kecamatan',
        'nama_desa',
        'nama_ketua',
        'no_telp_ketua'
    ];

    // Accessor untuk jenis kesenian
    public function getJenisKesenianNamaAttribute()
    {
        if ($this->jenisKesenianObj) {
            return $this->jenisKesenianObj->nama;
        }

        return $this->nama_jenis_kesenian ?? 'Tidak diketahui';
    }

    // Accessor untuk sub jenis kesenian
    public function getSubKesenianNamaAttribute()
    {
        if ($this->subKesenianObj) {
            return $this->subKesenianObj->nama;
        }

        return $this->nama_sub_kesenian ?? 'Tidak ada sub jenis';
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        $statusColors = [
            'Request' => 'warning',
            'Allow' => 'success',
            'Denny' => 'danger',
            'DataLama' => 'info',
        ];

        $color = $statusColors[$this->status] ?? 'secondary';

        $statusTexts = [
            'Request' => 'Menunggu',
            'Allow' => 'Diterima',
            'Denny' => 'Ditolak',
            'DataLama' => 'Data Lama',
        ];

        $text = $statusTexts[$this->status] ?? $this->status;

        return '<span class="badge bg-' . $color . '">' . $text . '</span>';
    }

    // Accessor untuk nama kecamatan
    public function getNamaKecamatanAttribute()
    {
        if ($this->kecamatanObj) {
            return $this->kecamatanObj->nama;
        }

        // Fallback: query langsung ke tabel wilayah
        if ($this->kecamatan) {
            $wilayah = \Illuminate\Support\Facades\DB::table('wilayah')
                ->where('kode', $this->kecamatan)
                ->first();
            return $wilayah->nama ?? $this->kecamatan;
        }

        return '-';
    }

    // Accessor untuk nama desa
    public function getNamaDesaAttribute()
    {
        if ($this->desaObj) {
            return $this->desaObj->nama;
        }

        // Fallback: query langsung ke tabel wilayah
        if ($this->desa) {
            $wilayah = \Illuminate\Support\Facades\DB::table('wilayah')
                ->where('kode', $this->desa)
                ->first();
            return $wilayah->nama ?? $this->desa;
        }

        return '-';
    }

    // Accessor untuk nama ketua
    public function getNamaKetuaAttribute()
    {
        $ketua = $this->anggota()->where('jabatan', 'Ketua')->first();
        return $ketua ? $ketua->nama : '-';
    }

    // Accessor untuk no telp ketua
    public function getNoTelpKetuaAttribute()
    {
        $ketua = $this->anggota()->where('jabatan', 'Ketua')->first();
        return $ketua ? ($ketua->telepon ?? $ketua->whatsapp ?? '-') : '-';
    }

    // Method untuk mendapatkan path file yang benar
    public function getFilePath($dataPendukung)
    {
        if (!$dataPendukung || !$dataPendukung->image) {
            return null;
        }

        // Cek struktur path yang mungkin
        $possiblePaths = [
            // Struktur baru: uploads/organisasi/{id_organisasi}/{filename}
            'uploads/organisasi/' . $this->id . '/' . basename($dataPendukung->image),
            'uploads/organisasi/' . $this->id . '/' . $dataPendukung->image,

            // Struktur lama: langsung dari field image
            $dataPendukung->image,

            // Jika ada public/ di path
            'public/uploads/organisasi/' . $this->id . '/' . basename($dataPendukung->image),
            'public/' . $dataPendukung->image,
        ];

        foreach ($possiblePaths as $path) {
            // Cek di storage public
            if (Storage::disk('public')->exists($path)) {
                return $path;
            }

            // Cek di storage biasa
            if (Storage::exists($path)) {
                return $path;
            }

            // Coba tanpa 'public/'
            if (strpos($path, 'public/') === 0) {
                $altPath = str_replace('public/', '', $path);
                if (Storage::disk('public')->exists($altPath)) {
                    return $altPath;
                }
                if (Storage::exists($altPath)) {
                    return $altPath;
                }
            }
        }

        return null;
    }

    // Method untuk mendapatkan URL file
    public function getFileUrl($dataPendukung)
    {
        $filePath = $this->getFilePath($dataPendukung);

        if (!$filePath) {
            return null;
        }

        // Coba berbagai cara untuk mendapatkan URL
        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->url($filePath);
        }

        if (Storage::exists($filePath)) {
            return Storage::url($filePath);
        }

        return null;
    }

    // Method untuk mengecek apakah file exists
    public function getFileExists($dataPendukung)
    {
        $filePath = $this->getFilePath($dataPendukung);

        if (!$filePath) {
            return false;
        }

        return Storage::disk('public')->exists($filePath) || Storage::exists($filePath);
    }

    // Accessor untuk data pendukung yang difilter
    public function getDokumenKtpAttribute()
    {
        return $this->dataPendukung->where('tipe', 'ktp')->first();
    }

    public function getDokumenKtpFileExistsAttribute()
    {
        $ktp = $this->dokumen_ktp;
        return $ktp ? $this->getFileExists($ktp) : false;
    }

    public function getDokumenKtpUrlAttribute()
    {
        $ktp = $this->dokumen_ktp;
        return $ktp ? $this->getFileUrl($ktp) : null;
    }

    public function getDokumenPasFotoAttribute()
    {
        return $this->dataPendukung->where('tipe', 'photo')->first();
    }

    public function getDokumenPasFotoFileExistsAttribute()
    {
        $pasFoto = $this->dokumen_pas_foto;
        return $pasFoto ? $this->getFileExists($pasFoto) : false;
    }

    public function getDokumenPasFotoUrlAttribute()
    {
        $pasFoto = $this->dokumen_pas_foto;
        return $pasFoto ? $this->getFileUrl($pasFoto) : null;
    }

    public function getDokumenBannerAttribute()
    {
        return $this->dataPendukung->where('tipe', 'banner')->first();
    }

    public function getDokumenBannerFileExistsAttribute()
    {
        $banner = $this->dokumen_banner;
        return $banner ? $this->getFileExists($banner) : false;
    }

    public function getDokumenBannerUrlAttribute()
    {
        $banner = $this->dokumen_banner;
        return $banner ? $this->getFileUrl($banner) : null;
    }

    public function getDokumenKegiatanAttribute()
    {
        return $this->dataPendukung->where('tipe', 'kegiatan');
    }

    // Method untuk mendapatkan data foto kegiatan yang sudah dipasangkan dengan status
    public function getFotoKegiatanWithStatus()
    {
        $fotos = [];
        foreach ($this->dokumen_kegiatan as $index => $foto) {
            $fotos[] = [
                'foto' => $foto,
                'url' => $this->getFileUrl($foto),
                'exists' => $this->getFileExists($foto),
                'index' => $index
            ];
        }
        return $fotos;
    }

    // Method untuk debug file storage
    public function getStorageDebugInfo()
    {
        $debugInfo = [
            'base_path' => storage_path('app'),
            'public_path' => public_path(),
            'storage_url' => Storage::url('test'),
            'organisasi_id' => $this->id,
        ];

        // Info untuk setiap dokumen
        $dokumenInfo = [];

        $dokumenTypes = [
            'ktp' => $this->dokumen_ktp,
            'pas_foto' => $this->dokumen_pas_foto,
            'banner' => $this->dokumen_banner,
        ];

        foreach ($dokumenTypes as $type => $dokumen) {
            if ($dokumen) {
                $dokumenInfo[$type] = [
                    'original_image' => $dokumen->image,
                    'file_path' => $this->getFilePath($dokumen),
                    'file_exists' => $this->getFileExists($dokumen),
                    'file_url' => $this->getFileUrl($dokumen),
                    'storage_exists' => Storage::disk('public')->exists($dokumen->image),
                    'direct_path' => storage_path('app/public/' . $dokumen->image),
                ];
            }
        }

        // Info untuk foto kegiatan
        $kegiatanInfo = [];
        foreach ($this->dokumen_kegiatan as $index => $foto) {
            $kegiatanInfo[] = [
                'index' => $index,
                'original_image' => $foto->image,
                'file_path' => $this->getFilePath($foto),
                'file_exists' => $this->getFileExists($foto),
                'file_url' => $this->getFileUrl($foto),
            ];
        }

        return [
            'storage' => $debugInfo,
            'dokumen' => $dokumenInfo,
            'kegiatan' => $kegiatanInfo,
        ];
    }

    public function kabupaten()
    {
        return $this->belongsTo(Wilayah::class, 'kabupaten_kode', 'kode');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Wilayah::class, 'kecamatan_kode', 'kode');
    }

    public function desa()
    {
        return $this->belongsTo(Wilayah::class, 'desa_kode', 'kode');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function anggota()
    {
        return $this->hasMany(Anggota::class, 'organisasi_id');
    }

    public function jenisKesenianObj()
    {
        return $this->belongsTo(JenisKesenian::class, 'jenis_kesenian');
    }

    public function subKesenianObj()
    {
        return $this->belongsTo(JenisKesenian::class, 'sub_kesenian');
    }

    public function inventaris()
    {
        return $this->hasMany(Inventaris::class, 'organisasi_id');
    }

    public function dataPendukung()
    {
        return $this->hasMany(DataPendukung::class, 'organisasi_id');
    }

    public function verifikasi()
    {
        return $this->hasMany(Verifikasi::class, 'organisasi_id');
    }
}
