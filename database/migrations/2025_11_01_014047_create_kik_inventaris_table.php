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
        Schema::create('kik_inventaris', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nama', 500)->nullable();
            $table->integer('jumlah')->nullable();
            $table->year('pembelian_th')->nullable();
            $table->string('kondisi')->nullable();
            $table->integer('organisasi_id')->nullable();
            $table->boolean('validasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kik_inventaris');
    }
};
