<?php

namespace App\Http\Controllers\Admin\Knowledge;

use App\Http\Controllers\Controller;
use App\Models\SOP;
use App\Models\User;
use App\Models\WorkInstruction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WorkInstructionController extends Controller
{
    /**
     * Display a listing of work instructions.
     */
    public function index(Request $request)
    {
        $query = WorkInstruction::with(['sop', 'creator', 'approver']);

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by difficulty
        if ($request->filled('difficulty_level')) {
            $query->where('difficulty_level', $request->difficulty_level);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('instruction_code', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $instructions = $query->paginate(15)->withQueryString();

        // Summary statistics
        $stats = [
            'total_count' => WorkInstruction::count(),
            'published_count' => WorkInstruction::published()->count(),
            'draft_count' => WorkInstruction::where('status', 'draft')->count(),
            'archived_count' => WorkInstruction::where('status', 'archived')->count(),
            'total_views' => WorkInstruction::sum('view_count'),
            'total_downloads' => WorkInstruction::sum('download_count'),
        ];

        return view('admin.knowledge.work-instructions.index', compact('instructions', 'stats'));
    }

    /**
     * Show the form for creating a new work instruction.
     */
    public function create()
    {
        $sops = SOP::orderBy('sop_code')->get();

        // Generate instruction code
        $lastInstruction = WorkInstruction::latest('id')->first();
        $nextNumber = $lastInstruction ? (intval(substr($lastInstruction->instruction_code, -3)) + 1) : 1;
        $instructionCode = 'WI-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return view('admin.knowledge.work-instructions.create', compact('sops', 'instructionCode'));
    }

    /**
     * Store a newly created work instruction in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sop_id' => 'nullable|exists:sops,id',
            'instruction_code' => 'required|unique:work_instructions,instruction_code',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:setup,execution,troubleshooting,maintenance,reporting,other',
            'content' => 'required|string',
            'steps' => 'nullable|json',
            'tools_required' => 'nullable|json',
            'materials_required' => 'nullable|json',
            'safety_notes' => 'nullable|string',
            'precautions' => 'nullable|json',
            'estimated_time' => 'nullable|integer|min:0',
            'difficulty_level' => 'required|in:easy,medium,hard',
            'version' => 'required|string|max:10',
            'effective_date' => 'required|date',
            'status' => 'required|in:draft,published',
            'notes' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        // Handle file uploads for attachments
        $uploadedAttachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('work-instructions/attachments', 'public');
                $uploadedAttachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getClientMimeType(),
                ];
            }
        }

        $validated['attachments'] = json_encode($uploadedAttachments);
        $validated['created_by'] = Auth::id();
        $validated['view_count'] = 0;
        $validated['download_count'] = 0;

        $instruction = WorkInstruction::create($validated);

        $message = $instruction->status === 'published'
            ? 'Work Instruction berhasil dibuat dan dipublish!'
            : 'Work Instruction berhasil disimpan sebagai draft!';

        return redirect()->route('admin.work-instructions.index')
            ->with('success', $message);
    }

    /**
     * Display the specified work instruction.
     */
    public function show(WorkInstruction $workInstruction)
    {
        $workInstruction->load(['sop', 'creator', 'approver']);

        // Increment view count
        $workInstruction->incrementViewCount();

        return view('admin.knowledge.work-instructions.show', compact('workInstruction'));
    }

    /**
     * Show the form for editing the specified work instruction.
     */
    public function edit(WorkInstruction $workInstruction)
    {
        // Only allow edit if not archived
        if ($workInstruction->status === 'archived') {
            return redirect()->route('admin.work-instructions.show', $workInstruction)
                ->with('error', 'Work Instruction yang sudah diarsip tidak bisa diedit!');
        }

        $sops = SOP::orderBy('sop_code')->get();

        return view('admin.knowledge.work-instructions.edit', compact('workInstruction', 'sops'));
    }

    /**
     * Update the specified work instruction in storage.
     */
    public function update(Request $request, WorkInstruction $workInstruction)
    {
        // Prevent update if archived
        if ($workInstruction->status === 'archived') {
            return redirect()->route('admin.work-instructions.show', $workInstruction)
                ->with('error', 'Work Instruction yang sudah diarsip tidak bisa diupdate!');
        }

        $validated = $request->validate([
            'sop_id' => 'nullable|exists:sops,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:setup,execution,troubleshooting,maintenance,reporting,other',
            'content' => 'required|string',
            'steps' => 'nullable|json',
            'tools_required' => 'nullable|json',
            'materials_required' => 'nullable|json',
            'safety_notes' => 'nullable|string',
            'precautions' => 'nullable|json',
            'estimated_time' => 'nullable|integer|min:0',
            'difficulty_level' => 'required|in:easy,medium,hard',
            'version' => 'required|string|max:10',
            'effective_date' => 'required|date',
            'status' => 'required|in:draft,published',
            'notes' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        // Handle file uploads for new attachments
        $existingAttachments = $workInstruction->attachments ?? [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('work-instructions/attachments', 'public');
                $existingAttachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getClientMimeType(),
                ];
            }
        }

        $validated['attachments'] = json_encode($existingAttachments);

        $workInstruction->update($validated);

        return redirect()->route('admin.work-instructions.index')
            ->with('success', 'Work Instruction berhasil diupdate!');
    }

    /**
     * Remove the specified work instruction from storage.
     */
    public function destroy(WorkInstruction $workInstruction)
    {
        // Only allow delete if draft
        if ($workInstruction->status !== 'draft') {
            return redirect()->route('admin.work-instructions.index')
                ->with('error', 'Hanya Work Instruction dengan status draft yang bisa dihapus!');
        }

        // Delete associated files
        if ($workInstruction->attachments) {
            foreach ($workInstruction->attachments as $attachment) {
                if (isset($attachment['path'])) {
                    Storage::disk('public')->delete($attachment['path']);
                }
            }
        }

        $workInstruction->delete();

        return redirect()->route('admin.work-instructions.index')
            ->with('success', 'Work Instruction berhasil dihapus!');
    }

    /**
     * Publish work instruction.
     */
    public function publish(WorkInstruction $workInstruction)
    {
        if ($workInstruction->status === 'archived') {
            return back()->with('error', 'Work Instruction yang diarsip tidak bisa dipublish!');
        }

        $workInstruction->publish(Auth::id());

        return back()->with('success', 'Work Instruction berhasil dipublish!');
    }

    /**
     * Archive work instruction.
     */
    public function archive(WorkInstruction $workInstruction)
    {
        $workInstruction->archive();

        return back()->with('success', 'Work Instruction berhasil diarsip!');
    }

    /**
     * Download work instruction.
     */
    public function download(WorkInstruction $workInstruction)
    {
        // Increment download count
        $workInstruction->incrementDownloadCount();

        // Generate PDF or return content
        // This is a placeholder - implement PDF generation logic
        // You can use packages like barryvdh/laravel-dompdf or similar

        return back()->with('info', 'Download feature coming soon! Download count updated.');
    }
}
