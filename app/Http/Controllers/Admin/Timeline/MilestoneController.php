<?php
// app/Http/Controllers/Admin/MilestoneController.php

namespace App\Http\Controllers\Admin\Timeline;

use App\Http\Controllers\Controller;
use App\Models\Milestone;
use App\Models\Event;
use App\Models\ProjectTimeline;
use App\Models\CommitteeStructure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MilestoneController extends Controller
{
    public function index(Request $request)
    {
        $query = Milestone::with(['event', 'timeline', 'responsiblePerson', 'structure'])
            ->ordered();

        // Filter by event
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        // Filter by timeline
        if ($request->filled('timeline_id')) {
            $query->where('timeline_id', $request->timeline_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by verification status
        if ($request->filled('is_verified')) {
            $query->where('is_verified', $request->is_verified);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('code', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $milestones = $query->paginate(15);

        // Get filter data
        $events = Event::select('id', 'title')->get();
        $timelines = ProjectTimeline::select('id', 'name', 'code')->get();
        $users = User::select('id', 'name')->get();
        $structures = CommitteeStructure::select('id', 'name')->get();

        return view('admin.timeline.milestone.index', compact('milestones', 'events', 'timelines', 'users', 'structures'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'timeline_id' => 'nullable|exists:project_timelines,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:milestones,code',
            'description' => 'nullable|string',
            'target_date' => 'required|date',
            'success_criteria' => 'nullable|array',
            'success_criteria.*' => 'string',
            'deliverables' => 'nullable|array',
            'deliverables.*' => 'string',
            'status' => 'required|in:pending,in_progress,completed,delayed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'responsible_person' => 'nullable|exists:users,id',
            'structure_id' => 'nullable|exists:committee_structures,id',
            'order' => 'nullable|integer|min:0',
        ]);

        // Generate code if not provided
        if (empty($validated['code'])) {
            $validated['code'] = 'MLS-' . strtoupper(uniqid());
        }

        // Set order if not provided
        if (empty($validated['order'])) {
            $maxOrder = Milestone::where('event_id', $validated['event_id'])->max('order');
            $validated['order'] = $maxOrder ? $maxOrder + 1 : 1;
        }

        $milestone = Milestone::create($validated);

        return redirect()->route('admin.milestone.index')
            ->with('success', 'Milestone berhasil ditambahkan');
    }

    public function update(Request $request, Milestone $milestone)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'timeline_id' => 'nullable|exists:project_timelines,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:milestones,code,' . $milestone->id,
            'description' => 'nullable|string',
            'target_date' => 'required|date',
            'actual_date' => 'nullable|date',
            'success_criteria' => 'nullable|array',
            'success_criteria.*' => 'string',
            'deliverables' => 'nullable|array',
            'deliverables.*' => 'string',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'status' => 'required|in:pending,in_progress,completed,delayed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'responsible_person' => 'nullable|exists:users,id',
            'structure_id' => 'nullable|exists:committee_structures,id',
            'completion_notes' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
        ]);

        // Auto-complete if progress is 100%
        if (isset($validated['progress_percentage']) && $validated['progress_percentage'] == 100 && $milestone->status != 'completed') {
            $validated['status'] = 'completed';
            $validated['actual_date'] = now();
            $validated['completed_by'] = Auth::id();
            $validated['completed_at'] = now();
        }

        $milestone->update($validated);

        return redirect()->route('admin.milestone.index')
            ->with('success', 'Milestone berhasil diperbarui');
    }

    public function destroy(Milestone $milestone)
    {
        $milestone->delete();

        return redirect()->route('admin.milestone.index')
            ->with('success', 'Milestone berhasil dihapus');
    }

    public function complete(Request $request, Milestone $milestone)
    {
        $validated = $request->validate([
            'completion_notes' => 'nullable|string',
            'completion_proof' => 'nullable|array',
            'completion_proof.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        $proofFiles = [];
        if ($request->hasFile('completion_proof')) {
            foreach ($request->file('completion_proof') as $file) {
                $path = $file->store('milestones/proofs', 'public');
                $proofFiles[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'url' => Storage::url($path),
                ];
            }
        }

        $milestone->complete(
            Auth::id(),
            $validated['completion_notes'] ?? null,
            $proofFiles
        );

        return redirect()->route('admin.milestone.index')
            ->with('success', 'Milestone berhasil diselesaikan');
    }

    public function verify(Request $request, Milestone $milestone)
    {
        if ($milestone->status !== 'completed') {
            return redirect()->route('admin.milestone.index')
                ->with('error', 'Hanya milestone yang sudah selesai yang dapat diverifikasi');
        }

        $validated = $request->validate([
            'verification_notes' => 'nullable|string',
        ]);

        $milestone->verify(Auth::id(), $validated['verification_notes'] ?? null);

        return redirect()->route('admin.milestone.index')
            ->with('success', 'Milestone berhasil diverifikasi');
    }

    public function reopen(Milestone $milestone)
    {
        $milestone->reopen();

        return redirect()->route('admin.milestone.index')
            ->with('success', 'Milestone berhasil dibuka kembali');
    }

    public function kanban(Request $request)
    {
        $query = Milestone::with(['event', 'responsiblePerson', 'timeline'])
            ->ordered();

        // Apply filters
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('responsible_person')) {
            $query->where('responsible_person', $request->responsible_person);
        }

        $milestones = $query->get();
        $events = Event::all();
        $users = User::all();
        $timelines = ProjectTimeline::all();
        $structures = CommitteeStructure::all();

        return view('admin.timeline.milestone.kanban', compact(
            'milestones',
            'events',
            'users',
            'timelines',
            'structures'
        ));
    }

    public function timeline(Request $request)
    {
        $query = Milestone::with(['event', 'responsiblePerson', 'timeline'])
            ->orderBy('target_date', 'asc')
            ->orderBy('order', 'asc');

        // Apply filters
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $milestones = $query->get();
        $events = Event::all();
        $users = User::all();
        $timelines = ProjectTimeline::all();
        $structures = CommitteeStructure::all();

        return view('admin.timeline.milestone.timeline', compact(
            'milestones',
            'events',
            'users',
            'timelines',
            'structures'
        ));
    }
}
