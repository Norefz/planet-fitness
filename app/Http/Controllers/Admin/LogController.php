<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\SuperAdmin;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class LogController extends Controller
{
    public function index(Request $request): View|StreamedResponse
    {
        return $this->showLogs($request, false);
    }

    /**
     * Audit trail tindakan yang dilakukan oleh akun admin.
     * Route ini dilindungi oleh middleware admin.head.
     */
    public function adminIndex(Request $request): View|StreamedResponse
    {
        return $this->showLogs($request, true);
    }

    private function showLogs(Request $request, bool $isAdminLog): View|StreamedResponse
    {
        // Log member/mentor dibuat tanpa admin_id; tindakan dari panel admin
        // selalu menyimpan SuperAdmin yang melakukan tindakan tersebut.
        $query = AuditLog::with('admin')
            ->when($isAdminLog, fn ($logs) => $logs->whereNotNull('admin_id'), fn ($logs) => $logs->whereNull('admin_id'))
            ->latest('performed_at');

        // ── Filter: admin ────────────────────────────────────────────
        if ($request->filled('admin_id')) {
            $query->where('admin_id', $request->query('admin_id'));
        }

        // ── Filter: jenis aksi (create/update/delete/verify/dll) ─────
        if ($request->filled('action_type')) {
            $type = $request->query('action_type');
            $query->where('action', 'like', "%{$type}%");
        }

        // ── Filter: tabel target ─────────────────────────────────────
        if ($request->filled('target_table')) {
            $query->where('target_table', $request->query('target_table'));
        }

        // ── Filter: rentang waktu ─────────────────────────────────────
        $period = $request->query('period', 'today');
        if ($period === 'today') {
            $query->whereDate('performed_at', today());
        } elseif ($period === '7d') {
            $query->where('performed_at', '>=', now()->subDays(7));
        } elseif ($period === '30d') {
            $query->where('performed_at', '>=', now()->subDays(30));
        } elseif ($period === 'custom' && $request->filled('date')) {
            $query->whereDate('performed_at', $request->query('date'));
        }

        // ── Pencarian bebas: aksi, detail, nama admin ────────────────
        if ($request->filled('q')) {
            $q = $request->query('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('action', 'like', "%{$q}%")
                    ->orWhere('details', 'like', "%{$q}%")
                    ->orWhere('target_table', 'like', "%{$q}%")
                    ->orWhereHas('admin', fn ($a) => $a->where('full_name', 'like', "%{$q}%"));
            });
        }

        // ── Export CSV (menghormati filter yang sedang aktif) ────────
        if ($request->query('export') === 'csv') {
            return $this->exportCsv((clone $query));
        }

        $logs = $query->paginate(10)->withQueryString();

        // ── Stat cards ────────────────────────────────────────────────
        $statsQuery = AuditLog::query()
            ->when($isAdminLog, fn ($logs) => $logs->whereNotNull('admin_id'), fn ($logs) => $logs->whereNull('admin_id'));

        $totalToday = (clone $statsQuery)->whereDate('performed_at', today())->count();

        $activeAdminsToday = (clone $statsQuery)->whereDate('performed_at', today())
            ->whereNotNull('admin_id')
            ->distinct('admin_id')
            ->count('admin_id');

        $updateToday = (clone $statsQuery)->whereDate('performed_at', today())
            ->where('action', 'like', '%update%')
            ->count();
        $updatePct = $totalToday > 0 ? round(($updateToday / $totalToday) * 100) : 0;

        $deleteToday = (clone $statsQuery)->whereDate('performed_at', today())
            ->where(function ($sub) {
                $sub->where('action', 'like', '%delete%')
                    ->orWhere('action', 'like', '%deleted%');
            })
            ->count();

        $stats = [
            'total_today'   => $totalToday,
            'active_admins' => $activeAdminsToday,
            'update_today'  => $updateToday,
            'update_pct'    => $updatePct,
            'delete_today'  => $deleteToday,
        ];

        // ── Dropdown filter: daftar admin ────────────────────────────
        $admins = $isAdminLog ? SuperAdmin::orderBy('full_name')->get() : collect();

        // ── Dropdown filter: daftar tabel target ─────────────────────
        $targetTables = (clone $statsQuery)->whereNotNull('target_table')
            ->distinct()
            ->orderBy('target_table')
            ->pluck('target_table');

        $logTitle = $isAdminLog ? 'Log Admin' : 'Log Aktivitas';
        $logSubtitle = $isAdminLog ? 'Riwayat tindakan akun admin' : 'Aktivitas Member & Mentor';
        $logRoute = $isAdminLog ? 'admin.admin-logs' : 'admin.logs';

        return view('admin.logs.index', compact(
            'logs',
            'stats',
            'admins',
            'targetTables',
            'period',
            'isAdminLog',
            'logTitle',
            'logSubtitle',
            'logRoute',
        ));
    }

    /**
     * Unduh log aktivitas (sesuai filter aktif) sebagai file CSV.
     */
    private function exportCsv($query)
    {
        $filename = 'log-aktivitas-' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Waktu', 'Admin', 'Aksi', 'Tabel Target', 'ID Target', 'Detail', 'IP Address']);

            $query->chunk(200, function ($chunk) use ($handle) {
                foreach ($chunk as $log) {
                    fputcsv($handle, [
                        optional($log->performed_at)->format('Y-m-d H:i:s'),
                        $log->admin?->full_name ?? 'Sistem',
                        $log->action,
                        $log->target_table,
                        $log->target_id,
                        $log->details,
                        $log->ip_address,
                    ]);
                }
            });

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
