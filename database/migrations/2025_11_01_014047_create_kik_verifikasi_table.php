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
        Schema::create('kik_verifikasi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('organisasi_id')->index('kik_verifikasi_organisasi_id_foreign');
            $table->string('status');
            $table->text('catatan')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('tipe')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable()->index('kik_verifikasi_verified_by_foreign');
            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->timestamp('tanggal_review')->nullable();
            $table->unsignedBigInteger('userid_review')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kik_verifikasi');
    }
};
