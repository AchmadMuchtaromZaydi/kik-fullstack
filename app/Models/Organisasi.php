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

    // === BOOT ===
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }

    // === ACCESSORS ===
   public function getJenisKesenianNamaAttribute()
{
    return $this->jenisKesenianObj->nama ?? $this->nama_jenis_kesenian ?? 'Tidak diketahui';
}


    public function getSubKesenianNamaAttribute()
    {
        return $this->subKesenianObj->nama ?? $this->nama_sub_kesenian ?? 'Tidak ada sub jenis';
    }

    public function getStatusBadgeAttribute()
    {
        $statusColors = [
            'Request' => 'warning',
            'Allow' => 'success',
            'Denny' => 'danger',
            'DataLama' => 'info',
        ];

        $statusTexts = [
            'Request' => 'Menunggu',
            'Allow' => 'Diterima',
            'Denny' => 'Ditolak',
            'DataLama' => 'Data Lama',
        ];

        $color = $statusColors[$this->status] ?? 'secondary';
        $text = $statusTexts[$this->status] ?? $this->status;

        return '<span class="badge bg-' . $color . '">' . e($text) . '</span>';
    }

    // === WILAYAH ===
    public function getNamaKecamatanAttribute()
    {
        if ($this->relationLoaded('kecamatanWilayah') && $this->kecamatanWilayah) {
            return $this->kecamatanWilayah->nama;
        }

        return $this->attributes['nama_kecamatan'] ?? '-';
    }

    public function getNamaDesaAttribute()
    {
        if ($this->relationLoaded('desaWilayah') && $this->desaWilayah) {
            return $this->desaWilayah->nama;
        }

        return $this->attributes['nama_desa'] ?? '-';
    }

    // === KETUA ===
    public function getNamaKetuaAttribute()
    {
        if ($this->relationLoaded('ketua') && $this->ketua) {
            return $this->ketua->nama;
        }

        return $this->attributes['nama_ketua'] ?? '-';
    }

    public function getNoTelpKetuaAttribute()
    {
        if ($this->relationLoaded('ketua') && $this->ketua) {
            return $this->ketua->telepon ?? $this->ketua->whatsapp ?? '-';
        }

        return $this->attributes['no_telp_ketua'] ?? '-';
    }

    // === FILE HANDLING ===
    public function getFilePath($dataPendukung)
    {
        if (!$dataPendukung || !$dataPendukung->image) return null;

        $possiblePaths = [
            "uploads/organisasi/{$this->id}/" . basename($dataPendukung->image),
            "uploads/organisasi/{$this->id}/" . $dataPendukung->image,
            $dataPendukung->image,
            "public/uploads/organisasi/{$this->id}/" . basename($dataPendukung->image),
            "public/" . $dataPendukung->image,
        ];

        foreach ($possiblePaths as $path) {
            if (Storage::disk('public')->exists($path)) return $path;
            if (Storage::exists($path)) return $path;

            if (str_starts_with($path, 'public/')) {
                $altPath = str_replace('public/', '', $path);
                if (Storage::disk('public')->exists($altPath)) return $altPath;
                if (Storage::exists($altPath)) return $altPath;
            }
        }

        return null;
    }

    public function getFileUrl($dataPendukung)
    {
        $filePath = $this->getFilePath($dataPendukung);
        if (!$filePath) return null;

        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->url($filePath);
        }
        if (Storage::exists($filePath)) {
            return Storage::url($filePath);
        }
        return null;
    }

    public function getFileExists($dataPendukung)
    {
        $filePath = $this->getFilePath($dataPendukung);
        if (!$filePath) return false;
        return Storage::disk('public')->exists($filePath) || Storage::exists($filePath);
    }

    // === DATA PENDUKUNG ===
    public function getDokumenKtpAttribute()
    {
        return $this->relationLoaded('dataPendukung')
            ? $this->dataPendukung->where('tipe', 'ktp')->first()
            : null;
    }

    public function getDokumenPasFotoAttribute()
    {
        return $this->relationLoaded('dataPendukung')
            ? $this->dataPendukung->where('tipe', 'photo')->first()
            : null;
    }

    public function getDokumenBannerAttribute()
    {
        return $this->relationLoaded('dataPendukung')
            ? $this->dataPendukung->where('tipe', 'banner')->first()
            : null;
    }

    public function getDokumenKegiatanAttribute()
    {
        return $this->relationLoaded('dataPendukung')
            ? $this->dataPendukung->where('tipe', 'kegiatan')
            : collect();
    }

    public function getFotoKegiatanWithStatus()
    {
        return $this->dokumen_kegiatan->map(function ($foto, $index) {
            return [
                'foto' => $foto,
                'url' => $this->getFileUrl($foto),
                'exists' => $this->getFileExists($foto),
                'index' => $index,
            ];
        })->values();
    }

    // === RELATIONS ===
    public function kabupatenWilayah()
    {
        return $this->belongsTo(Wilayah::class, 'kabupaten_kode', 'kode');
    }

    public function kecamatanWilayah()
    {
        return $this->belongsTo(Wilayah::class, 'kecamatan', 'kode');
    }

    public function desaWilayah()
    {
        return $this->belongsTo(Wilayah::class, 'desa', 'kode');
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

    // ✅ FIXED: tidak lagi memanggil kolom 'deskripsi' yang tidak ada
    public function dataPendukung()
    {
        return $this->hasMany(DataPendukung::class, 'organisasi_id')
            ->select('id', 'organisasi_id', 'image', 'tipe', 'validasi');
    }

    public function verifikasi()
    {
        return $this->hasMany(Verifikasi::class, 'organisasi_id');
    }

    public function ketua()
    {
        return $this->hasOne(Anggota::class, 'organisasi_id')->where('jabatan', 'Ketua');
    }
}
