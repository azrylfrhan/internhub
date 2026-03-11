<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LogbookController extends Controller
{
    /**
     * Cek apakah user boleh menambahkan logbook baru.
     */
    private function getLogbookCreateBlockReason($user): ?string
    {
        $today = Carbon::today('Asia/Makassar')->startOfDay();

        if ($user->role === 'alumni') {
            return 'Akun kamu berstatus nonaktif, sehingga tidak dapat menambahkan logbook baru.';
        }

        if (!empty($user->tanggal_selesai) && Carbon::parse($user->tanggal_selesai)->startOfDay()->lt($today)) {
            return 'Masa magang kamu sudah selesai, sehingga penambahan logbook baru dinonaktifkan.';
        }

        return null;
    }

    /**
     * Get logbook data for current user, optionally filtered by date
     */
    public function getData(Request $request)
    {
        try {
            $user = Auth::user();
            $query = Logbook::where('user_id', $user->id);
            $perPage = 10;

            // Filter by date if provided
            if ($request->has('tanggal') && $request->tanggal) {
                $query->whereDate('tanggal', $request->tanggal);
            }

            $paginator = $query->orderBy('tanggal', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            $rows = $paginator->getCollection()->map(function ($row) {
                return [
                    'id' => $row->id,
                    'tanggal' => $row->tanggal,
                    'aktivitas' => $row->aktivitas,
                    'deskripsi' => $row->deskripsi,
                    'jam_mulai' => $row->jam_mulai,
                    'jam_selesai' => $row->jam_selesai,
                    'created_at' => $row->created_at->format('d M Y H:i'),
                ];
            })->values();

            return response()->json([
                'success' => true,
                'rows' => $rows,
                'pagination' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                    'from' => $paginator->firstItem(),
                    'to' => $paginator->lastItem(),
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching logbook data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data logbook: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get logbook statistics for current user
     */
    public function getStats()
    {
        try {
            $user = Auth::user();
            $now = Carbon::now();
            $weekStart = $now->copy()->startOfWeek()->toDateString();
            $weekEnd = $now->copy()->endOfWeek()->toDateString();

            $total = Logbook::where('user_id', $user->id)->count();
            $month = Logbook::where('user_id', $user->id)
                ->whereYear('tanggal', $now->year)
                ->whereMonth('tanggal', $now->month)
                ->count();
            $week = Logbook::where('user_id', $user->id)
                ->whereBetween('tanggal', [$weekStart, $weekEnd])
                ->count();

            return response()->json([
                'success' => true,
                'total' => $total,
                'month' => $month,
                'week' => $week,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching logbook stats: ' . $e->getMessage());
            return response()->json([
                'success' => true,
                'total' => 0,
                'month' => 0,
                'week' => 0,
            ]);
        }
    }

    /**
     * Store new logbook entry
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            $createBlockReason = $this->getLogbookCreateBlockReason($user);
            if ($createBlockReason) {
                return response()->json([
                    'success' => false,
                    'message' => $createBlockReason,
                ], 403);
            }

            $request->validate([
                'tanggal' => 'required|date',
                'aktivitas' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'jam_mulai' => 'nullable|date_format:H:i',
                'jam_selesai' => 'nullable|date_format:H:i',
            ]);

            $logbook = Logbook::create([
                'user_id' => $user->id,
                'tanggal' => $request->tanggal,
                'aktivitas' => $request->aktivitas,
                'deskripsi' => $request->deskripsi,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Logbook berhasil disimpan',
                'logbook' => $logbook,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all()),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error saving logbook: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan logbook: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete logbook entry
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'tanggal' => 'required|date',
                'aktivitas' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'jam_mulai' => 'nullable|date_format:H:i',
                'jam_selesai' => 'nullable|date_format:H:i',
            ]);

            $logbook = Logbook::find($id);

            if (!$logbook || $logbook->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Logbook tidak ditemukan atau Anda tidak memiliki akses',
                ], 403);
            }

            $logbook->update([
                'tanggal' => $request->tanggal,
                'aktivitas' => $request->aktivitas,
                'deskripsi' => $request->deskripsi,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Logbook berhasil diperbarui',
                'logbook' => $logbook,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all()),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error updating logbook: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui logbook: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete logbook entry
     */
    public function destroy($id)
    {
        $logbook = Logbook::find($id);

        if (!$logbook || $logbook->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Logbook tidak ditemukan atau Anda tidak memiliki akses',
            ], 403);
        }

        $logbook->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logbook berhasil dihapus',
        ]);
    }
}
