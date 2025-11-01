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
        Schema::table('kik_verifikasi', function (Blueprint $table) {
            $table->foreign(['organisasi_id'])->references(['id'])->on('kik_organisasi')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['verified_by'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kik_verifikasi', function (Blueprint $table) {
            $table->dropForeign('kik_verifikasi_organisasi_id_foreign');
            $table->dropForeign('kik_verifikasi_verified_by_foreign');
        });
    }
};
