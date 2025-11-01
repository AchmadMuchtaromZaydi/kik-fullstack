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
        Schema::create('records_api', function (Blueprint $table) {
            $table->string('method')->nullable();
            $table->string('from_url')->nullable();
            $table->text('payload')->nullable();
            $table->dateTime('datetime')->nullable();
            $table->integer('userid_access')->nullable();
            $table->string('nama_user')->nullable();
            $table->integer('id', true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('records_api');
    }
};
