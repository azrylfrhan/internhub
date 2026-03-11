<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public function index(Request $request): View
    {
        $baseQuery = Permission::query()->with('user');

        if ($request->filled('status')) {
            $baseQuery->where('status', $request->string('status')->value());
        }

        if ($request->filled('permission_type')) {
            $baseQuery->where('permission_type', $request->string('permission_type')->value());
        }

        if ($request->filled('q')) {
            $keyword = trim((string) $request->string('q'));
            $baseQuery->whereHas('user', function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%");
            });
        }

        $permissions = (clone $baseQuery)
            ->orderByRaw("case when status = 'pending' then 0 else 1 end")
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $summary = [
            'total' => Permission::count(),
            'pending' => Permission::where('status', 'pending')->count(),
            'approved' => Permission::where('status', 'approved')->count(),
            'rejected' => Permission::where('status', 'rejected')->count(),
        ];

        return view('admin.permissions.index', compact('permissions', 'summary'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'permission_type' => ['required', Rule::in(['sakit', 'lainnya'])],
            'reason' => ['nullable', 'string', 'max:1500', 'required_if:permission_type,lainnya'],
            'medical_document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:3072', 'required_if:permission_type,sakit'],
        ], [
            'permission_type.required' => 'Pilih alasan tidak masuk terlebih dahulu.',
            'reason.required_if' => 'Tuliskan alasan izin pada kolom keterangan.',
            'medical_document.required_if' => 'Dokumen izin sakit wajib diunggah.',
            'medical_document.mimes' => 'Dokumen izin sakit harus berupa PDF, JPG, JPEG, atau PNG.',
            'medical_document.max' => 'Ukuran dokumen izin sakit maksimal 3 MB.',
        ]);

        $today = Carbon::today('Asia/Makassar')->toDateString();
        $attachmentPath = null;

        if ($request->hasFile('medical_document')) {
            $attachmentPath = $request->file('medical_document')->store('permission-documents', 'public');
        }

        $reason = $validated['permission_type'] === 'sakit'
            ? 'Sakit'
            : trim((string) $validated['reason']);

        Permission::create([
            'user_id' => Auth::id(),
            'start_date' => $today,
            'end_date' => $today,
            'permission_type' => $validated['permission_type'],
            'reason' => $reason,
            'attachment_path' => $attachmentPath,
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
