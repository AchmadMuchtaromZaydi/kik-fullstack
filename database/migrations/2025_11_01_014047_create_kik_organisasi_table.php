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
        Schema::create('kik_organisasi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nomor_induk')->nullable()->unique('nomor_induk');
            $table->string('nama')->nullable();
            $table->string('nama_ketua', 200)->nullable();
            $table->string('no_telp_ketua', 20)->nullable();
            $table->date('tanggal_berdiri')->nullable();
            $table->date('tanggal_daftar')->nullable();
            $table->date('tanggal_expired')->nullable();
            $table->date('tanggal_cetak_kartu')->nullable();
            $table->integer('perpanjangan_ke')->nullable();
            $table->text('alamat')->nullable();
            $table->string('desa')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('nama_kecamatan')->nullable();
            $table->string('nama_desa')->nullable();
            $table->string('jenis_kesenian')->nullable();
            $table->string('sub_kesenian', 100)->nullable();
            $table->string('nama_jenis_kesenian')->nullable();
            $table->string('nama_sub_kesenian')->nullable();
            $table->integer('jumlah_anggota')->nullable();
            $table->integer('logo')->nullable();
            $table->enum('status', ['Request', 'Allow', 'Denny', 'DataLama'])->nullable();
            $table->string('kartu')->nullable();
            $table->string('kode_kartu')->nullable()->unique('kode_kartu');
            $table->integer('user_id')->nullable()->unique('user_id');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kik_organisasi');
    }
};
