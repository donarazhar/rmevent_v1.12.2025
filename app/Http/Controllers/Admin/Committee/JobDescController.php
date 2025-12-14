<?php
// app/Http/Controllers/Admin/JobDescController.php

namespace App\Http\Controllers\Admin\Committee;

use App\Http\Controllers\Controller;
use App\Models\JobDescription;
use App\Models\JobAssignment;
use App\Models\CommitteeStructure;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class JobDescController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = JobDescription::with(['structure', 'event', 'creator', 'assignments'])
            ->withCount('assignments');

        // Filter by structure
        if ($request->filled('structure_id')) {
            $query->where('structure_id', $request->structure_id);
        }

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
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $jobDescs = $query->paginate(15)->withQueryString();

        // Data for filters
        $structures = CommitteeStructure::orderBy('level')->get();
        $events = Event::where('status', 'published')
            ->orderBy('start_datetime', 'desc')
            ->get();

        return view('admin.committee.jobdescs.index', compact(
            'jobDescs',
            'structures',
            'events'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $structures = CommitteeStructure::orderBy('level')->get();
        $events = Event::where('status', 'published')
            ->orderBy('start_datetime', 'desc')
            ->get();

        return view('admin.committee.jobdescs.create', compact('structures', 'events'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'structure_id' => 'required|exists:committee_structures,id',
            'event_id' => 'nullable|exists:events,id',
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:job_descriptions,code',
            'description' => 'required|string',
            'responsibilities' => 'required|array|min:1',
            'responsibilities.*' => 'required|string',
            'requirements' => 'nullable|array',
            'requirements.*' => 'required|string',
            'skills_required' => 'nullable|array',
            'skills_required.*' => 'required|string',
            'estimated_hours' => 'nullable|integer|min:1',
            'workload_level' => 'required|in:ringan,sedang,berat',
            'priority' => 'required|in:rendah,sedang,tinggi,kritis',
            'required_members' => 'required|integer|min:1',
            'start_datetime' => 'nullable|date',
            'end_datetime' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:draft,active,completed,archived',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['assigned_members'] = 0;

        $jobDesc = JobDescription::create($validated);

        return redirect()
            ->route('admin.jobdescs.show', $jobDesc)
            ->with('success', 'Job Description berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(JobDescription $jobdesc)
    {
        $jobdesc->load([
            'structure',
            'event',
            'creator',
            'approver',
            'assignments.user',
            'assignments' => function ($query) {
                $query->orderBy('assigned_date', 'desc');
            }
        ]);

        // Available users for assignment (not yet assigned)
        $assignedUserIds = $jobdesc->assignments()->pluck('user_id')->toArray();
        $availableUsers = User::whereNotIn('id', $assignedUserIds)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin.committee.jobdescs.show', compact('jobdesc', 'availableUsers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobDescription $jobdesc)
    {
        $structures = CommitteeStructure::orderBy('level')->get();
        $events = Event::where('status', 'published')
            ->orderBy('start_date', 'desc')
            ->get();

        return view('admin.committee.jobdescs.edit', compact('jobdesc', 'structures', 'events'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobDescription $jobdesc)
    {
        $validated = $request->validate([
            'structure_id' => 'required|exists:committee_structures,id',
            'event_id' => 'nullable|exists:events,id',
            'title' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('job_descriptions', 'code')->ignore($jobdesc->id)
            ],
            'description' => 'required|string',
            'responsibilities' => 'required|array|min:1',
            'responsibilities.*' => 'required|string',
            'requirements' => 'nullable|array',
            'requirements.*' => 'required|string',
            'skills_required' => 'nullable|array',
            'skills_required.*' => 'required|string',
            'estimated_hours' => 'nullable|integer|min:1',
            'workload_level' => 'required|in:ringan,sedang,berat',
            'priority' => 'required|in:rendah,sedang,tinggi,kritis',
            'required_members' => 'required|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:draft,active,completed,archived',
        ]);

        $jobdesc->update($validated);

        return redirect()
            ->route('admin.jobdescs.show', $jobdesc)
            ->with('success', 'Job Description berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobDescription $jobdesc)
    {
        // Check if has active assignments
        if ($jobdesc->activeAssignments()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus Job Description yang memiliki assignment aktif!');
        }

        $jobdesc->delete();

        return redirect()
            ->route('admin.jobdescs.index')
            ->with('success', 'Job Description berhasil dihapus!');
    }

    /**
     * Assign user to job description
     */
    public function assign(Request $request, JobDescription $jobdesc)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'expected_completion_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string',
        ]);

        // Check if user already assigned
        $existingAssignment = JobAssignment::where('job_description_id', $jobdesc->id)
            ->where('user_id', $validated['user_id'])
            ->whereIn('status', ['assigned', 'in_progress'])
            ->first();

        if ($existingAssignment) {
            return back()->with('error', 'User sudah ditugaskan pada Job Description ini!');
        }

        // Check if job desc is full
        if ($jobdesc->assigned_members >= $jobdesc->required_members) {
            return back()->with('error', 'Job Description sudah penuh!');
        }

        DB::transaction(function () use ($jobdesc, $validated) {
            // Create assignment
            JobAssignment::create([
                'job_description_id' => $jobdesc->id,
                'user_id' => $validated['user_id'],
                'assigned_date' => now(),
                'expected_completion_date' => $validated['expected_completion_date'] ?? null,
                'status' => 'assigned',
                'progress_percentage' => 0,
                'notes' => $validated['notes'] ?? null,
                'assigned_by' => Auth::id(),
            ]);

            // Update assigned members count
            $jobdesc->increment('assigned_members');
        });

        return back()->with('success', 'User berhasil ditugaskan!');
    }

    /**
     * Unassign user from job description
     */
    public function unassign(JobDescription $jobdesc, User $user)
    {
        $assignment = JobAssignment::where('job_description_id', $jobdesc->id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['assigned', 'in_progress'])
            ->first();

        if (!$assignment) {
            return back()->with('error', 'Assignment tidak ditemukan!');
        }

        DB::transaction(function () use ($jobdesc, $assignment) {
            // Update assignment status
            $assignment->update([
                'status' => 'cancelled',
            ]);

            // Decrement assigned members count
            $jobdesc->decrement('assigned_members');
        });

        return back()->with('success', 'User berhasil dibatalkan dari tugas!');
    }

    /**
     * Bulk assign users to job descriptions
     */
    public function bulkAssign(Request $request)
    {
        $validated = $request->validate([
            'job_description_ids' => 'required|array|min:1',
            'job_description_ids.*' => 'exists:job_descriptions,id',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
            'expected_completion_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string',
        ]);

        $successCount = 0;
        $errorCount = 0;

        DB::transaction(function () use ($validated, &$successCount, &$errorCount) {
            foreach ($validated['job_description_ids'] as $jobDescId) {
                $jobDesc = JobDescription::find($jobDescId);

                foreach ($validated['user_ids'] as $userId) {
                    // Check if already assigned
                    $exists = JobAssignment::where('job_description_id', $jobDescId)
                        ->where('user_id', $userId)
                        ->whereIn('status', ['assigned', 'in_progress'])
                        ->exists();

                    if ($exists) {
                        $errorCount++;
                        continue;
                    }

                    // Check if job desc is full
                    if ($jobDesc->assigned_members >= $jobDesc->required_members) {
                        $errorCount++;
                        continue;
                    }

                    // Create assignment
                    JobAssignment::create([
                        'job_description_id' => $jobDescId,
                        'user_id' => $userId,
                        'assigned_date' => now(),
                        'expected_completion_date' => $validated['expected_completion_date'] ?? null,
                        'status' => 'assigned',
                        'progress_percentage' => 0,
                        'notes' => $validated['notes'] ?? null,
                        'assigned_by' => Auth::id(),
                    ]);

                    $jobDesc->increment('assigned_members');
                    $successCount++;
                }
            }
        });

        $message = "Berhasil menugaskan {$successCount} assignment.";
        if ($errorCount > 0) {
            $message .= " {$errorCount} assignment gagal (sudah ditugaskan atau penuh).";
        }

        return back()->with('success', $message);
    }
}
