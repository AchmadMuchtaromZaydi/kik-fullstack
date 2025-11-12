<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // 1. PASTIKAN Carbon di-import

class Anggota extends Model
{
    use HasFactory;
    protected $table = 'kik_anggota';
    protected $guarded = [];

    // ================= PERUBAHAN DI SINI =================

    /**
     * 2. Daftarkan 'tanggal_lahir' (sesuai DB Anda) sebagai 'date'
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /**
     * 3. Buat Accessor untuk 'umur'
     * Ini akan menghitung umur dari 'tanggal_lahir'
     */
    public function getUmurAttribute()
    {
        // Pastikan 'tanggal_lahir' (sesuai DB Anda) ada isinya
        if ($this->tanggal_lahir) {
            return $this->tanggal_lahir->age;
        }
        return null; // Kembalikan null jika tanggal_lahir kosong
    }

    // =======================================================


    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class, 'organisasi_id');
    }
}
