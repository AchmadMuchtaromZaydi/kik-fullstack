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
        Schema::create('kik_jeniskesenian', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('parent')->nullable();
            $table->string('nama')->nullable();
            $table->integer('jenis_kesenian_id_lama')->nullable();
            $table->integer('sub_kesenian_id_lama')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kik_jeniskesenian');
    }
};
