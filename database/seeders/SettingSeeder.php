<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
            'jam_masuk_senin_kamis' => '07:30',
            'jam_pulang_senin_kamis' => '16:00',
            'jam_masuk_jumat' => '07:30',
            'jam_pulang_jumat' => '16:00',
            'office_latitude' => '1.46759',
            'office_longitude' => '124.84542',
            'max_distance_meters' => '500',
        ];

        foreach ($defaults as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
