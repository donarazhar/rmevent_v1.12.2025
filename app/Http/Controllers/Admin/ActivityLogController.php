<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with(['user', 'subject'])
            ->orderBy('created_at', 'desc');

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by subject type
        if ($request->filled('subject_type')) {
            $query->where('subject_type', $request->subject_type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Quick filters
        if ($request->filled('filter')) {
            switch ($request->filter) {
                case 'today':
                    $query->today();
                    break;
                case 'week':
                    $query->thisWeek();
                    break;
                case 'month':
                    $query->thisMonth();
                    break;
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('action', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(50);

        // Get filter data
        $users = User::select('id', 'name')->orderBy('name')->get();
        $actions = ActivityLog::select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');
        $subjectTypes = ActivityLog::select('subject_type')
            ->distinct()
            ->whereNotNull('subject_type')
            ->orderBy('subject_type')
            ->pluck('subject_type');

        return view('admin.activity-logs.index', compact(
            'logs',
            'users',
            'actions',
            'subjectTypes'
        ));
    }

    /**
     * Display the specified activity log
     */
    public function show(ActivityLog $activityLog)
    {
        $activityLog->load(['user', 'subject']);

        return view('admin.activity-logs.show', compact('activityLog'));
    }

    /**
     * Remove the specified activity log
     */
    public function destroy(ActivityLog $activityLog)
    {
        try {
            $activityLog->delete();

            return redirect()
                ->route('admin.activity-logs.index')
                ->with('success', 'Log aktivitas berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete activity logs
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:activity_logs,id'
        ]);

        try {
            ActivityLog::whereIn('id', $request->ids)->delete();

            return redirect()
                ->back()
                ->with('success', count($request->ids) . ' log aktivitas berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Clear old activity logs
     */
    public function clearOld(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:365'
        ]);

        try {
            $date = now()->subDays($request->days);
            $count = ActivityLog::where('created_at', '<', $date)->delete();

            return redirect()
                ->back()
                ->with('success', $count . ' log aktivitas yang lebih dari ' . $request->days . ' hari berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Export activity logs
     */
    public function export(Request $request)
    {
        $query = ActivityLog::with(['user', 'subject'])
            ->orderBy('created_at', 'desc');

        // Apply same filters as index
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('subject_type')) {
            $query->where('subject_type', $request->subject_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->limit(1000)->get();

        $filename = 'activity_logs_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, [
                'ID',
                'Tanggal/Waktu',
                'User',
                'Aksi',
                'Deskripsi',
                'Subject Type',
                'Subject ID',
                'IP Address',
                'User Agent'
            ]);

            // Data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user?->name ?? 'System',
                    $log->action,
                    $log->description,
                    $log->subject_type,
                    $log->subject_id,
                    $log->ip_address,
                    $log->user_agent
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get statistics
     */
    public function statistics()
    {
        $stats = [
            'total' => ActivityLog::count(),
            'today' => ActivityLog::today()->count(),
            'this_week' => ActivityLog::thisWeek()->count(),
            'this_month' => ActivityLog::thisMonth()->count(),

            'by_action' => ActivityLog::select('action', DB::raw('count(*) as total'))
                ->groupBy('action')
                ->orderBy('total', 'desc')
                ->limit(10)
                ->get(),

            'by_user' => ActivityLog::with('user')
                ->select('user_id', DB::raw('count(*) as total'))
                ->whereNotNull('user_id')
                ->groupBy('user_id')
                ->orderBy('total', 'desc')
                ->limit(10)
                ->get(),

            'recent' => ActivityLog::with(['user', 'subject'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
        ];

        return view('admin.activity-logs.statistics', compact('stats'));
    }
}
