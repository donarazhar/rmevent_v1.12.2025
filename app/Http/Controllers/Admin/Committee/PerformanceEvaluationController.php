<?php

namespace App\Http\Controllers\Admin\Committee;

use App\Http\Controllers\Controller;
use App\Models\CommitteeStructure;
use App\Models\Event;
use App\Models\JobAssignment;
use App\Models\PerformanceEvaluation;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PerformanceEvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PerformanceEvaluation::with(['user', 'evaluator', 'structure', 'event'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by event
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        // Filter by period type
        if ($request->filled('period_type')) {
            $query->where('period_type', $request->period_type);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Search by evaluation code or user name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('evaluation_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $evaluations = $query->paginate(15);
        $events = Event::orderBy('title')->get();
        $users = User::orderBy('name')->get();

        return view('admin.committee.evaluations.index', compact('evaluations', 'events', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        $structures = CommitteeStructure::orderBy('name')->get();
        $events = Event::orderBy('title')->get();
        
        // Generate evaluation code
        $lastEvaluation = PerformanceEvaluation::latest('id')->first();
        $nextNumber = $lastEvaluation ? intval(substr($lastEvaluation->evaluation_code, 4)) + 1 : 1;
        $evaluationCode = 'EVAL-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return view('admin.committee.evaluations.create', compact('users', 'structures', 'events', 'evaluationCode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'structure_id' => 'nullable|exists:committee_structures,id',
            'event_id' => 'nullable|exists:events,id',
            'evaluation_code' => 'required|unique:performance_evaluations,evaluation_code',
            'period_type' => 'required|in:monthly,quarterly,event,annual',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'task_completion_score' => 'nullable|numeric|min:0|max:5',
            'quality_score' => 'nullable|numeric|min:0|max:5',
            'teamwork_score' => 'nullable|numeric|min:0|max:5',
            'initiative_score' => 'nullable|numeric|min:0|max:5',
            'leadership_score' => 'nullable|numeric|min:0|max:5',
            'discipline_score' => 'nullable|numeric|min:0|max:5',
            'strengths' => 'nullable|string',
            'weaknesses' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'achievements' => 'nullable|string',
            'improvement_areas' => 'nullable|string',
            'tasks_completed' => 'nullable|integer|min:0',
            'tasks_assigned' => 'nullable|integer|min:0',
            'attendance_days' => 'nullable|integer|min:0',
            'total_days' => 'nullable|integer|min:0',
            'evaluator_comments' => 'nullable|string',
        ]);

        $validated['evaluator_id'] = Auth::id();
        $validated['status'] = 'draft';

        // Calculate overall score
        $scores = [
            $validated['task_completion_score'] ?? null,
            $validated['quality_score'] ?? null,
            $validated['teamwork_score'] ?? null,
            $validated['initiative_score'] ?? null,
            $validated['leadership_score'] ?? null,
            $validated['discipline_score'] ?? null,
        ];

        $validScores = array_filter($scores, fn($score) => $score !== null);
        if (count($validScores) > 0) {
            $validated['overall_score'] = round(array_sum($validScores) / count($validScores), 2);
        }

        $evaluation = PerformanceEvaluation::create($validated);

        return redirect()->route('evaluations.show', $evaluation)
            ->with('success', 'Evaluasi kinerja berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PerformanceEvaluation $evaluation)
    {
        $evaluation->load(['user', 'evaluator', 'structure', 'event', 'approver']);
        
        // Get user's completed assignments in the period
        $completedAssignments = JobAssignment::where('user_id', $evaluation->user_id)
            ->where('status', 'completed')
            ->whereBetween('completed_at', [$evaluation->period_start, $evaluation->period_end])
            ->with('jobDescription')
            ->get();

        return view('admin.committee.evaluations.show', compact('evaluation', 'completedAssignments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PerformanceEvaluation $evaluation)
    {
        // Only allow editing draft evaluations
        if ($evaluation->status !== 'draft') {
            return redirect()->route('evaluations.show', $evaluation)
                ->with('error', 'Hanya evaluasi dengan status draft yang dapat diedit!');
        }

        $users = User::orderBy('name')->get();
        $structures = CommitteeStructure::orderBy('position_name')->get();
        $events = Event::orderBy('name')->get();

        return view('admin.committee.evaluations.edit', compact('evaluation', 'users', 'structures', 'events'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PerformanceEvaluation $evaluation)
    {
        // Only allow updating draft evaluations
        if ($evaluation->status !== 'draft') {
            return redirect()->route('evaluations.show', $evaluation)
                ->with('error', 'Hanya evaluasi dengan status draft yang dapat diupdate!');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'structure_id' => 'nullable|exists:committee_structures,id',
            'event_id' => 'nullable|exists:events,id',
            'period_type' => 'required|in:monthly,quarterly,event,annual',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'task_completion_score' => 'nullable|numeric|min:0|max:5',
            'quality_score' => 'nullable|numeric|min:0|max:5',
            'teamwork_score' => 'nullable|numeric|min:0|max:5',
            'initiative_score' => 'nullable|numeric|min:0|max:5',
            'leadership_score' => 'nullable|numeric|min:0|max:5',
            'discipline_score' => 'nullable|numeric|min:0|max:5',
            'strengths' => 'nullable|string',
            'weaknesses' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'achievements' => 'nullable|string',
            'improvement_areas' => 'nullable|string',
            'tasks_completed' => 'nullable|integer|min:0',
            'tasks_assigned' => 'nullable|integer|min:0',
            'attendance_days' => 'nullable|integer|min:0',
            'total_days' => 'nullable|integer|min:0',
            'evaluator_comments' => 'nullable|string',
        ]);

        // Calculate overall score
        $scores = [
            $validated['task_completion_score'] ?? null,
            $validated['quality_score'] ?? null,
            $validated['teamwork_score'] ?? null,
            $validated['initiative_score'] ?? null,
            $validated['leadership_score'] ?? null,
            $validated['discipline_score'] ?? null,
        ];

        $validScores = array_filter($scores, fn($score) => $score !== null);
        if (count($validScores) > 0) {
            $validated['overall_score'] = round(array_sum($validScores) / count($validScores), 2);
        }

        $evaluation->update($validated);

        return redirect()->route('evaluations.show', $evaluation)
            ->with('success', 'Evaluasi kinerja berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PerformanceEvaluation $evaluation)
    {
        // Only allow deleting draft evaluations
        if ($evaluation->status !== 'draft') {
            return redirect()->route('evaluations.index')
                ->with('error', 'Hanya evaluasi dengan status draft yang dapat dihapus!');
        }

        $evaluation->delete();

        return redirect()->route('evaluations.index')
            ->with('success', 'Evaluasi kinerja berhasil dihapus!');
    }

    /**
     * Submit evaluation for approval
     */
    public function submit(PerformanceEvaluation $evaluation)
    {
        if ($evaluation->status !== 'draft') {
            return redirect()->route('evaluations.show', $evaluation)
                ->with('error', 'Hanya evaluasi dengan status draft yang dapat disubmit!');
        }

        $evaluation->submit();

        return redirect()->route('evaluations.show', $evaluation)
            ->with('success', 'Evaluasi berhasil disubmit untuk persetujuan!');
    }

    /**
     * Approve evaluation
     */
    public function approve(Request $request, PerformanceEvaluation $evaluation)
    {
        if ($evaluation->status !== 'submitted') {
            return redirect()->route('evaluations.show', $evaluation)
                ->with('error', 'Hanya evaluasi yang sudah disubmit yang dapat diapprove!');
        }

        $request->validate([
            'approval_notes' => 'nullable|string',
        ]);

        $evaluation->approve(Auth::id(), $request->approval_notes);

        return redirect()->route('evaluations.show', $evaluation)
            ->with('success', 'Evaluasi berhasil disetujui!');
    }

    /**
     * Show user evaluations
     */
    public function userEvaluations(User $user)
    {
        $evaluations = PerformanceEvaluation::where('user_id', $user->id)
            ->with(['evaluator', 'structure', 'event'])
            ->orderBy('period_start', 'desc')
            ->paginate(10);

        // Calculate average scores
        $approvedEvaluations = PerformanceEvaluation::where('user_id', $user->id)
            ->where('status', 'approved')
            ->get();

        $averageScores = [
            'task_completion' => $approvedEvaluations->avg('task_completion_score'),
            'quality' => $approvedEvaluations->avg('quality_score'),
            'teamwork' => $approvedEvaluations->avg('teamwork_score'),
            'initiative' => $approvedEvaluations->avg('initiative_score'),
            'leadership' => $approvedEvaluations->avg('leadership_score'),
            'discipline' => $approvedEvaluations->avg('discipline_score'),
            'overall' => $approvedEvaluations->avg('overall_score'),
        ];

        return view('admin.committee.evaluations.user', compact('user', 'evaluations', 'averageScores'));
    }

    /**
     * Export evaluation to PDF
     */
    public function export(PerformanceEvaluation $evaluation)
    {
        $evaluation->load(['user', 'evaluator', 'structure', 'event', 'approver']);
        
        $pdf = Pdf::loadView('admin.committee.evaluations.pdf', compact('evaluation'));
        
        return $pdf->download('evaluation-' . $evaluation->evaluation_code . '.pdf');
    }
}