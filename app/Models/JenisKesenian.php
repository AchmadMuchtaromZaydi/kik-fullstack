<?php
// app/Models/JenisKesenian.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisKesenian extends Model
{
    protected $table = 'kik_jeniskesenian';

    // Nonaktifkan timestamps
    public $timestamps = false;

    protected $fillable = [
        'nama', 'parent', 'jenis_kesenian_id_lama', 'sub_kesenian_id_lama'
    ];

    // Relasi untuk parent (jenis utama)
    public function parentJenis()
    {
        return $this->belongsTo(JenisKesenian::class, 'parent');
    }

    // Relasi untuk sub jenis
    public function sub()
    {
        return $this->hasMany(JenisKesenian::class, 'parent');
    }

    // Scope untuk jenis utama (tanpa parent)
    public function scopeJenisUtama($query)
    {
        return $query->whereNull('parent');
    }

    // Scope untuk sub jenis
    public function scopeSubJenis($query)
    {
        return $query->whereNotNull('parent');
    }
}
