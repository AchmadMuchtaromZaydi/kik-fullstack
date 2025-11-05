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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Scope untuk data yang boleh diakses
     */
    public function scopeAccessible($query)
    {
        // Tambahkan logic authorization di sini
        // Contoh: return $query->where('status', 'Allow');
        return $query;
    }
}
