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
        Schema::create('kik_datapendukung', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('tipe')->nullable();
            $table->string('image')->nullable();
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
        Schema::dropIfExists('kik_datapendukung');
    }
};
