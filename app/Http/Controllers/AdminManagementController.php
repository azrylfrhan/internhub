<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminManagementController extends Controller
{
    public function index(): View
    {
        $users = User::whereIn('role', ['admin', 'mentor'])
            ->orderBy('role')
            ->orderBy('name')
            ->get();

        return view('admin.user-management.index', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['admin', 'mentor'])],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()
            ->route('admin.management.index')
            ->with('success', 'Akun admin/mentor berhasil ditambahkan.');
    }

    public function update(Request $request, User $management): RedirectResponse
    {
        $this->ensureManageableRole($management);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($management->id)],
            'role' => ['required', Rule::in(['admin', 'mentor'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $management->name = $validated['name'];
        $management->email = $validated['email'];
        $management->role = $validated['role'];

        if (!empty($validated['password'])) {
            $management->password = Hash::make($validated['password']);
        }

        $management->save();

        return redirect()
            ->route('admin.management.index')
            ->with('success', 'Data admin/mentor berhasil diperbarui.');
    }

    public function destroy(User $management): RedirectResponse
    {
        $this->ensureManageableRole($management);

        if ((int) auth()->id() === (int) $management->id) {
            return redirect()
                ->route('admin.management.index')
                ->with('error', 'Akun yang sedang login tidak dapat dihapus.');
        }

        $management->delete();

        return redirect()
            ->route('admin.management.index')
            ->with('success', 'Akun admin/mentor berhasil dihapus.');
    }

    private function ensureManageableRole(User $user): void
    {
        if (!in_array($user->role, ['admin', 'mentor'], true)) {
            abort(404);
        }
    }
}
