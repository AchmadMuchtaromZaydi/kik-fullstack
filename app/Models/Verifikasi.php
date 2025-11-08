<?php
// app/Models/Verifikasi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Verifikasi extends Model
{
    protected $table = 'kik_verifikasi';

    protected $fillable = [
        'organisasi_id',
        'tipe',
        'status',
        'catatan',
        'keterangan',
        'verified_by',
        'tanggal_verifikasi',
        'tanggal_review',
        'userid_review',
        'foto'
    ];

    protected $casts = [
        'tanggal_verifikasi' => 'datetime',
        'tanggal_review' => 'datetime',
    ];

    /**
     * Relasi ke organisasi
     */
    public function organisasi(): BelongsTo
    {
        return $this->belongsTo(Organisasi::class, 'organisasi_id');
    }

    /**
     * Relasi ke user yang melakukan review
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userid_review');
    }

    /**
     * Relasi ke user yang melakukan verifikasi final
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Accessor untuk status badge
     */
    public function getStatusBadgeAttribute(): string
    {
        $colors = [
            'valid' => 'success',
            'tdk_valid' => 'danger',
        ];

        $texts = [
            'valid' => 'Valid',
            'tdk_valid' => 'Tidak Valid',
        ];

        $color = $colors[$this->status] ?? 'secondary';
        $text = $texts[$this->status] ?? $this->status;

        return '<span class="badge bg-' . $color . '">' . $text . '</span>';
    }

    /**
     * Accessor untuk tipe formatted
     */
    public function getTipeFormattedAttribute(): string
    {
        $tipes = [
            'data_organisasi' => 'Data Organisasi',
            'data_anggota' => 'Data Anggota',
            'data_inventaris' => 'Data Inventaris',
            'data_pendukung' => 'Dokumen Pendukung',
        ];

        return $tipes[$this->tipe] ?? $this->tipe;
    }

    /**
     * Scope untuk tipe tertentu
     */
    public function scopeTipe($query, $tipe)
    {
        return $query->where('tipe', $tipe);
    }

    /**
     * Scope untuk status tertentu
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk organisasi tertentu
     */
    public function scopeOrganisasi($query, $organisasiId)
    {
        return $query->where('organisasi_id', $organisasiId);
    }
}
