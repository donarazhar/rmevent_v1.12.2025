<?php

namespace App\Http\Controllers\Admin\Timeline;

use App\Http\Controllers\Controller;
use App\Models\ProgressReport;
use App\Models\Event;
use App\Models\CommitteeStructure;
use App\Models\ProjectTimeline;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProgressReportController extends Controller
{
    public function index(Request $request)
    {
        $query = ProgressReport::with(['event', 'structure', 'creator', 'approver'])
            ->orderBy('report_date', 'desc');

        // Apply filters
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('report_type')) {
            $query->where('report_type', $request->report_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('structure_id')) {
            $query->where('structure_id', $request->structure_id);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('report_code', 'like', '%' . $request->search . '%');
            });
        }

        $reports = $query->paginate(15)->withQueryString();
        $events = Event::all();
        $structures = CommitteeStructure::all();
        $users = User::all();
        $timelines = ProjectTimeline::all();

        return view('admin.timeline.progress.index', compact(
            'reports',
            'events',
            'structures',
            'users',
            'timelines'
        ));
    }

    public function create()
    {
        $events = Event::all();
        $structures = CommitteeStructure::all();
        $users = User::all();
        $timelines = ProjectTimeline::all();

        return view('admin.timeline.progress.create', compact(
            'events',
            'structures',
            'users',
            'timelines'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'structure_id' => 'nullable|exists:committee_structures,id',
            'timeline_id' => 'nullable|exists:project_timelines,id',
            'title' => 'required|string|max:255',
            'report_type' => 'required|in:daily,weekly,monthly,milestone,ad_hoc',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'report_date' => 'required|date',
            'executive_summary' => 'nullable|string',
            'activities_completed' => 'nullable|string',
            'ongoing_activities' => 'nullable|string',
            'planned_activities' => 'nullable|string',
            'issues_challenges' => 'nullable|string',
            'solutions_recommendations' => 'nullable|string',
            'overall_progress' => 'required|integer|min:0|max:100',
            'tasks_planned' => 'nullable|integer|min:0',
            'tasks_completed' => 'nullable|integer|min:0',
            'tasks_delayed' => 'nullable|integer|min:0',
            'budget_allocated' => 'nullable|numeric|min:0',
            'budget_used' => 'nullable|numeric|min:0',
            'team_members_involved' => 'nullable|integer|min:0',
            'hours_spent' => 'nullable|integer|min:0',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max
        ]);

        // Generate report code
        $validated['report_code'] = $this->generateReportCode();
        $validated['created_by'] = Auth::id();

        // Calculate budget variance
        if ($validated['budget_allocated'] && $validated['budget_used']) {
            $validated['budget_variance'] = $validated['budget_allocated'] - $validated['budget_used'];
        }

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('progress-reports', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getClientMimeType(),
                ];
            }
            $validated['attachments'] = $attachments;
        }

        $report = ProgressReport::create($validated);

        return redirect()->route('admin.progress-reports.index')
            ->with('success', 'Progress report berhasil dibuat!');
    }

    public function show(ProgressReport $progressReport)
    {
        $progressReport->load(['event', 'structure', 'timeline', 'creator', 'submittedTo', 'approver']);

        return view('admin.timeline.progress.show', compact('progressReport'));
    }

    public function edit(ProgressReport $progressReport)
    {
        // Only allow editing if status is draft or rejected
        if (!in_array($progressReport->status, ['draft', 'rejected'])) {
            return redirect()->route('admin.progress-reports.index')
                ->with('error', 'Report yang sudah disubmit tidak dapat diedit!');
        }

        $events = Event::all();
        $structures = CommitteeStructure::all();
        $users = User::all();
        $timelines = ProjectTimeline::all();

        return view('admin.timeline.progress.edit', compact(
            'progressReport',
            'events',
            'structures',
            'users',
            'timelines'
        ));
    }

    public function update(Request $request, ProgressReport $progressReport)
    {
        // Only allow updating if status is draft or rejected
        if (!in_array($progressReport->status, ['draft', 'rejected'])) {
            return redirect()->route('admin.progress-reports.index')
                ->with('error', 'Report yang sudah disubmit tidak dapat diupdate!');
        }

        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'structure_id' => 'nullable|exists:committee_structures,id',
            'timeline_id' => 'nullable|exists:project_timelines,id',
            'title' => 'required|string|max:255',
            'report_type' => 'required|in:daily,weekly,monthly,milestone,ad_hoc',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'report_date' => 'required|date',
            'executive_summary' => 'nullable|string',
            'activities_completed' => 'nullable|string',
            'ongoing_activities' => 'nullable|string',
            'planned_activities' => 'nullable|string',
            'issues_challenges' => 'nullable|string',
            'solutions_recommendations' => 'nullable|string',
            'overall_progress' => 'required|integer|min:0|max:100',
            'tasks_planned' => 'nullable|integer|min:0',
            'tasks_completed' => 'nullable|integer|min:0',
            'tasks_delayed' => 'nullable|integer|min:0',
            'budget_allocated' => 'nullable|numeric|min:0',
            'budget_used' => 'nullable|numeric|min:0',
            'team_members_involved' => 'nullable|integer|min:0',
            'hours_spent' => 'nullable|integer|min:0',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        // Calculate budget variance
        if ($validated['budget_allocated'] && $validated['budget_used']) {
            $validated['budget_variance'] = $validated['budget_allocated'] - $validated['budget_used'];
        }

        // Handle new file uploads
        if ($request->hasFile('attachments')) {
            $existingAttachments = $progressReport->attachments ?? [];
            $newAttachments = [];

            foreach ($request->file('attachments') as $file) {
                $path = $file->store('progress-reports', 'public');
                $newAttachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getClientMimeType(),
                ];
            }

            $validated['attachments'] = array_merge($existingAttachments, $newAttachments);
        }

        $progressReport->update($validated);

        return redirect()->route('admin.progress-reports.index')
            ->with('success', 'Progress report berhasil diupdate!');
    }

    public function destroy(ProgressReport $progressReport)
    {
        // Only allow deleting if status is draft
        if ($progressReport->status !== 'draft') {
            return redirect()->route('admin.progress-reports.index')
                ->with('error', 'Hanya report dengan status draft yang dapat dihapus!');
        }

        // Delete attachments
        if ($progressReport->attachments) {
            foreach ($progressReport->attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }

        $progressReport->delete();

        return redirect()->route('admin.progress-reports.index')
            ->with('success', 'Progress report berhasil dihapus!');
    }

    public function submit(Request $request, ProgressReport $progressReport)
    {
        $request->validate([
            'submitted_to' => 'required|exists:users,id',
        ]);

        $progressReport->submit($request->submitted_to);

        return redirect()->route('admin.progress-reports.show', $progressReport)
            ->with('success', 'Progress report berhasil disubmit!');
    }

    public function approve(Request $request, ProgressReport $progressReport)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string',
            'feedback' => 'required_if:action,reject|string',
        ]);

        if ($request->action === 'approve') {
            $progressReport->approve(Auth::id(), $request->notes);
            $message = 'Progress report berhasil diapprove!';
        } else {
            $progressReport->reject($request->feedback);
            $message = 'Progress report ditolak dan dikembalikan ke draft!';
        }

        return redirect()->route('admin.progress-reports.show', $progressReport)
            ->with('success', $message);
    }

    public function export(ProgressReport $progressReport)
    {
        // This is a placeholder - implement PDF export later
        return redirect()->back()->with('info', 'Export feature coming soon!');
    }

    private function generateReportCode()
    {
        $year = date('Y');
        $month = date('m');
        $lastReport = ProgressReport::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastReport ? intval(substr($lastReport->report_code, -3)) + 1 : 1;

        return 'PR-' . $year . $month . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}
