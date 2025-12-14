<?php

namespace App\Http\Controllers\Admin\Timeline;

use App\Http\Controllers\Controller;
use App\Models\ProjectTimeline;
use App\Models\Event;
use App\Models\CommitteeStructure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProjectTimelineController extends Controller
{
    public function index(Request $request)
    {
        $query = ProjectTimeline::with(['event', 'assignedUser', 'parent', 'children', 'structure'])
            ->orderBy('order');

        // Filter by event
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $timelines = $query->paginate(15);

        // Get filter data
        $events = Event::select('id', 'title')->get();
        $users = User::select('id', 'name')->get();
        $structures = CommitteeStructure::select('id', 'name')->get();

        return view('admin.timeline.project.index', compact('timelines', 'events', 'users', 'structures'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'parent_id' => 'nullable|exists:project_timelines,id',
            'structure_id' => 'nullable|exists:committee_structures,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'nullable|string|max:50|unique:project_timelines,code',
            'level' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'assigned_to' => 'nullable|exists:users,id',
            'team_members' => 'nullable|array',
            'team_members.*' => 'exists:users,id',
            'status' => 'required|in:not_started,in_progress,completed,delayed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'dependencies' => 'nullable|array',
            'dependencies.*' => 'exists:project_timelines,id',
            'estimated_budget' => 'nullable|numeric|min:0',
            'estimated_hours' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = Auth::id();
        
        // Calculate duration in days
        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);
        $validated['duration_days'] = $startDate->diffInDays($endDate) + 1;

        // Generate code if not provided
        if (empty($validated['code'])) {
            $validated['code'] = 'TL-' . strtoupper(uniqid());
        }

        // Set level based on parent
        if ($request->filled('parent_id')) {
            $parent = ProjectTimeline::find($request->parent_id);
            $validated['level'] = $parent ? $parent->level + 1 : 0;
        } else {
            $validated['level'] = 0;
        }

        // Set order if not provided
        if (empty($validated['order'])) {
            $maxOrder = ProjectTimeline::where('event_id', $validated['event_id'])
                ->where('parent_id', $validated['parent_id'])
                ->max('order');
            $validated['order'] = $maxOrder ? $maxOrder + 1 : 1;
        }

        $timeline = ProjectTimeline::create($validated);

        return redirect()->route('admin.timeline.index')
            ->with('success', 'Timeline berhasil ditambahkan');
    }

    public function update(Request $request, ProjectTimeline $timeline)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'parent_id' => 'nullable|exists:project_timelines,id',
            'structure_id' => 'nullable|exists:committee_structures,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'nullable|string|max:50|unique:project_timelines,code,' . $timeline->id,
            'level' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'actual_start_date' => 'nullable|date',
            'actual_end_date' => 'nullable|date|after_or_equal:actual_start_date',
            'assigned_to' => 'nullable|exists:users,id',
            'team_members' => 'nullable|array',
            'team_members.*' => 'exists:users,id',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'status' => 'required|in:not_started,in_progress,completed,delayed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'dependencies' => 'nullable|array',
            'dependencies.*' => 'exists:project_timelines,id',
            'estimated_budget' => 'nullable|numeric|min:0',
            'actual_budget' => 'nullable|numeric|min:0',
            'estimated_hours' => 'nullable|numeric|min:0',
            'actual_hours' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'completion_notes' => 'nullable|string',
        ]);

        // Calculate duration in days
        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);
        $validated['duration_days'] = $startDate->diffInDays($endDate) + 1;

        $timeline->update($validated);

        return redirect()->route('admin.timeline.index')
            ->with('success', 'Timeline berhasil diperbarui');
    }

    public function destroy(ProjectTimeline $timeline)
    {
        // Check if has children
        if ($timeline->children()->count() > 0) {
            return redirect()->route('admin.timeline.index')
                ->with('error', 'Timeline tidak dapat dihapus karena memiliki sub-timeline');
        }

        $timeline->delete();

        return redirect()->route('admin.timeline.index')
            ->with('success', 'Timeline berhasil dihapus');
    }

    public function duplicate(ProjectTimeline $timeline)
    {
        $newTimeline = $timeline->replicate();
        $newTimeline->name = $timeline->name . ' (Copy)';
        $newTimeline->code = 'TL-' . strtoupper(uniqid());
        $newTimeline->status = 'not_started';
        $newTimeline->progress_percentage = 0;
        $newTimeline->actual_start_date = null;
        $newTimeline->actual_end_date = null;
        $newTimeline->actual_budget = null;
        $newTimeline->actual_hours = null;
        $newTimeline->created_by = Auth::id();
        $newTimeline->save();

        return redirect()->route('admin.timeline.index')
            ->with('success', 'Timeline berhasil diduplikasi');
    }

    public function ganttChart(Request $request)
    {
        $eventId = $request->get('event_id');
        
        $timelines = ProjectTimeline::with(['event', 'assignedUser', 'parent'])
            ->when($eventId, function($query) use ($eventId) {
                $query->where('event_id', $eventId);
            })
            ->orderBy('start_date')
            ->get();

        $events = Event::select('id', 'title')->get();

        return view('admin.timeline.project.partials.gantt-chart', compact('timelines', 'events', 'eventId'));
    }

    public function export(Request $request)
    {
        // Implementation for export (Excel/PDF)
        // You can use Laravel Excel or similar package
        
        return redirect()->route('admin.timeline.index')
            ->with('info', 'Fitur export sedang dalam pengembangan');
    }
}