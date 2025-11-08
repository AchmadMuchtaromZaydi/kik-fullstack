<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataPendukung extends Model
{
    protected $table = 'kik_datapendukung';

    protected $fillable = [
        'tipe',
        'image',
        'organisasi_id',
        'validasi',
    ];

    // Relasi ke Organisasi
    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class, 'organisasi_id');
    }
}
