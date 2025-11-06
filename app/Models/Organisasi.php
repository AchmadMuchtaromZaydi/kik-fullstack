<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
    // 'jenis_kesenian' adalah kolom di tabel organisasi yang menyimpan ID jenis kesenian
}

// Relasi ke sub jenis kesenian
public function subKesenianObj()
{
    return $this->belongsTo(JenisKesenian::class, 'sub_kesenian');
    // 'sub_kesenian' adalah kolom di tabel organisasi yang menyimpan ID sub jenis
}
}
