<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\FinalEventReport;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class FinalEventReportController extends Controller
{
    /**
     * Display a listing of final event reports.
     */
    public function index(Request $request)
    {
        $query = FinalEventReport::with(['event', 'createdBy', 'reviewedBy', 'approvedBy']);

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Filter by event
        if ($request->filled('event_id')) {
            $query->byEvent($request->event_id);
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->reportDateBetween($request->start_date, $request->end_date);
        }

        // Filter by created by
        if ($request->filled('created_by')) {
            $query->createdBy($request->created_by);
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $reports = $query->paginate($request->get('per_page', 15));

        // Get filter options
        $events = Event::select('id', 'title')->orderBy('title')->get();
        $creators = User::select('id', 'name')->orderBy('name')->get();
        $statuses = [
            'draft' => 'Draft',
            'under_review' => 'Sedang Ditinjau',
            'approved' => 'Disetujui',
            'published' => 'Dipublikasikan',
        ];

        return view('admin.reports.final-event-reports.index', compact('reports', 'events', 'creators', 'statuses'));
    }

    /**
     * Show the form for creating a new final event report.
     */
    public function create(Request $request)
    {
        // Get events that don't have reports yet or are closed
        $events = Event::whereDoesntHave('finalEventReport')
            ->orWhereHas('finalEventReport', function ($query) {
                $query->where('status', '!=', 'published');
            })
            ->where('status', 'closed')
            ->orderBy('end_datetime', 'desc')
            ->get();

        // If event_id is provided in query string, select it
        $selectedEventId = $request->get('event_id');

        return view('admin.reports.final-event-reports.create', compact('events', 'selectedEventId'));
    }

    /**
     * Store a newly created final event report.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'title' => 'required|string|max:255',
            'report_date' => 'required|date',

            // Sections
            'executive_summary' => 'nullable|string',
            'event_overview' => 'nullable|string',
            'objectives_achievement' => 'nullable|string',
            'implementation_process' => 'nullable|string',
            'participant_analysis' => 'nullable|string',
            'financial_report' => 'nullable|string',
            'challenges_solutions' => 'nullable|string',
            'lessons_learned' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'conclusion' => 'nullable|string',

            // Statistics
            'total_participants' => 'nullable|integer|min:0',
            'registered_participants' => 'nullable|integer|min:0',
            'attended_participants' => 'nullable|integer|min:0',

            // Financial
            'total_budget' => 'nullable|numeric|min:0',
            'total_income' => 'nullable|numeric|min:0',
            'total_expenses' => 'nullable|numeric|min:0',

            // Ratings
            'overall_satisfaction' => 'nullable|numeric|min:0|max:5',
            'content_rating' => 'nullable|numeric|min:0|max:5',
            'organization_rating' => 'nullable|numeric|min:0|max:5',
            'venue_rating' => 'nullable|numeric|min:0|max:5',

            // Team
            'committee_members' => 'nullable|integer|min:0',
            'team_performance_score' => 'nullable|numeric|min:0|max:5',

            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $validated['created_by'] = Auth::id();
            $validated['status'] = 'draft';

            $report = FinalEventReport::create($validated);

            DB::commit();

            return redirect()
                ->route('admin.reports.final-event-reports.show', $report)
                ->with('success', 'Laporan akhir acara berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat laporan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified final event report.
     */
    public function show(FinalEventReport $finalEventReport)
    {
        $finalEventReport->load(['event', 'createdBy', 'reviewedBy', 'approvedBy']);

        return view('admin.reports.final-event-reports.show', compact('finalEventReport'));
    }

    /**
     * Show the form for editing the specified final event report.
     */
    public function edit(FinalEventReport $finalEventReport)
    {
        // Check permission
        if (!$finalEventReport->canBeEditedBy(Auth::user())) {
            return redirect()
                ->route('admin.reports.final-event-reports.show', $finalEventReport)
                ->with('error', 'Anda tidak memiliki izin untuk mengedit laporan ini.');
        }

        $events = Event::orderBy('title')->get();

        return view('admin.reports.final-event-reports.edit', compact('finalEventReport', 'events'));
    }

    /**
     * Update the specified final event report.
     */
    public function update(Request $request, FinalEventReport $finalEventReport)
    {
        // Check permission
        if (!$finalEventReport->canBeEditedBy(Auth::user())) {
            return back()->with('error', 'Anda tidak memiliki izin untuk mengedit laporan ini.');
        }

        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'title' => 'required|string|max:255',
            'report_date' => 'required|date',

            // Sections
            'executive_summary' => 'nullable|string',
            'event_overview' => 'nullable|string',
            'objectives_achievement' => 'nullable|string',
            'implementation_process' => 'nullable|string',
            'participant_analysis' => 'nullable|string',
            'financial_report' => 'nullable|string',
            'challenges_solutions' => 'nullable|string',
            'lessons_learned' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'conclusion' => 'nullable|string',

            // Statistics
            'total_participants' => 'nullable|integer|min:0',
            'registered_participants' => 'nullable|integer|min:0',
            'attended_participants' => 'nullable|integer|min:0',

            // Financial
            'total_budget' => 'nullable|numeric|min:0',
            'total_income' => 'nullable|numeric|min:0',
            'total_expenses' => 'nullable|numeric|min:0',

            // Ratings
            'overall_satisfaction' => 'nullable|numeric|min:0|max:5',
            'content_rating' => 'nullable|numeric|min:0|max:5',
            'organization_rating' => 'nullable|numeric|min:0|max:5',
            'venue_rating' => 'nullable|numeric|min:0|max:5',

            // Team
            'committee_members' => 'nullable|integer|min:0',
            'team_performance_score' => 'nullable|numeric|min:0|max:5',

            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $finalEventReport->update($validated);

            DB::commit();

            return redirect()
                ->route('admin.reports.final-event-reports.show', $finalEventReport)
                ->with('success', 'Laporan akhir acara berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui laporan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified final event report.
     */
    public function destroy(FinalEventReport $finalEventReport)
    {
        // Check permission - only draft can be deleted
        if (!$finalEventReport->isDraft() && !Auth::user()->hasRole('admin')) {
            return back()->with('error', 'Hanya laporan dengan status draft yang dapat dihapus.');
        }

        DB::beginTransaction();
        try {
            $finalEventReport->delete();

            DB::commit();

            return redirect()
                ->route('admin.reports.final-event-reports.index')
                ->with('success', 'Laporan akhir acara berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus laporan: ' . $e->getMessage());
        }
    }

    /**
     * Submit report for review.
     */
    public function submitForReview(FinalEventReport $finalEventReport)
    {
        if (!$finalEventReport->isDraft()) {
            return back()->with('error', 'Laporan ini tidak dapat diajukan untuk ditinjau.');
        }

        DB::beginTransaction();
        try {
            $finalEventReport->submitForReview(Auth::id());

            DB::commit();

            return back()->with('success', 'Laporan berhasil diajukan untuk ditinjau.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengajukan laporan: ' . $e->getMessage());
        }
    }

    /**
     * Review the report.
     */
    public function review(Request $request, FinalEventReport $finalEventReport)
    {
        if (!$finalEventReport->canBeReviewedBy(Auth::user())) {
            return back()->with('error', 'Anda tidak memiliki izin untuk meninjau laporan ini.');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $finalEventReport->review(Auth::id(), $validated['notes'] ?? null);

            DB::commit();

            return back()->with('success', 'Laporan berhasil ditinjau.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal meninjau laporan: ' . $e->getMessage());
        }
    }

    /**
     * Approve the report.
     */
    public function approve(Request $request, FinalEventReport $finalEventReport)
    {
        if (!$finalEventReport->canBeApprovedBy(Auth::user())) {
            return back()->with('error', 'Anda tidak memiliki izin untuk menyetujui laporan ini.');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $finalEventReport->approve(Auth::id(), $validated['notes'] ?? null);

            DB::commit();

            return back()->with('success', 'Laporan berhasil disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyetujui laporan: ' . $e->getMessage());
        }
    }

    /**
     * Publish the report.
     */
    public function publish(FinalEventReport $finalEventReport)
    {
        if (!$finalEventReport->isApproved()) {
            return back()->with('error', 'Hanya laporan yang disetujui yang dapat dipublikasikan.');
        }

        DB::beginTransaction();
        try {
            $finalEventReport->publish();

            DB::commit();

            return back()->with('success', 'Laporan berhasil dipublikasikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mempublikasikan laporan: ' . $e->getMessage());
        }
    }

    /**
     * Reject the report.
     */
    public function reject(Request $request, FinalEventReport $finalEventReport)
    {
        $validated = $request->validate([
            'notes' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $finalEventReport->reject($validated['notes']);

            DB::commit();

            return back()->with('success', 'Laporan ditolak dan dikembalikan ke status draft.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menolak laporan: ' . $e->getMessage());
        }
    }

    /**
     * Upload photo to gallery.
     */
    public function uploadPhoto(Request $request, FinalEventReport $finalEventReport)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'caption' => 'nullable|string|max:255',
        ]);

        try {
            $path = $request->file('photo')->store('final-event-reports/photos', 'public');

            $finalEventReport->addPhoto($path, $request->caption);

            return back()->with('success', 'Foto berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengunggah foto: ' . $e->getMessage());
        }
    }

    /**
     * Remove photo from gallery.
     */
    public function removePhoto(Request $request, FinalEventReport $finalEventReport)
    {
        $request->validate([
            'photo_path' => 'required|string',
        ]);

        try {
            $finalEventReport->removePhoto($request->photo_path);

            return back()->with('success', 'Foto berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus foto: ' . $e->getMessage());
        }
    }

    /**
     * Upload supporting document.
     */
    public function uploadDocument(Request $request, FinalEventReport $finalEventReport)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            $path = $request->file('document')->store('final-event-reports/documents', 'public');

            $finalEventReport->addSupportingDocument($path, $request->description);

            return back()->with('success', 'Dokumen pendukung berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengunggah dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Remove supporting document.
     */
    public function removeDocument(Request $request, FinalEventReport $finalEventReport)
    {
        $request->validate([
            'document_path' => 'required|string',
        ]);

        try {
            $finalEventReport->removeSupportingDocument($request->document_path);

            return back()->with('success', 'Dokumen pendukung berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF report.
     */
    public function generatePdf(FinalEventReport $finalEventReport)
    {
        try {
            // TODO: Implement PDF generation
            // This is a placeholder - you'll need to implement actual PDF generation

            return back()->with('info', 'Fitur generate PDF sedang dalam pengembangan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Generate PowerPoint presentation.
     */
    public function generatePresentation(FinalEventReport $finalEventReport)
    {
        try {
            // TODO: Implement PowerPoint generation
            // This is a placeholder - you'll need to implement actual PPTX generation

            return back()->with('info', 'Fitur generate presentasi sedang dalam pengembangan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate presentasi: ' . $e->getMessage());
        }
    }

    /**
     * Download report file.
     */
    public function downloadReport(FinalEventReport $finalEventReport)
    {
        if (!$finalEventReport->report_file) {
            return back()->with('error', 'File laporan tidak tersedia.');
        }

        return Storage::download($finalEventReport->report_file);
    }

    /**
     * Download presentation file.
     */
    public function downloadPresentation(FinalEventReport $finalEventReport)
    {
        if (!$finalEventReport->presentation_file) {
            return back()->with('error', 'File presentasi tidak tersedia.');
        }

        return Storage::download($finalEventReport->presentation_file);
    }

    /**
     * Print report view.
     */
    public function print(FinalEventReport $finalEventReport)
    {
        $finalEventReport->load(['event', 'createdBy', 'reviewedBy', 'approvedBy']);

        return view('admin.reports.final-event-reports.print', compact('finalEventReport'));
    }

    /**
     * Duplicate report.
     */
    public function duplicate(FinalEventReport $finalEventReport)
    {
        DB::beginTransaction();
        try {
            $newReport = $finalEventReport->replicate();
            $newReport->title = $finalEventReport->title . ' (Copy)';
            $newReport->report_code = null; // Will be auto-generated
            $newReport->status = 'draft';
            $newReport->created_by = Auth::id();
            $newReport->reviewed_by = null;
            $newReport->reviewed_at = null;
            $newReport->approved_by = null;
            $newReport->approved_at = null;
            $newReport->published_at = null;
            $newReport->report_file = null;
            $newReport->presentation_file = null;
            $newReport->save();

            DB::commit();

            return redirect()
                ->route('admin.reports.final-event-reports.edit', $newReport)
                ->with('success', 'Laporan berhasil diduplikasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menduplikasi laporan: ' . $e->getMessage());
        }
    }
}
