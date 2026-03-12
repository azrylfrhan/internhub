<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MagangUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $magangs = [
            [
                'name' => 'Ahmad Rizki',
                'username' => 'azrial_magang',
                'email' => 'ahmad@bps.go.id',
                'role' => 'magang'
            ]
        ];

        foreach ($magangs as $magang) {
            User::updateOrCreate(
                ['email' => $magang['email']],
                array_merge($magang, [
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ])
            );
        }
    }
}
