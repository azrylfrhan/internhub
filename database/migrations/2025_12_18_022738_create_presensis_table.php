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
                Schema::create('presensis', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('user_id')->constrained()->onDelete('cascade');
                    $table->date('tanggal'); // Tanggal hari ini
                    $table->time('jam_masuk')->nullable();
                    $table->time('jam_pulang')->nullable();
                    $table->string('lokasi_masuk')->nullable(); // Koordinat GPS
                    $table->string('lokasi_pulang')->nullable(); // Koordinat GPS
                    $table->string('foto_masuk')->nullable(); // Link storage foto
                    $table->string('foto_pulang')->nullable(); // Link storage foto
                    $table->enum('status', ['hadir', 'terlambat', 'izin', 'alpa'])->default('hadir');
                    $table->text('keterangan')->nullable(); // Jika izin atau ada info tambahan
                    $table->timestamps();
                });
            }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
