<?php
// app/Models/Anggota.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    protected $table = 'kik_anggota';

    protected $fillable = [
        'nik', 'nama', 'jenis_kelamin', 'tanggal_lahir', 'pekerjaan',
        'alamat', 'whatsapp', 'telepon', 'jabatan', 'foto',
        'organisasi_id', 'validasi'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class, 'organisasi_id');
    }
}
