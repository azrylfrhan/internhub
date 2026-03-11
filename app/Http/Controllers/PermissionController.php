<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public function index(): View
    {
        $permissions = Permission::with('user')
            ->orderByRaw("case when status = 'pending' then 0 else 1 end")
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.permissions.index', compact('permissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string', 'max:1500'],
        ]);

        Permission::create([
            'user_id' => Auth::id(),
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return redirect()
            ->route('magang.attendance')
            ->with('success', 'Pengajuan izin berhasil dikirim dan menunggu persetujuan mentor/admin.');
    }

    public function updateStatus(Request $request, Permission $permission): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['approved', 'rejected'])],
        ]);

        if ($permission->status !== 'pending') {
            return redirect()
                ->route('admin.permissions.index')
                ->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $permission->update([
            'status' => $validated['status'],
        ]);

        $message = $validated['status'] === 'approved'
            ? 'Pengajuan izin berhasil disetujui.'
            : 'Pengajuan izin berhasil ditolak.';

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', $message);
    }
}
