<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PesertaManagementController extends Controller
{
    /**
     * Simpan peserta magang baru dari dashboard admin/mentor.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'regex:/^\S+$/', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'instansi' => ['required', 'string', 'max:255'],
            'nomor_telepon' => ['required', 'string', 'max:30'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'alamat' => ['nullable', 'string'],
        ], [
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'username.regex' => 'Username tidak boleh mengandung spasi.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.peserta.detail')
                ->withErrors($validator, 'addPeserta')
                ->withInput();
        }

        $validated = $validator->validated();

        $createdDate = Carbon::today();
        $endDate = Carbon::parse($validated['tanggal_selesai'])->startOfDay();
        $shouldArchive = $endDate->lt($createdDate);

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make('password123'),
            'role' => $shouldArchive ? 'alumni' : 'magang',
            'instansi' => $validated['instansi'],
            'nomor_telepon' => $validated['nomor_telepon'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'alamat' => $validated['alamat'] ?? null,
        ]);

        $message = 'Peserta baru berhasil ditambahkan. Password default: password123';
        if ($shouldArchive) {
            $message .= ' Tanggal selesai sebelum tanggal pembuatan akun, peserta otomatis masuk arsip.';
        }

        return redirect()
            ->route('admin.peserta.detail')
            ->with('success', $message);
    }

    /**
     * Update data detail peserta magang.
     */
    public function update(Request $request, User $peserta): RedirectResponse
    {
        if ($peserta->role !== 'magang') {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'edit_name' => ['required', 'string', 'max:255'],
            'edit_username' => ['required', 'string', 'max:255', 'regex:/^\S+$/', 'unique:users,username,' . $peserta->id],
            'edit_email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $peserta->id],
            'edit_instansi' => ['required', 'string', 'max:255'],
            'edit_nomor_telepon' => ['required', 'string', 'max:30'],
            'edit_tanggal_mulai' => ['required', 'date'],
            'edit_tanggal_selesai' => ['required', 'date', 'after_or_equal:edit_tanggal_mulai'],
            'edit_alamat' => ['nullable', 'string'],
            'edit_password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'edit_tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            'edit_password.min' => 'Password baru minimal 8 karakter.',
            'edit_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'edit_username.required' => 'Username wajib diisi.',
            'edit_username.unique' => 'Username sudah digunakan.',
            'edit_username.regex' => 'Username tidak boleh mengandung spasi.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.peserta.detail')
                ->withErrors($validator, 'editPeserta')
                ->withInput($request->all() + ['edit_user_id' => $peserta->id]);
        }

        $validated = $validator->validated();

        $accountCreatedDate = optional($peserta->created_at)->copy()->startOfDay() ?? now()->startOfDay();
        $endDate = Carbon::parse($validated['edit_tanggal_selesai'])->startOfDay();
        $shouldArchive = $endDate->lt($accountCreatedDate);

        $updatePayload = [
            'name' => $validated['edit_name'],
            'username' => $validated['edit_username'],
            'email' => $validated['edit_email'],
            'instansi' => $validated['edit_instansi'],
            'nomor_telepon' => $validated['edit_nomor_telepon'],
            'tanggal_mulai' => $validated['edit_tanggal_mulai'],
            'tanggal_selesai' => $validated['edit_tanggal_selesai'],
            'alamat' => $validated['edit_alamat'] ?? null,
            'role' => $shouldArchive ? 'alumni' : 'magang',
        ];

        if (!empty($validated['edit_password'])) {
            $updatePayload['password'] = Hash::make((string) $validated['edit_password']);
        }

        $peserta->update($updatePayload);

        $message = 'Data peserta berhasil diperbarui.';
        if ($shouldArchive) {
            $message .= ' Peserta otomatis dipindahkan ke arsip karena tanggal selesai sebelum tanggal pembuatan akun.';
        }

        return redirect()
            ->route('admin.peserta.detail')
            ->with('success', $message);
    }

    /**
     * Hapus permanen peserta arsip dari database.
     */
    public function destroyPermanent(User $peserta): RedirectResponse
    {
        $isArchived = $peserta->role === 'alumni'
            || ($peserta->role === 'magang'
                && !is_null($peserta->tanggal_selesai)
                && $peserta->tanggal_selesai->isBefore(now()->startOfDay()));

        if (!$isArchived) {
            return redirect()
                ->route('admin.peserta.detail')
                ->with('error', 'Peserta aktif tidak dapat dihapus permanen dari menu arsip.');
        }

        $peserta->delete();

        return redirect()
            ->route('admin.peserta.detail')
            ->with('success', 'Peserta arsip berhasil dihapus permanen dari database.');
    }

}
