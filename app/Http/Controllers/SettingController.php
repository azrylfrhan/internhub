<?php

namespace App\Http\Controllers;

use App\Models\CustomWorkingDay;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        return view('admin.settings', [
            'settings' => [
                'jam_masuk_senin_kamis' => Setting::getValue('jam_masuk_senin_kamis', '07:30'),
                'jam_pulang_senin_kamis' => Setting::getValue('jam_pulang_senin_kamis', '16:00'),
                'jam_masuk_jumat' => Setting::getValue('jam_masuk_jumat', '07:30'),
                'jam_pulang_jumat' => Setting::getValue('jam_pulang_jumat', '16:00'),
                'office_latitude' => Setting::getValue('office_latitude', '1.46759'),
                'office_longitude' => Setting::getValue('office_longitude', '124.84542'),
                'max_distance_meters' => Setting::getValue('max_distance_meters', '500'),
            ],
            'customWorkingDays' => CustomWorkingDay::orderBy('tanggal_mulai')->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'jam_masuk_senin_kamis' => ['required', 'regex:/^([01]\d|2[0-3])[\.:]([0-5]\d)$/'],
            'jam_pulang_senin_kamis' => ['required', 'regex:/^([01]\d|2[0-3])[\.:]([0-5]\d)$/'],
            'jam_masuk_jumat' => ['required', 'regex:/^([01]\d|2[0-3])[\.:]([0-5]\d)$/'],
            'jam_pulang_jumat' => ['required', 'regex:/^([01]\d|2[0-3])[\.:]([0-5]\d)$/'],
            'office_latitude' => ['required', 'numeric', 'between:-90,90'],
            'office_longitude' => ['required', 'numeric', 'between:-180,180'],
            'max_distance_meters' => ['required', 'integer', 'min:1'],
        ]);

        foreach (['jam_masuk_senin_kamis', 'jam_pulang_senin_kamis', 'jam_masuk_jumat', 'jam_pulang_jumat'] as $timeKey) {
            $validated[$timeKey] = $this->normalizeTimeInput($validated[$timeKey]);
        }

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => (string) $value]
            );
        }

        return back()->with('success', 'Pengaturan jam kerja dan lokasi berhasil diperbarui.');
    }

    public function storeCustomWorkingDay(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'jam_masuk' => ['required', 'regex:/^([01]\d|2[0-3])[\.:]([0-5]\d)$/'],
            'jam_pulang' => ['required', 'regex:/^([01]\d|2[0-3])[\.:]([0-5]\d)$/'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['jam_masuk'] = $this->normalizeTimeInput($validated['jam_masuk']);
        $validated['jam_pulang'] = $this->normalizeTimeInput($validated['jam_pulang']);

        CustomWorkingDay::create($validated);

        return back()->with('success', 'Jam kerja khusus berhasil ditambahkan.');
    }

    public function destroyCustomWorkingDay(CustomWorkingDay $customWorkingDay): RedirectResponse
    {
        $customWorkingDay->delete();

        return back()->with('success', 'Jam kerja khusus berhasil dihapus.');
    }

    private function normalizeTimeInput(string $value): string
    {
        return str_replace('.', ':', trim($value));
    }
}
