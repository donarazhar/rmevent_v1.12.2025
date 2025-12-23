<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\ExecutiveSummary;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExecutiveSummaryController extends Controller
{
    /**
     * Display a listing of executive summaries.
     */
    public function index(Request $request)
    {
        $query = ExecutiveSummary::with(['event', 'createdBy', 'reviewedBy', 'approvedBy'])
            ->latest();

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

        // Filter by period
        if ($request->filled('period')) {
            switch ($request->period) {
                case 'this_month':
                    $query->thisMonth();
                    break;
                case 'this_quarter':
                    $query->thisQuarter();
                    break;
                case 'this_year':
                    $query->thisYear();
                    break;
            }
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->periodBetween($request->start_date, $request->end_date);
        }

        $summaries = $query->paginate(15);

        // Statistics for dashboard
        $statistics = [
            'total' => ExecutiveSummary::count(),
            'draft' => ExecutiveSummary::draft()->count(),
            'under_review' => ExecutiveSummary::underReview()->count(),
            'approved' => ExecutiveSummary::approved()->count(),
            'published' => ExecutiveSummary::published()->count(),
        ];

        return view('admin.reports.executive-summaries.index', compact('summaries', 'statistics'));
    }

    /**
     * Show the form for creating a new executive summary.
     */
    public function create()
    {
        $events = Event::where('status', 'published')
            ->orderBy('start_datetime', 'desc')
            ->get();

        $summaryCode = ExecutiveSummary::generateSummaryCode();

        return view('admin.reports.executive-summaries.create', compact('events', 'summaryCode'));
    }

    /**
     * Store a newly created executive summary in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'nullable|exists:events,id',
            'title' => 'required|string|max:255',
            'summary_type' => 'required|in:monthly,quarterly,event,annual',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'report_date' => 'required|date',

            // Content
            'executive_overview' => 'nullable|string',
            'key_highlights' => 'nullable|string',
            'achievements' => 'nullable|string',
            'challenges' => 'nullable|string',
            'recommendations' => 'nullable|string',

            // Financial
            'total_income' => 'nullable|numeric|min:0',
            'total_expenses' => 'nullable|numeric|min:0',

            // Events
            'events_conducted' => 'nullable|integer|min:0',
            'total_participants' => 'nullable|integer|min:0',
            'satisfaction_score' => 'nullable|numeric|min:0|max:5',

            // Supporting Documents
            'supporting_documents.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        DB::beginTransaction();
        try {
            // Create summary
            $summary = ExecutiveSummary::create([
                'event_id' => $validated['event_id'] ?? null,
                'title' => $validated['title'],
                'summary_type' => $validated['summary_type'],
                'period_start' => $validated['period_start'],
                'period_end' => $validated['period_end'],
                'report_date' => $validated['report_date'],
                'executive_overview' => $validated['executive_overview'] ?? null,
                'key_highlights' => $validated['key_highlights'] ?? null,
                'achievements' => $validated['achievements'] ?? null,
                'challenges' => $validated['challenges'] ?? null,
                'recommendations' => $validated['recommendations'] ?? null,
                'total_income' => $validated['total_income'] ?? null,
                'total_expenses' => $validated['total_expenses'] ?? null,
                'events_conducted' => $validated['events_conducted'] ?? null,
                'total_participants' => $validated['total_participants'] ?? null,
                'satisfaction_score' => $validated['satisfaction_score'] ?? null,
                'status' => ExecutiveSummary::STATUS_DRAFT,
                'created_by' => Auth::id(),
            ]);

            // Handle supporting documents upload
            if ($request->hasFile('supporting_documents')) {
                $documents = [];
                foreach ($request->file('supporting_documents') as $file) {
                    $path = $file->store('executive-summaries/documents', 'public');
                    $documents[] = $path;
                }
                $summary->update(['supporting_documents' => $documents]);
            }

            DB::commit();

            return redirect()
                ->route('admin.reports.executive-summaries.edit', $summary)
                ->with('success', 'Executive Summary berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified executive summary.
     */
    public function show(ExecutiveSummary $executiveSummary)
    {
        $executiveSummary->load(['event', 'createdBy', 'reviewedBy', 'approvedBy']);

        return view('admin.reports.executive-summaries.show', compact('executiveSummary'));
    }

    /**
     * Show the form for editing the specified executive summary.
     */
    public function edit(ExecutiveSummary $executiveSummary)
    {
        // Check if user can edit
        if (!$executiveSummary->canBeEditedBy(Auth::user())) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengedit summary ini.');
        }

        $events = Event::where('status', 'published')
            ->orderBy('start_datetime', 'desc')
            ->get();

        return view('admin.reports.executive-summaries.edit', compact('executiveSummary', 'events'));
    }

    /**
     * Update the specified executive summary in storage.
     */
    public function update(Request $request, ExecutiveSummary $executiveSummary)
    {
        // Check if user can edit
        if (!$executiveSummary->canBeEditedBy(Auth::user())) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengedit summary ini.');
        }

        $validated = $request->validate([
            'event_id' => 'nullable|exists:events,id',
            'title' => 'required|string|max:255',
            'summary_type' => 'required|in:monthly,quarterly,event,annual',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'report_date' => 'required|date',

            // Content
            'executive_overview' => 'nullable|string',
            'key_highlights' => 'nullable|string',
            'achievements' => 'nullable|string',
            'challenges' => 'nullable|string',
            'recommendations' => 'nullable|string',

            // Financial
            'total_income' => 'nullable|numeric|min:0',
            'total_expenses' => 'nullable|numeric|min:0',

            // Events
            'events_conducted' => 'nullable|integer|min:0',
            'total_participants' => 'nullable|integer|min:0',
            'satisfaction_score' => 'nullable|numeric|min:0|max:5',

            // Supporting Documents
            'supporting_documents.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        DB::beginTransaction();
        try {
            $executiveSummary->update([
                'event_id' => $validated['event_id'] ?? null,
                'title' => $validated['title'],
                'summary_type' => $validated['summary_type'],
                'period_start' => $validated['period_start'],
                'period_end' => $validated['period_end'],
                'report_date' => $validated['report_date'],
                'executive_overview' => $validated['executive_overview'] ?? null,
                'key_highlights' => $validated['key_highlights'] ?? null,
                'achievements' => $validated['achievements'] ?? null,
                'challenges' => $validated['challenges'] ?? null,
                'recommendations' => $validated['recommendations'] ?? null,
                'total_income' => $validated['total_income'] ?? null,
                'total_expenses' => $validated['total_expenses'] ?? null,
                'events_conducted' => $validated['events_conducted'] ?? null,
                'total_participants' => $validated['total_participants'] ?? null,
                'satisfaction_score' => $validated['satisfaction_score'] ?? null,
            ]);

            // Handle new supporting documents upload
            if ($request->hasFile('supporting_documents')) {
                $existingDocuments = $executiveSummary->supporting_documents ?? [];

                foreach ($request->file('supporting_documents') as $file) {
                    $path = $file->store('executive-summaries/documents', 'public');
                    $existingDocuments[] = $path;
                }

                $executiveSummary->update(['supporting_documents' => $existingDocuments]);
            }

            DB::commit();

            return redirect()
                ->route('admin.reports.executive-summaries.show', $executiveSummary)
                ->with('success', 'Executive Summary berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified executive summary from storage.
     */
    public function destroy(ExecutiveSummary $executiveSummary)
    {
        try {
            $executiveSummary->delete();

            return redirect()
                ->route('admin.reports.executive-summaries.index')
                ->with('success', 'Executive Summary berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Submit summary for review
     */
    public function submitForReview(ExecutiveSummary $executiveSummary)
    {
        if ($executiveSummary->submitForReview()) {
            return back()->with('success', 'Executive Summary berhasil diajukan untuk ditinjau.');
        }

        return back()->with('error', 'Tidak dapat mengajukan summary untuk ditinjau.');
    }

    /**
     * Review summary
     */
    public function review(ExecutiveSummary $executiveSummary)
    {
        if (!$executiveSummary->canBeReviewedBy(Auth::user())) {
            return back()->with('error', 'Anda tidak memiliki akses untuk meninjau summary ini.');
        }

        if ($executiveSummary->review(Auth::id())) {
            return back()->with('success', 'Executive Summary berhasil ditinjau.');
        }

        return back()->with('error', 'Tidak dapat meninjau summary.');
    }

    /**
     * Approve summary
     */
    public function approve(ExecutiveSummary $executiveSummary)
    {
        if (!$executiveSummary->canBeApprovedBy(Auth::user())) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menyetujui summary ini.');
        }

        if ($executiveSummary->approve(Auth::id())) {
            return back()->with('success', 'Executive Summary berhasil disetujui.');
        }

        return back()->with('error', 'Tidak dapat menyetujui summary.');
    }

    /**
     * Publish summary
     */
    public function publish(ExecutiveSummary $executiveSummary)
    {
        if (!$executiveSummary->canBeApprovedBy(Auth::user())) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mempublikasikan summary ini.');
        }

        if ($executiveSummary->publish()) {
            return back()->with('success', 'Executive Summary berhasil dipublikasikan.');
        }

        return back()->with('error', 'Tidak dapat mempublikasikan summary.');
    }

    /**
     * Reject summary
     */
    public function reject(Request $request, ExecutiveSummary $executiveSummary)
    {
        if (!$executiveSummary->canBeReviewedBy(Auth::user())) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menolak summary ini.');
        }

        if ($executiveSummary->rejectToReview()) {
            return back()->with('success', 'Executive Summary dikembalikan ke draft.');
        }

        return back()->with('error', 'Tidak dapat menolak summary.');
    }

    /**
     * Delete supporting document
     */
    public function deleteDocument(ExecutiveSummary $executiveSummary, Request $request)
    {
        $documentPath = $request->input('document_path');

        if ($documentPath) {
            $executiveSummary->removeSupportingDocument($documentPath);
            return back()->with('success', 'Dokumen berhasil dihapus.');
        }

        return back()->with('error', 'Dokumen tidak ditemukan.');
    }

    /**
     * Generate PDF document
     */
    public function generatePdf(ExecutiveSummary $executiveSummary)
    {
        try {
            // TODO: Implement PDF generation logic
            // This would use a library like DomPDF or Snappy

            return back()->with('info', 'Fitur generate PDF sedang dalam pengembangan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Duplicate summary
     */
    public function duplicate(ExecutiveSummary $executiveSummary)
    {
        try {
            $newSummary = $executiveSummary->replicate();
            $newSummary->summary_code = ExecutiveSummary::generateSummaryCode();
            $newSummary->title = $executiveSummary->title . ' (Copy)';
            $newSummary->status = ExecutiveSummary::STATUS_DRAFT;
            $newSummary->created_by = Auth::id();
            $newSummary->reviewed_by = null;
            $newSummary->reviewed_at = null;
            $newSummary->approved_by = null;
            $newSummary->approved_at = null;
            $newSummary->document_file = null;
            $newSummary->save();

            return redirect()
                ->route('admin.reports.executive-summaries.edit', $newSummary)
                ->with('success', 'Executive Summary berhasil diduplikasi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
