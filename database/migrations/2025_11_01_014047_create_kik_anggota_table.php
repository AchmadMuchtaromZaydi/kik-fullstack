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
        Schema::create('kik_anggota', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nik', 200)->nullable();
            $table->string('nama')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->text('alamat')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('telepon')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('foto')->nullable();
            $table->integer('organisasi_id')->nullable();
            $table->tinyInteger('validasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kik_anggota');
    }
};
