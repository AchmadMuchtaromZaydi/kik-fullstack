<?php
// app/Models/Organisasi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organisasi extends Model
{
    protected $table = 'kik_organisasi';

    protected $fillable = [
        'nomor_induk', 'nama', 'nama_ketua', 'no_telp_ketua',
        'tanggal_berdiri', 'tanggal_daftar', 'tanggal_expired',
        'alamat', 'desa', 'kecamatan', 'kabupaten', 'jenis_kesenian',
        'sub_kesenian', 'jumlah_anggota', 'status', 'user_id', 'keterangan'
    ];

    protected $casts = [
        'tanggal_berdiri' => 'date',
        'tanggal_daftar' => 'date',
        'tanggal_expired' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function anggota()
    {
        return $this->hasMany(Anggota::class, 'organisasi_id');
    }
}
