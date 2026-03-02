<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Presensi;
use Carbon\Carbon;

class AutoCheckoutCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'presensi:auto-checkout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatis checkout semua presensi yang belum checkout di hari ini pada jam 23:59';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        
        // Cari semua presensi hari ini yang belum checkout (jam_pulang masih null)
        $presensis = Presensi::where('tanggal', $today)
            ->whereNull('jam_pulang')
            ->get();

        if ($presensis->isEmpty()) {
            $this->info('Tidak ada presensi yang perlu di auto-checkout.');
            return 0;
        }

        $count = 0;
        foreach ($presensis as $presensi) {
            $presensi->update([
                'jam_pulang' => '23:59',
                'lokasi_pulang' => $presensi->lokasi_masuk, // Gunakan lokasi masuk sebagai fallback
                'keterangan' => 'Auto checkout by system'
            ]);
            $count++;
        }

        $this->info("Auto checkout berhasil untuk {$count} presensi.");
        return 0;
    }
}
