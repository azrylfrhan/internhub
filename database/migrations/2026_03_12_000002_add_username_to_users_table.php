<?php

use App\Models\User;
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
            $table->string('username')->nullable()->unique()->after('name');
        });

        User::query()
            ->select(['id', 'username', 'email'])
            ->whereNull('username')
            ->orderBy('id')
            ->chunkById(200, function ($users): void {
                foreach ($users as $user) {
                    $base = preg_replace('/\s+/', '', (string) strstr((string) $user->email, '@', true));
                    $base = $base !== '' ? strtolower($base) : 'user';
                    $candidate = $base;
                    $suffix = 1;

                    while (User::query()->where('username', $candidate)->where('id', '!=', $user->id)->exists()) {
                        $candidate = $base . $suffix;
                        $suffix++;
                    }

                    User::query()->where('id', $user->id)->update(['username' => $candidate]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn('username');
        });
    }
};
