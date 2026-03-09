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
        Schema::table('users', function (Blueprint $table) {
            $table->string('instansi')->nullable()->after('role');
            $table->string('nomor_telepon')->nullable()->after('instansi');
            $table->date('tanggal_mulai')->nullable()->after('nomor_telepon');
            $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
            $table->text('alamat')->nullable()->after('tanggal_selesai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'instansi',
                'nomor_telepon',
                'tanggal_mulai',
                'tanggal_selesai',
                'alamat',
            ]);
        });
    }
};
