<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kik_organisasi', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
        });

        // Generate UUID untuk data yang sudah ada
        $organisations = \App\Models\Organisasi::all();
        foreach ($organisations as $org) {
            $org->update(['uuid' => Str::uuid()]);
        }

        // Set kolom uuid menjadi not nullable setelah diisi
        Schema::table('kik_organisasi', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('kik_organisasi', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
