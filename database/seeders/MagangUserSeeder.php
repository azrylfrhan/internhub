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
                'email' => 'ahmad@bps.go.id',
                'role' => 'magang'
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@bps.go.id',
                'role' => 'magang'
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@bps.go.id',
                'role' => 'magang'
            ],
            [
                'name' => 'Maya Sari',
                'email' => 'maya@bps.go.id',
                'role' => 'magang'
            ],
            [
                'name' => 'Dika Pratama',
                'email' => 'dika@bps.go.id',
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
