<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    // use HasFactory;
    protected $table = 'kik_inventaris';

    protected $fillable = [
        'organisasi_id',
        'nama',
        'jumlah',
        'pembelian_th',
        'kondisi',
        'keterangan',
        'validasi',
    ];
}
