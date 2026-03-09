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
        Schema::create('custom_working_days', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->time('jam_masuk');
            $table->time('jam_pulang');
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->index(['tanggal_mulai', 'tanggal_selesai']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_working_days');
    }
};
