<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public function index(Request $request): View
    {
        $baseQuery = Permission::query()->with('user');

        if ($request->filled('permission_type')) {
            $baseQuery->where('permission_type', $request->string('permission_type')->value());
        }

        if ($request->filled('q')) {
            $keyword = trim((string) $request->string('q'));
            $baseQuery->whereHas('user', function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                    ->orWhere('username', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%");
            });
        }

        $permissions = (clone $baseQuery)
            ->orderByRaw("case when status = 'pending' then 0 else 1 end")
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $participants = User::query()
            ->whereIn('role', ['magang', 'alumni'])
            ->orderBy('name')
            ->get(['id', 'name', 'username', 'email', 'role']);

        $summary = [
            'total' => Permission::count(),
            'sakit' => Permission::where('permission_type', 'sakit')->count(),
            'lainnya' => Permission::where('permission_type', 'lainnya')->count(),
        ];

        return view('admin.permissions.index', compact('permissions', 'summary', 'participants'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'permission_type' => ['required', Rule::in(['sakit', 'lainnya'])],
            'reason' => ['nullable', 'string', 'max:1500', 'required_if:permission_type,lainnya'],
            'medical_document' => ['nullable', 'file', 'mimes:pdf', 'max:3072', 'required_if:permission_type,sakit'],
        ], [
            'user_id.required' => 'Pilih peserta magang terlebih dahulu.',
            'user_id.exists' => 'Peserta magang yang dipilih tidak valid.',
            'start_date.required' => 'Tanggal mulai izin wajib diisi.',
            'end_date.required' => 'Tanggal akhir izin wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal akhir izin harus sama atau setelah tanggal mulai.',
            'permission_type.required' => 'Pilih jenis izin terlebih dahulu.',
            'reason.required_if' => 'Alasan izin wajib diisi untuk jenis alasan lain.',
            'medical_document.required_if' => 'Dokumen izin sakit wajib diunggah dalam format PDF.',
            'medical_document.mimes' => 'Dokumen izin sakit harus berupa file PDF.',
            'medical_document.max' => 'Ukuran dokumen izin sakit maksimal 3 MB.',
        ]);

        $participant = User::query()
            ->whereIn('role', ['magang', 'alumni'])
            ->find($validated['user_id']);

        if (!$participant) {
            return redirect()
                ->route('admin.permissions.index')
                ->with('error', 'Hanya akun peserta magang yang dapat diinputkan izin.');
        }

        $attachmentPath = null;
        if ($request->hasFile('medical_document')) {
            $attachmentPath = $request->file('medical_document')->store('permission-documents', 'public');
        }

        $reason = $validated['permission_type'] === 'sakit'
            ? 'Sakit'
            : trim((string) $validated['reason']);

        Permission::create([
            'user_id' => $participant->id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'permission_type' => $validated['permission_type'],
            'reason' => $reason,
            'attachment_path' => $attachmentPath,
            'status' => 'approved',
        ]);

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Data izin berhasil diinput dan langsung disetujui.');
    }
}
