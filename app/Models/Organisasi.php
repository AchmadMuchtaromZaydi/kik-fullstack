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

    // Note: appends tetap seperti semula (tidak wajib memasukkan dokumen_* di sini)
    protected $appends = [
        'jenis_kesenian_nama',
        'sub_kesenian_nama',
        'status_badge',
        'nama_kecamatan',
        'nama_desa',
        'nama_ketua',
        'no_telp_ketua'
    ];

    /* -----------------------------------------------------------------
     |  BOOT
     | -----------------------------------------------------------------
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }

    /* -----------------------------------------------------------------
     |  ACCESSORS UMUM
     | -----------------------------------------------------------------
     */
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

    /* -----------------------------------------------------------------
     |  WILAYAH
     | -----------------------------------------------------------------
     */
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

    /* -----------------------------------------------------------------
     |  KETUA
     | -----------------------------------------------------------------
     */
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

    /* -----------------------------------------------------------------
     |  FILE HANDLING
     | -----------------------------------------------------------------
     */

    /**
     * Dapatkan path file yang valid dari storage (relative untuk disk public)
     * atau fallback ke public/storage
     *
     * Mengembalikan:
     *  - 'uploads/organisasi/{id}/{filename}'  (jika ada di storage/app/public)
     *  - 'storage/uploads/organisasi/{id}/{filename}' (jika file ada di public/storage)
     *  - null jika tidak ditemukan
     */
    public function getFilePath($dataPendukung)
    {
        if (!$dataPendukung || empty($dataPendukung->image)) {
            return null;
        }

        $filename = basename($dataPendukung->image);
        $relativePath = "uploads/organisasi/{$this->id}/{$filename}";

        // 1) cek di disk 'public' (storage/app/public)
        if (Storage::disk('public')->exists($relativePath)) {
            return $relativePath;
        }

        // 2) fallback: cek di public/storage (symlink)
        if (file_exists(public_path("storage/{$relativePath}"))) {
            return "storage/{$relativePath}";
        }

        return null;
    }

    /**
     * Dapatkan URL publik dari file yang ditemukan.
     */
   public function getFileUrl($dataPendukung)
{
    $path = $this->getFilePath($dataPendukung);

    if (!$path) {
        return null;
    }

    // Normalisasi base URL agar sesuai dengan yang sedang digunakan (localhost atau 127)
    $baseUrl = request()->getSchemeAndHttpHost();
    // contoh: http://127.0.0.1:8000 atau http://localhost:8000

    // Jika path sudah mengandung 'storage/', artinya file ada di public/storage
    if (str_starts_with($path, 'storage/')) {
        return "{$baseUrl}/" . ltrim($path, '/');
    }

    // Jika path berasal dari disk 'public'
    $url = Storage::disk('public')->url($path);

    // Pastikan base URL diganti sesuai environment saat ini (127 atau localhost)
    return str_replace(['http://localhost', 'https://localhost'], $baseUrl, $url);
}


    /**
     * Mengecek keberadaan file.
     */
    public function getFileExists($dataPendukung)
    {
        return $this->getFilePath($dataPendukung) !== null;
    }

    /* -----------------------------------------------------------------
     |  ACCESSOR UNTUK BLADE (URL & EXISTS)
     |  (dipakai oleh view tanpa mengubah view)
     | -----------------------------------------------------------------
     */

    // --- KTP ---
    public function getDokumenKtpUrlAttribute()
    {
        return $this->getFileUrl($this->dokumen_ktp);
    }

    public function getDokumenKtpFileExistsAttribute()
    {
        return $this->getFileExists($this->dokumen_ktp);
    }

    // --- PAS FOTO ---
    public function getDokumenPasFotoUrlAttribute()
    {
        return $this->getFileUrl($this->dokumen_pas_foto);
    }

    public function getDokumenPasFotoFileExistsAttribute()
    {
        return $this->getFileExists($this->dokumen_pas_foto);
    }

    // --- BANNER ---
    public function getDokumenBannerUrlAttribute()
    {
        return $this->getFileUrl($this->dokumen_banner);
    }

    public function getDokumenBannerFileExistsAttribute()
    {
        return $this->getFileExists($this->dokumen_banner);
    }

    /* -----------------------------------------------------------------
     |  DATA PENDUKUNG ACCESSORS (case-insensitive + tolerant terhadap format)
     | -----------------------------------------------------------------
     */

    protected function normalizeTipe(?string $tipe): string
    {
        // safe normalize: lower + remove spaces, dashes, underscores
        return strtolower(str_replace([' ', '-', '_'], '', (string) $tipe));
    }

    public function getDokumenKtpAttribute()
    {
        return $this->relationLoaded('dataPendukung')
            ? $this->dataPendukung->firstWhere(fn($item) => str_contains($this->normalizeTipe($item->tipe), 'ktp'))
            : null;
    }

    public function getDokumenPasFotoAttribute()
    {
        return $this->relationLoaded('dataPendukung')
            ? $this->dataPendukung->firstWhere(fn($item) => $this->normalizeTipe($item->tipe) === 'pasfoto' || str_contains($this->normalizeTipe($item->tipe), 'pasfoto'))
            : null;
    }

    public function getDokumenBannerAttribute()
    {
        return $this->relationLoaded('dataPendukung')
            ? $this->dataPendukung->firstWhere(fn($item) => str_contains($this->normalizeTipe($item->tipe), 'banner'))
            : null;
    }

    public function getDokumenKegiatanAttribute()
    {
        return $this->relationLoaded('dataPendukung')
            ? $this->dataPendukung->filter(fn($item) => str_contains($this->normalizeTipe($item->tipe), 'kegiatan') || str_contains($this->normalizeTipe($item->tipe), 'fotokegiatan') || str_contains($this->normalizeTipe($item->tipe), 'foto'))
            : collect();
    }

    /**
     * Kembalikan koleksi foto kegiatan lengkap dengan url & exists
     */
    public function getFotoKegiatanWithStatus()
    {
        return $this->getDokumenKegiatanAttribute()->map(function ($foto, $index) {
            return [
                'foto' => $foto,
                'url' => $this->getFileUrl($foto),
                'exists' => $this->getFileExists($foto),
                'index' => $index,
            ];
        })->values();
    }

    /* -----------------------------------------------------------------
     |  RELASI
     | -----------------------------------------------------------------
     */
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
        return $this->hasOne(Anggota::class, 'organisasi_id')
            ->where('jabatan', 'Ketua');
    }
}
