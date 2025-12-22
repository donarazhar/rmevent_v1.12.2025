<?php

namespace App\Http\Controllers\Admin\Administration;

use App\Http\Controllers\Controller;
use App\Models\OfficialLetter;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class OfficialLetterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = OfficialLetter::with(['event', 'sender', 'createdBy', 'approvedBy', 'signatory']);

        // Filter by direction
        if ($request->filled('direction')) {
            $query->where('direction', $request->direction);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('letter_type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by classification
        if ($request->filled('classification')) {
            $query->where('classification', $request->classification);
        }

        // Filter by event
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('letter_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('letter_date', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Quick filters
        if ($request->filled('filter')) {
            switch ($request->filter) {
                case 'overdue':
                    $query->overdue();
                    break;
                case 'due_soon':
                    $query->dueSoon(7);
                    break;
                case 'urgent':
                    $query->urgent();
                    break;
                case 'this_month':
                    $query->thisMonth();
                    break;
                case 'this_year':
                    $query->thisYear();
                    break;
            }
        }

        $letters = $query->latest('letter_date')->paginate(20);
        $events = Event::select('id', 'title')->get();

        return view('admin.administrations.official-letters.index', compact('letters', 'events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $events = Event::select('id', 'title')->where('status', 'published')->get();
        $users = User::select('id', 'name')->get();

        // Get direction from query parameter (default: outgoing)
        $direction = $request->get('direction', 'outgoing');

        return view('admin.administrations.official-letters.create', compact('events', 'users', 'direction'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'nullable|exists:events,id',
            'letter_type' => 'required|in:invitation,announcement,notification,request,response,thank_you,cooperation,recommendation,other',
            'direction' => 'required|in:incoming,outgoing',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',

            // Sender (for outgoing) or Source (for incoming)
            'sender_name' => 'required_if:direction,incoming|nullable|string|max:255',
            'sender_organization' => 'nullable|string|max:255',

            // Recipient
            'recipient_name' => 'required|string|max:255',
            'recipient_organization' => 'nullable|string|max:255',
            'recipient_address' => 'nullable|string',
            'recipient_email' => 'nullable|email|max:255',

            // CC Recipients
            'cc_recipients' => 'nullable|array',
            'cc_recipients.*.name' => 'required|string|max:255',
            'cc_recipients.*.email' => 'required|email|max:255',
            'cc_recipients.*.organization' => 'nullable|string|max:255',

            // Reference
            'reference_number' => 'nullable|string|max:255',

            // Dates
            'letter_date' => 'required|date',
            'due_date' => 'nullable|date|after:letter_date',

            // Priority & Classification
            'priority' => 'required|in:low,normal,high,urgent',
            'classification' => 'required|in:public,internal,confidential,secret',

            // Signatory (for outgoing)
            'signatory' => 'nullable|exists:users,id',
            'signatory_name' => 'required_if:direction,outgoing|nullable|string|max:255',
            'signatory_position' => 'required_if:direction,outgoing|nullable|string|max:255',

            // Files
            'letter_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'supporting_files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,xlsx,xls|max:5120',

            // Notes
            'notes' => 'nullable|string',
            'internal_notes' => 'nullable|string',
        ]);

        // Handle sender_id for outgoing letters
        if ($validated['direction'] === 'outgoing') {
            $validated['sender_id'] = Auth::id();
        }

        // Handle file upload
        if ($request->hasFile('letter_file')) {
            $validated['letter_file'] = $request->file('letter_file')->store('official-letters/main', 'public');
        }

        // Handle supporting files
        if ($request->hasFile('supporting_files')) {
            $supportingFiles = [];
            foreach ($request->file('supporting_files') as $file) {
                $supportingFiles[] = $file->store('official-letters/supporting', 'public');
            }
            $validated['supporting_files'] = $supportingFiles;
        }

        // Handle attachment list (just file names, not actual files)
        if ($request->filled('attachment_list')) {
            $validated['attachment_list'] = $request->attachment_list;
            $validated['attachment_count'] = count($request->attachment_list);
        }

        // Set created_by
        $validated['created_by'] = Auth::id();

        // Create letter
        $letter = OfficialLetter::create($validated);

        return redirect()
            ->route('admin.official-letters.show', $letter)
            ->with('success', 'Surat berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(OfficialLetter $officialLetter)
    {
        $officialLetter->load([
            'event',
            'sender',
            'createdBy',
            'approvedBy',
            'signatory',
            'repliedToLetter',
            'replies'
        ]);

        return view('admin.administrations.official-letters.show', compact('officialLetter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OfficialLetter $officialLetter)
    {
        // Only draft letters can be edited
        if ($officialLetter->status !== 'draft') {
            return redirect()
                ->route('admin.official-letters.show', $officialLetter)
                ->with('error', 'Hanya surat dengan status draft yang dapat diedit.');
        }

        $events = Event::select('id', 'title')->where('status', 'published')->get();
        $users = User::select('id', 'name')->get();

        return view('admin.administrations.official-letters.edit', compact('officialLetter', 'events', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OfficialLetter $officialLetter)
    {
        // Only draft letters can be updated
        if ($officialLetter->status !== 'draft') {
            return redirect()
                ->route('admin.official-letters.show', $officialLetter)
                ->with('error', 'Hanya surat dengan status draft yang dapat diupdate.');
        }

        $validated = $request->validate([
            'event_id' => 'nullable|exists:events,id',
            'letter_type' => 'required|in:invitation,announcement,notification,request,response,thank_you,cooperation,recommendation,other',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',

            'sender_name' => 'required_if:direction,incoming|nullable|string|max:255',
            'sender_organization' => 'nullable|string|max:255',

            'recipient_name' => 'required|string|max:255',
            'recipient_organization' => 'nullable|string|max:255',
            'recipient_address' => 'nullable|string',
            'recipient_email' => 'nullable|email|max:255',

            'cc_recipients' => 'nullable|array',
            'reference_number' => 'nullable|string|max:255',

            'letter_date' => 'required|date',
            'due_date' => 'nullable|date|after:letter_date',

            'priority' => 'required|in:low,normal,high,urgent',
            'classification' => 'required|in:public,internal,confidential,secret',

            'signatory' => 'nullable|exists:users,id',
            'signatory_name' => 'required_if:direction,outgoing|nullable|string|max:255',
            'signatory_position' => 'required_if:direction,outgoing|nullable|string|max:255',

            'letter_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'supporting_files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,xlsx,xls|max:5120',

            'notes' => 'nullable|string',
            'internal_notes' => 'nullable|string',
        ]);

        // Handle file upload
        if ($request->hasFile('letter_file')) {
            // Delete old file
            if ($officialLetter->letter_file) {
                Storage::disk('public')->delete($officialLetter->letter_file);
            }
            $validated['letter_file'] = $request->file('letter_file')->store('official-letters/main', 'public');
        }

        // Handle supporting files
        if ($request->hasFile('supporting_files')) {
            $supportingFiles = $officialLetter->supporting_files ?? [];
            foreach ($request->file('supporting_files') as $file) {
                $supportingFiles[] = $file->store('official-letters/supporting', 'public');
            }
            $validated['supporting_files'] = $supportingFiles;
        }

        // Handle attachment list
        if ($request->filled('attachment_list')) {
            $validated['attachment_list'] = $request->attachment_list;
            $validated['attachment_count'] = count($request->attachment_list);
        }

        $officialLetter->update($validated);

        return redirect()
            ->route('admin.official-letters.show', $officialLetter)
            ->with('success', 'Surat berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OfficialLetter $officialLetter)
    {
        // Only draft letters can be deleted
        if ($officialLetter->status !== 'draft') {
            return redirect()
                ->route('admin.official-letters.index')
                ->with('error', 'Hanya surat dengan status draft yang dapat dihapus.');
        }

        $officialLetter->delete();

        return redirect()
            ->route('admin.official-letters.index')
            ->with('success', 'Surat berhasil dihapus!');
    }

    /**
     * Submit letter for approval
     */
    public function submitForApproval(OfficialLetter $officialLetter)
    {
        if ($officialLetter->submitForApproval()) {
            return redirect()
                ->route('admin.official-letters.show', $officialLetter)
                ->with('success', 'Surat berhasil diajukan untuk persetujuan.');
        }

        return back()->with('error', 'Gagal mengajukan surat untuk persetujuan.');
    }

    /**
     * Approve letter
     */
    public function approve(OfficialLetter $officialLetter)
    {
        if ($officialLetter->approve(Auth::id())) {
            return redirect()
                ->route('admin.official-letters.show', $officialLetter)
                ->with('success', 'Surat berhasil disetujui.');
        }

        return back()->with('error', 'Gagal menyetujui surat.');
    }

    /**
     * Reject letter
     */
    public function reject(OfficialLetter $officialLetter)
    {
        if ($officialLetter->reject()) {
            return redirect()
                ->route('admin.official-letters.show', $officialLetter)
                ->with('success', 'Surat dikembalikan ke status draft.');
        }

        return back()->with('error', 'Gagal menolak surat.');
    }

    /**
     * Send letter
     */
    public function send(OfficialLetter $officialLetter)
    {
        if ($officialLetter->send()) {
            return redirect()
                ->route('admin.official-letters.show', $officialLetter)
                ->with('success', 'Surat berhasil dikirim/diterima.');
        }

        return back()->with('error', 'Gagal mengirim/menerima surat.');
    }

    /**
     * Archive letter
     */
    public function archive(OfficialLetter $officialLetter)
    {
        if ($officialLetter->archive()) {
            return redirect()
                ->route('admin.official-letters.show', $officialLetter)
                ->with('success', 'Surat berhasil diarsipkan.');
        }

        return back()->with('error', 'Gagal mengarsipkan surat.');
    }

    public function download(OfficialLetter $officialLetter)
    {
        if (!$officialLetter->letter_file) {
            return back()->with('error', 'File surat tidak ditemukan.');
        }

        // Sanitize filename - replace / and \ with -
        $filename = str_replace(['/', '\\'], '-', $officialLetter->letter_number) . '.pdf';

        return Storage::disk('public')->download(
            $officialLetter->letter_file,
            $filename
        );
    }

    public function print(OfficialLetter $officialLetter)
    {
        $pdf = Pdf::loadView('admin.administrations.official-letters.print', compact('officialLetter'));

        // Sanitize filename - replace / and \ with -
        $filename = str_replace(['/', '\\'], '-', $officialLetter->letter_number) . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Generate letter number
     */
    public function generateNumber(Request $request)
    {
        $direction = $request->get('direction', 'outgoing');
        $letterNumber = OfficialLetter::generateLetterNumber($direction);

        return response()->json([
            'letter_number' => $letterNumber
        ]);
    }
}
