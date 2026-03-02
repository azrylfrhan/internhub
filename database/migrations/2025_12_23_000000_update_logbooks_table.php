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
        // Drop old table and recreate with correct columns
        if (Schema::hasTable('logbooks')) {
            Schema::dropIfExists('logbooks');
        }

        Schema::create('logbooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('tanggal');
            $table->string('aktivitas'); // Nama aktivitas
            $table->text('deskripsi')->nullable(); // Deskripsi detail
            $table->time('jam_mulai')->nullable(); // Jam mulai aktivitas
            $table->time('jam_selesai')->nullable(); // Jam selesai aktivitas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logbooks');
    }
};
