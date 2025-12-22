<?php

namespace App\Http\Controllers\Admin\Administration;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\Event;
use App\Models\CommitteeStructure;
use App\Http\Requests\Admin\ProposalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProposalController extends Controller
{
    /**
     * Display a listing of the proposals.
     */
    public function index(Request $request)
    {
        $query = Proposal::with(['event', 'structure', 'createdBy', 'submittedBy', 'approvedBy'])
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        // Filter by event
        if ($request->filled('event_id')) {
            $query->byEvent($request->event_id);
        }

        // Filter by structure
        if ($request->filled('structure_id')) {
            $query->byStructure($request->structure_id);
        }

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->submittedBetween($request->start_date, $request->end_date);
        }

        // Filter overdue
        if ($request->boolean('show_overdue')) {
            $query->overdue();
        }

        $proposals = $query->paginate(15)->withQueryString();

        // Get filter options
        $events = Event::select('id', 'title')->get();
        $structures = CommitteeStructure::select('id', 'name')->get();

        // Statistics
        $stats = [
            'total' => Proposal::count(),
            'draft' => Proposal::draft()->count(),
            'pending' => Proposal::pending()->count(),
            'approved' => Proposal::approved()->count(),
            'rejected' => Proposal::rejected()->count(),
            'overdue' => Proposal::overdue()->count(),
        ];

        return view('admin.administrations.proposals.index', compact('proposals', 'events', 'structures', 'stats'));
    }

    /**
     * Show the form for creating a new proposal.
     */
    public function create()
    {
        $events = Event::select('id', 'title')->get();
        $structures = CommitteeStructure::select('id', 'name')->get();

        return view('admin.administrations.proposals.create', compact('events', 'structures'));
    }

    /**
     * Store a newly created proposal in storage.
     */
    public function store(ProposalRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = Auth::id();
        $data['status'] = Proposal::STATUS_DRAFT;

        // Handle main document file upload
        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $data['document_file'] = $file->storeAs('proposals/documents', $filename, 'public');
        }

        // Handle supporting documents
        if ($request->hasFile('supporting_documents')) {
            $supportingDocs = [];
            foreach ($request->file('supporting_documents') as $file) {
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $supportingDocs[] = $file->storeAs('proposals/supporting', $filename, 'public');
            }
            $data['supporting_documents'] = $supportingDocs;
        }

        $proposal = Proposal::create($data);

        return redirect()
            ->route('admin.proposals.show', $proposal)
            ->with('success', 'Proposal berhasil dibuat!');
    }

    /**
     * Display the specified proposal.
     */
    public function show(Proposal $proposal)
    {
        $proposal->load(['event', 'structure', 'createdBy', 'submittedBy', 'reviewedBy', 'approvedBy']);

        return view('admin.administrations.proposals.show', compact('proposal'));
    }

    /**
     * Show the form for editing the specified proposal.
     */
    public function edit(Proposal $proposal)
    {
        // Only draft proposals can be edited
        if (!$proposal->isDraft()) {
            return redirect()
                ->route('admin.proposals.show', $proposal)
                ->with('error', 'Hanya proposal dengan status Draft yang dapat diedit.');
        }

        // Check permission
        if (!$proposal->canBeEditedBy(Auth::user())) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit proposal ini.');
        }

        $events = Event::select('id', 'title')->get();
        $structures = CommitteeStructure::select('id', 'name')->get();

        return view('admin.administrations.proposals.edit', compact('proposal', 'events', 'structures'));
    }

    /**
     * Update the specified proposal in storage.
     */
    public function update(ProposalRequest $request, Proposal $proposal)
    {
        // Only draft proposals can be updated
        if (!$proposal->isDraft()) {
            return redirect()
                ->route('admin.proposals.show', $proposal)
                ->with('error', 'Hanya proposal dengan status Draft yang dapat diupdate.');
        }

        // Check permission
        if (!$proposal->canBeEditedBy(Auth::user())) {
            abort(403, 'Anda tidak memiliki izin untuk mengupdate proposal ini.');
        }

        $data = $request->validated();

        // Handle main document file upload
        if ($request->hasFile('document_file')) {
            // Delete old file
            if ($proposal->document_file && Storage::disk('public')->exists($proposal->document_file)) {
                Storage::disk('public')->delete($proposal->document_file);
            }

            $file = $request->file('document_file');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $data['document_file'] = $file->storeAs('proposals/documents', $filename, 'public');
        }

        // Handle new supporting documents
        if ($request->hasFile('supporting_documents')) {
            $existingDocs = $proposal->supporting_documents ?? [];
            foreach ($request->file('supporting_documents') as $file) {
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $existingDocs[] = $file->storeAs('proposals/supporting', $filename, 'public');
            }
            $data['supporting_documents'] = $existingDocs;
        }

        $proposal->update($data);

        return redirect()
            ->route('admin.proposals.show', $proposal)
            ->with('success', 'Proposal berhasil diupdate!');
    }

    /**
     * Remove the specified proposal from storage.
     */
    public function destroy(Proposal $proposal)
    {
        // Only draft proposals can be deleted
        if (!$proposal->isDraft()) {
            return redirect()
                ->route('admin.proposals.index')
                ->with('error', 'Hanya proposal dengan status Draft yang dapat dihapus.');
        }

        $proposal->delete();

        return redirect()
            ->route('admin.proposals.index')
            ->with('success', 'Proposal berhasil dihapus!');
    }

    /**
     * Submit proposal for review
     */
    public function submit(Proposal $proposal)
    {
        if (!$proposal->canBeSubmittedBy(Auth::user())) {
            return back()->with('error', 'Anda tidak memiliki izin untuk mengajukan proposal ini.');
        }

        if ($proposal->submit(Auth::id())) {
            return redirect()
                ->route('admin.proposals.show', $proposal)
                ->with('success', 'Proposal berhasil diajukan untuk review!');
        }

        return back()->with('error', 'Gagal mengajukan proposal. Pastikan proposal dalam status Draft.');
    }

    /**
     * Approve proposal
     */
    public function approve(Request $request, Proposal $proposal)
    {
        $request->validate([
            'approved_amount' => 'nullable|numeric|min:0',
            'approval_notes' => 'nullable|string|max:1000',
        ]);

        if (!$proposal->canBeApprovedBy(Auth::user())) {
            return back()->with('error', 'Anda tidak memiliki izin untuk menyetujui proposal ini.');
        }

        if ($proposal->approve(
            Auth::id(),
            $request->approved_amount,
            $request->approval_notes
        )) {
            return redirect()
                ->route('admin.proposals.show', $proposal)
                ->with('success', 'Proposal berhasil disetujui!');
        }

        return back()->with('error', 'Gagal menyetujui proposal.');
    }

    /**
     * Reject proposal
     */
    public function reject(Request $request, Proposal $proposal)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        if (!$proposal->canBeReviewedBy(Auth::user())) {
            return back()->with('error', 'Anda tidak memiliki izin untuk menolak proposal ini.');
        }

        if ($proposal->reject(Auth::id(), $request->rejection_reason)) {
            return redirect()
                ->route('admin.proposals.show', $proposal)
                ->with('success', 'Proposal telah ditolak.');
        }

        return back()->with('error', 'Gagal menolak proposal.');
    }

    /**
     * Request revision for proposal
     */
    public function requestRevision(Request $request, Proposal $proposal)
    {
        $request->validate([
            'review_feedback' => 'required|string|max:1000',
        ]);

        if (!$proposal->canBeReviewedBy(Auth::user())) {
            return back()->with('error', 'Anda tidak memiliki izin untuk meminta revisi proposal ini.');
        }

        if ($proposal->requestRevision(Auth::id(), $request->review_feedback)) {
            return redirect()
                ->route('admin.proposals.show', $proposal)
                ->with('success', 'Permintaan revisi telah dikirim.');
        }

        return back()->with('error', 'Gagal meminta revisi proposal.');
    }

    /**
     * Download proposal document
     */
    public function download(Proposal $proposal)
    {
        if (!$proposal->document_file) {
            return back()->with('error', 'Dokumen proposal tidak tersedia.');
        }

        if (!Storage::disk('public')->exists($proposal->document_file)) {
            return back()->with('error', 'File dokumen tidak ditemukan.');
        }

        return Storage::disk('public')->download($proposal->document_file);
    }

    /**
     * Print proposal (PDF generation would go here)
     */
    public function print(Proposal $proposal)
    {
        $proposal->load(['event', 'structure', 'createdBy', 'submittedBy', 'reviewedBy', 'approvedBy']);

        return view('admin.administrations.proposals.print', compact('proposal'));
    }
}
