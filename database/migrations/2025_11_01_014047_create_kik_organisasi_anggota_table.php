<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kik_organisasi_anggota', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('organisasi_id');
            $table->integer('anggota_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kik_organisasi_anggota');
    }
};
