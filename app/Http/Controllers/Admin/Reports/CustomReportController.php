<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\CustomReport;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomReportController extends Controller
{
    /**
     * Display a listing of custom reports
     */
    public function index(Request $request)
    {
        $query = CustomReport::with(['event:id,title', 'createdBy:id,name'])
            ->visibleTo(Auth::id());

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Filter by event
        if ($request->filled('event_id')) {
            $query->byEvent($request->event_id);
        }

        // Filter by visibility
        if ($request->filled('visibility')) {
            $query->byVisibility($request->visibility);
        }

        // Filter by period
        if ($request->filled('period_start') && $request->filled('period_end')) {
            $query->periodBetween($request->period_start, $request->period_end);
        }

        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortField, $sortOrder);

        $reports = $query->paginate(15)->withQueryString();

        // Get filter options
        $events = Event::select('id', 'title')->where('status', 'published')->get();
        $reportTypes = [
            'financial' => 'Keuangan',
            'performance' => 'Kinerja',
            'event' => 'Event',
            'registration' => 'Pendaftaran',
            'custom' => 'Kustom'
        ];
        $statuses = [
            'draft' => 'Draft',
            'saved' => 'Tersimpan',
            'published' => 'Dipublikasikan'
        ];

        return view('admin.reports.custom.index', compact(
            'reports',
            'events',
            'reportTypes',
            'statuses'
        ));
    }

    /**
     * Show the form for creating a new report
     */
    public function create()
    {
        $events = Event::select('id', 'title')->where('status', 'published')->get();

        $reportTypes = [
            'financial' => 'Keuangan',
            'performance' => 'Kinerja',
            'event' => 'Event',
            'registration' => 'Pendaftaran',
            'custom' => 'Kustom'
        ];

        $frequencies = [
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
            'quarterly' => 'Triwulanan'
        ];

        $visibilities = [
            'private' => 'Pribadi',
            'team' => 'Tim',
            'public' => 'Publik'
        ];

        // Available data sources
        $dataSources = [
            'events' => 'Events',
            'registrations' => 'Registrations',
            'payments' => 'Payments',
            'feedback' => 'Feedback',
            'users' => 'Users',
            'tasks' => 'Tasks',
            'budgets' => 'Budgets',
            'expenses' => 'Expenses'
        ];

        // Available metrics
        $metrics = [
            'count' => 'Jumlah (Count)',
            'sum' => 'Total (Sum)',
            'avg' => 'Rata-rata (Average)',
            'max' => 'Maksimum',
            'min' => 'Minimum'
        ];

        // Chart types
        $chartTypes = [
            'line' => 'Line Chart',
            'bar' => 'Bar Chart',
            'pie' => 'Pie Chart',
            'doughnut' => 'Doughnut Chart',
            'area' => 'Area Chart'
        ];

        return view('admin.reports.custom.create', compact(
            'events',
            'reportTypes',
            'frequencies',
            'visibilities',
            'dataSources',
            'metrics',
            'chartTypes'
        ));
    }

    /**
     * Store a newly created report
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'report_type' => 'required|in:financial,performance,event,registration,custom',
            'event_id' => 'nullable|exists:events,id',
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|date|after_or_equal:period_start',
            'visibility' => 'required|in:private,team,public',
            'status' => 'required|in:draft,saved,published',

            // Configuration
            'data_sources' => 'nullable|array',
            'filters' => 'nullable|array',
            'metrics' => 'nullable|array',
            'dimensions' => 'nullable|array',
            'chart_config' => 'nullable|array',

            // Scheduling
            'is_scheduled' => 'nullable|boolean',
            'schedule_frequency' => 'nullable|in:daily,weekly,monthly,quarterly',
            'schedule_config' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            $validated['created_by'] = Auth::id();

            // Convert boolean
            $validated['is_scheduled'] = $request->boolean('is_scheduled');

            $report = CustomReport::create($validated);

            // Generate report if requested
            if ($request->boolean('generate_now') && $report->status === 'published') {
                $report->generate();
            }

            DB::commit();

            return redirect()
                ->route('admin.reports.custom.show', $report)
                ->with('success', 'Custom Report berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create custom report: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Gagal membuat custom report: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified report
     */
    public function show(CustomReport $customReport)
    {
        // Check permission
        if (!$customReport->canBeViewedBy(Auth::user())) {
            abort(403, 'Unauthorized access to this report.');
        }

        $customReport->load(['event:id,title', 'createdBy:id,name,email']);

        // Increment view count
        $customReport->incrementViewCount();

        // Generate if stale or empty
        if ($customReport->needsRegeneration()) {
            $customReport->generate();
            $customReport->refresh();
        }

        return view('admin.reports.custom.show', compact('customReport'));
    }

    /**
     * Show the form for editing the specified report
     */
    public function edit(CustomReport $customReport)
    {
        // Check permission
        if (!$customReport->canBeEditedBy(Auth::user())) {
            abort(403, 'Unauthorized to edit this report.');
        }

        $events = Event::select('id', 'title')->where('status', 'published')->get();

        $reportTypes = [
            'financial' => 'Keuangan',
            'performance' => 'Kinerja',
            'event' => 'Event',
            'registration' => 'Pendaftaran',
            'custom' => 'Kustom'
        ];

        $frequencies = [
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
            'quarterly' => 'Triwulanan'
        ];

        $visibilities = [
            'private' => 'Pribadi',
            'team' => 'Tim',
            'public' => 'Publik'
        ];

        $dataSources = [
            'events' => 'Events',
            'registrations' => 'Registrations',
            'payments' => 'Payments',
            'feedback' => 'Feedback',
            'users' => 'Users',
            'tasks' => 'Tasks',
            'budgets' => 'Budgets',
            'expenses' => 'Expenses'
        ];

        $metrics = [
            'count' => 'Jumlah (Count)',
            'sum' => 'Total (Sum)',
            'avg' => 'Rata-rata (Average)',
            'max' => 'Maksimum',
            'min' => 'Minimum'
        ];

        $chartTypes = [
            'line' => 'Line Chart',
            'bar' => 'Bar Chart',
            'pie' => 'Pie Chart',
            'doughnut' => 'Doughnut Chart',
            'area' => 'Area Chart'
        ];

        return view('admin.reports.custom.edit', compact(
            'customReport',
            'events',
            'reportTypes',
            'frequencies',
            'visibilities',
            'dataSources',
            'metrics',
            'chartTypes'
        ));
    }

    /**
     * Update the specified report
     */
    public function update(Request $request, CustomReport $customReport)
    {
        // Check permission
        if (!$customReport->canBeEditedBy(Auth::user())) {
            abort(403, 'Unauthorized to edit this report.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'report_type' => 'required|in:financial,performance,event,registration,custom',
            'event_id' => 'nullable|exists:events,id',
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|date|after_or_equal:period_start',
            'visibility' => 'required|in:private,team,public',
            'status' => 'required|in:draft,saved,published',

            'data_sources' => 'nullable|array',
            'filters' => 'nullable|array',
            'metrics' => 'nullable|array',
            'dimensions' => 'nullable|array',
            'chart_config' => 'nullable|array',

            'is_scheduled' => 'nullable|boolean',
            'schedule_frequency' => 'nullable|in:daily,weekly,monthly,quarterly',
            'schedule_config' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            $validated['is_scheduled'] = $request->boolean('is_scheduled');

            $customReport->update($validated);

            // Regenerate if configuration changed
            if ($request->boolean('regenerate')) {
                $customReport->generate();
            }

            DB::commit();

            return redirect()
                ->route('admin.reports.custom.show', $customReport)
                ->with('success', 'Custom Report berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update custom report: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Gagal mengupdate custom report: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified report
     */
    public function destroy(CustomReport $customReport)
    {
        // Check permission
        if (!$customReport->canBeDeletedBy(Auth::user())) {
            abort(403, 'Unauthorized to delete this report.');
        }

        try {
            $customReport->delete();

            return redirect()
                ->route('admin.reports.custom.index')
                ->with('success', 'Custom Report berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Failed to delete custom report: ' . $e->getMessage());

            return back()
                ->with('error', 'Gagal menghapus custom report: ' . $e->getMessage());
        }
    }

    /**
     * Generate report data
     */
    public function generate(CustomReport $customReport)
    {
        // Check permission
        if (!$customReport->canBeViewedBy(Auth::user())) {
            abort(403, 'Unauthorized access to this report.');
        }

        try {
            $success = $customReport->generate();

            if ($success) {
                return back()->with('success', 'Report berhasil di-generate!');
            }

            return back()->with('error', 'Gagal generate report.');
        } catch (\Exception $e) {
            Log::error('Failed to generate report: ' . $e->getMessage());
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Publish report
     */
    public function publish(CustomReport $customReport)
    {
        if (!$customReport->canBeEditedBy(Auth::user())) {
            abort(403);
        }

        try {
            $customReport->publish();
            return back()->with('success', 'Report berhasil dipublikasikan!');
        } catch (\Exception $e) {
            Log::error('Failed to publish report: ' . $e->getMessage());
            return back()->with('error', 'Gagal publish report.');
        }
    }

    /**
     * Unpublish report
     */
    public function unpublish(CustomReport $customReport)
    {
        if (!$customReport->canBeEditedBy(Auth::user())) {
            abort(403);
        }

        try {
            $customReport->unpublish();
            return back()->with('success', 'Report berhasil di-unpublish!');
        } catch (\Exception $e) {
            Log::error('Failed to unpublish report: ' . $e->getMessage());
            return back()->with('error', 'Gagal unpublish report.');
        }
    }

    /**
     * Duplicate report
     */
    public function duplicate(CustomReport $customReport)
    {
        if (!$customReport->canBeViewedBy(Auth::user())) {
            abort(403);
        }

        try {
            $newReport = $customReport->duplicate();

            return redirect()
                ->route('admin.reports.custom.edit', $newReport)
                ->with('success', 'Report berhasil diduplikasi!');
        } catch (\Exception $e) {
            Log::error('Failed to duplicate report: ' . $e->getMessage());
            return back()->with('error', 'Gagal duplikasi report.');
        }
    }

    /**
     * Export report
     */
    public function export(CustomReport $customReport, Request $request)
    {
        if (!$customReport->canBeViewedBy(Auth::user())) {
            abort(403);
        }

        $format = $request->get('format', 'pdf');

        try {
            // This would implement actual export logic
            $filePath = $customReport->export($format);

            // For now, just return success message
            return back()->with('success', "Export {$format} berhasil!");
        } catch (\Exception $e) {
            Log::error('Failed to export report: ' . $e->getMessage());
            return back()->with('error', 'Gagal export report.');
        }
    }

    /**
     * Share report with users
     */
    public function share(CustomReport $customReport, Request $request)
    {
        if (!$customReport->canBeEditedBy(Auth::user())) {
            abort(403);
        }

        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        try {
            $customReport->shareWithMultiple($validated['user_ids']);
            return back()->with('success', 'Report berhasil di-share!');
        } catch (\Exception $e) {
            Log::error('Failed to share report: ' . $e->getMessage());
            return back()->with('error', 'Gagal share report.');
        }
    }

    /**
     * Toggle schedule
     */
    public function toggleSchedule(CustomReport $customReport, Request $request)
    {
        if (!$customReport->canBeEditedBy(Auth::user())) {
            abort(403);
        }

        try {
            if ($request->boolean('enable')) {
                $customReport->enableSchedule(
                    $request->input('frequency', 'daily'),
                    $request->input('config', [])
                );
                $message = 'Schedule berhasil diaktifkan!';
            } else {
                $customReport->disableSchedule();
                $message = 'Schedule berhasil dinonaktifkan!';
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Failed to toggle schedule: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengubah schedule.');
        }
    }
}
