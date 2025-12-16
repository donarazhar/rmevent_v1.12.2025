<?php

namespace App\Http\Controllers\Admin\Knowledge;

use App\Http\Controllers\Controller;
use App\Models\SOP;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SOPController extends Controller
{
    /**
     * Display a listing of SOPs.
     */
    public function index(Request $request)
    {
        $query = SOP::with(['creator', 'approver'])
            ->latestVersions()
            ->latest();

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sop_code', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('purpose', 'like', "%{$search}%");
            });
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('effective_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('effective_date', '<=', $request->end_date);
        }

        $sops = $query->paginate(15)->withQueryString();

        // Statistics
        $stats = [
            'total' => SOP::latestVersions()->count(),
            'published' => SOP::published()->count(),
            'draft' => SOP::where('status', 'draft')->count(),
            'needs_review' => SOP::needingReview()->count(),
        ];

        return view('admin.knowledge.sop.index', compact('sops', 'stats'));
    }

    /**
     * Show the form for creating a new SOP.
     */
    public function create()
    {
        return view('admin.knowledge.sop.create');
    }

    /**
     * Store a newly created SOP.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'purpose' => 'nullable|string',
            'scope' => 'nullable|string',
            'category' => 'required|in:event_management,finance,registration,documentation,emergency,general,other',
            'content' => 'required|string',
            'procedures' => 'nullable|array',
            'responsibilities' => 'nullable|array',
            'related_forms' => 'nullable|array',
            'related_templates' => 'nullable|array',
            'effective_date' => 'required|date',
            'review_date' => 'nullable|date|after:effective_date',
            'expiry_date' => 'nullable|date|after:effective_date',
            'notes' => 'nullable|string',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120',
        ]);

        // Generate SOP code
        $lastSOP = SOP::latest('id')->first();
        $nextNumber = $lastSOP ? intval(substr($lastSOP->sop_code, 4)) + 1 : 1;
        $validated['sop_code'] = 'SOP-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Handle attachments
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('sop/attachments', $filename, 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                ];
            }
            $validated['attachments'] = $attachments;
        }

        $validated['created_by'] = Auth::id();
        $validated['version'] = '1.0';

        // If submit action is publish, set status accordingly
        if ($request->submit_action === 'publish') {
            $validated['status'] = 'under_review';
        }

        $sop = SOP::create($validated);

        return redirect()->route('admin.sops.show', $sop)
            ->with('success', 'SOP berhasil dibuat!');
    }

    /**
     * Display the specified SOP.
     */
    public function show(SOP $sop)
    {
        $sop->load(['creator', 'reviewer', 'approver', 'parentSOP', 'versions', 'workInstructions']);

        // Increment view count
        $sop->incrementViewCount();

        return view('admin.knowledge.sop.show', compact('sop'));
    }

    /**
     * Show the form for editing the specified SOP.
     */
    public function edit(SOP $sop)
    {
        // Only draft or under_review can be edited
        if (!in_array($sop->status, ['draft', 'under_review'])) {
            return redirect()->route('admin.sops.show', $sop)
                ->with('warning', 'SOP yang sudah published tidak dapat diedit. Silakan buat versi baru.');
        }

        return view('admin.knowledge.sop.edit', compact('sop'));
    }

    /**
     * Update the specified SOP.
     */
    public function update(Request $request, SOP $sop)
    {
        // Check if can be edited
        if (!in_array($sop->status, ['draft', 'under_review'])) {
            return redirect()->route('admin.sops.show', $sop)
                ->with('error', 'SOP yang sudah published tidak dapat diedit.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'purpose' => 'nullable|string',
            'scope' => 'nullable|string',
            'category' => 'required|in:event_management,finance,registration,documentation,emergency,general,other',
            'content' => 'required|string',
            'procedures' => 'nullable|array',
            'responsibilities' => 'nullable|array',
            'related_forms' => 'nullable|array',
            'related_templates' => 'nullable|array',
            'effective_date' => 'required|date',
            'review_date' => 'nullable|date|after:effective_date',
            'expiry_date' => 'nullable|date|after:effective_date',
            'notes' => 'nullable|string',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120',
            'remove_attachments' => 'nullable|array',
        ]);

        // Handle attachments
        $currentAttachments = $sop->attachments ?? [];

        // Remove selected attachments
        if ($request->filled('remove_attachments')) {
            foreach ($request->remove_attachments as $index) {
                if (isset($currentAttachments[$index])) {
                    Storage::disk('public')->delete($currentAttachments[$index]['path']);
                    unset($currentAttachments[$index]);
                }
            }
            $currentAttachments = array_values($currentAttachments);
        }

        // Add new attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('sop/attachments', $filename, 'public');
                $currentAttachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                ];
            }
        }

        $validated['attachments'] = $currentAttachments;

        // If submit action is publish
        if ($request->submit_action === 'publish' && $sop->status === 'draft') {
            $validated['status'] = 'under_review';
        }

        $sop->update($validated);

        return redirect()->route('admin.sops.show', $sop)
            ->with('success', 'SOP berhasil diupdate!');
    }

    /**
     * Remove the specified SOP.
     */
    public function destroy(SOP $sop)
    {
        // Only draft can be deleted
        if ($sop->status !== 'draft') {
            return redirect()->route('admin.sops.index')
                ->with('error', 'Hanya SOP dengan status draft yang dapat dihapus.');
        }

        // Delete attachments
        if ($sop->attachments) {
            foreach ($sop->attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }

        $sop->delete();

        return redirect()->route('admin.sops.index')
            ->with('success', 'SOP berhasil dihapus!');
    }

    /**
     * Approve the SOP.
     */
    public function approve(Request $request, SOP $sop)
    {
        if ($sop->status !== 'under_review') {
            return back()->with('error', 'SOP tidak dalam status review.');
        }

        $request->validate([
            'approval_notes' => 'nullable|string',
        ]);

        $sop->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'SOP berhasil disetujui!');
    }

    /**
     * Reject the SOP.
     */
    public function reject(Request $request, SOP $sop)
    {
        if ($sop->status !== 'under_review') {
            return back()->with('error', 'SOP tidak dalam status review.');
        }

        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $sop->update([
            'status' => 'draft',
            'notes' => ($sop->notes ? $sop->notes . "\n\n" : '') . "Rejected: " . $request->rejection_reason,
        ]);

        return back()->with('success', 'SOP telah ditolak dan dikembalikan ke draft.');
    }

    /**
     * Publish the SOP.
     */
    public function publish(SOP $sop)
    {
        if ($sop->status !== 'approved') {
            return back()->with('error', 'Hanya SOP yang sudah approved yang dapat dipublish.');
        }

        $sop->update(['status' => 'published']);

        return back()->with('success', 'SOP berhasil dipublish!');
    }

    /**
     * Archive the SOP.
     */
    public function archive(SOP $sop)
    {
        $sop->archive();

        return back()->with('success', 'SOP berhasil diarsipkan!');
    }

    /**
     * Create new version of SOP.
     */
    public function createVersion(Request $request, SOP $sop)
    {
        $request->validate([
            'version_notes' => 'required|string',
        ]);

        $newSOP = $sop->createNewVersion(Auth::id(), $request->version_notes);

        return redirect()->route('admin.sops.edit', $newSOP)
            ->with('success', 'Versi baru SOP berhasil dibuat!');
    }

    /**
     * Download the SOP as PDF.
     */
    public function download(SOP $sop)
    {
        // Increment download count
        $sop->incrementDownloadCount();

        // In production, implement actual PDF generation
        // For now, just download as HTML

        return back()->with('info', 'Fitur download PDF akan segera tersedia.');
    }

    /**
     * Export SOPs data.
     */
    public function export(Request $request)
    {
        // Implement export logic (CSV/Excel)
        return back()->with('info', 'Fitur export akan segera tersedia.');
    }
}
