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
        'nama_jenis_kesenian', 'nama_kecamatan', 'nama_desa'
    ];

    protected $casts = [
        'tanggal_berdiri' => 'date',
        'tanggal_daftar' => 'date',
        'tanggal_expired' => 'date',
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

    // Method untuk mendapatkan path file
    public function getFilePath($dataPendukung)
    {
        if (!$dataPendukung || !$dataPendukung->image) {
            return null;
        }

        // Coba beberapa kemungkinan path
        $possiblePaths = [
            'uploads/organisasi/' . $this->id . '/' . $dataPendukung->image,
            'organisasi/' . $this->id . '/' . $dataPendukung->image,
            'public/uploads/organisasi/' . $this->id . '/' . $dataPendukung->image,
            'storage/uploads/organisasi/' . $this->id . '/' . $dataPendukung->image,
            $dataPendukung->image, // Jika sudah full path
        ];

        foreach ($possiblePaths as $path) {
            if (Storage::exists($path)) {
                return $path;
            }

            // Coba tanpa 'public/'
            if (strpos($path, 'public/') === 0) {
                $altPath = str_replace('public/', '', $path);
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
        return $filePath ? Storage::url($filePath) : null;
    }

    // Method untuk mengecek apakah file exists
    public function getFileExists($dataPendukung)
    {
        return $this->getFilePath($dataPendukung) !== null;
    }

    // Accessor untuk data pendukung yang difilter
    public function getDokumenKtpAttribute()
    {
        return $this->dataPendukung->where('tipe', 'KTP')->first();
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
        return $this->dataPendukung->where('tipe', 'PAS-FOTO')->first();
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
        return $this->dataPendukung->where('tipe', 'BANNER')->first();
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
        return $this->dataPendukung->where('tipe', 'FOTO-KEGIATAN');
    }

    // Method untuk mendapatkan URL foto kegiatan
    public function getFotoKegiatanUrls()
    {
        $urls = [];
        foreach ($this->dokumen_kegiatan as $foto) {
            $urls[] = $this->getFileUrl($foto);
        }
        return $urls;
    }

        // Method untuk mengecek exists foto kegiatan
    public function getFotoKegiatanExists()
    {
        $exists = [];
        foreach ($this->dokumen_kegiatan as $foto) {
            $exists[] = $this->getFileExists($foto);
        }
        return $exists;
    }

    // Method baru untuk mendapatkan data foto kegiatan yang sudah dipasangkan
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
