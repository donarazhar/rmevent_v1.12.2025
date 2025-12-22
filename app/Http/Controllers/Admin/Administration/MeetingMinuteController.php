<?php

namespace App\Http\Controllers\Admin\Administration;

use App\Http\Controllers\Controller;
use App\Models\MeetingMinute;
use App\Models\Event;
use App\Models\CommitteeStructure;
use App\Models\User;
use App\Http\Requests\Admin\MeetingMinuteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MeetingMinuteController extends Controller
{
    /**
     * Display a listing of the meeting minutes.
     */
    public function index(Request $request)
    {
        $query = MeetingMinute::with(['event', 'structure', 'chairmanUser', 'secretaryUser', 'createdBy'])
            ->latest('meeting_date');

        // Filter by status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Filter by type
        if ($request->filled('meeting_type')) {
            $query->byType($request->meeting_type);
        }

        // Filter by event
        if ($request->filled('event_id')) {
            $query->byEvent($request->event_id);
        }

        // Filter by structure
        if ($request->filled('structure_id')) {
            $query->byStructure($request->structure_id);
        }

        // Filter by time period
        if ($request->filled('period')) {
            switch ($request->period) {
                case 'upcoming':
                    $query->upcoming();
                    break;
                case 'past':
                    $query->past();
                    break;
                case 'this_month':
                    $query->thisMonth();
                    break;
                case 'this_year':
                    $query->thisYear();
                    break;
            }
        }

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->meetingBetween($request->start_date, $request->end_date);
        }

        $minutes = $query->paginate(15)->withQueryString();

        // Get filter options
        $events = Event::select('id', 'title')->get();
        $structures = CommitteeStructure::select('id', 'name')->get();

        // Statistics
        $stats = [
            'total' => MeetingMinute::count(),
            'draft' => MeetingMinute::draft()->count(),
            'finalized' => MeetingMinute::finalized()->count(),
            'distributed' => MeetingMinute::distributed()->count(),
            'this_month' => MeetingMinute::thisMonth()->count(),
            'upcoming' => MeetingMinute::upcoming()->count(),
        ];

        return view('admin.administrations.meeting-minutes.index', compact('minutes', 'events', 'structures', 'stats'));
    }

    /**
     * Show the form for creating a new meeting minute.
     */
    public function create()
    {
        $events = Event::select('id', 'title')->get();
        $structures = CommitteeStructure::select('id', 'name')->get();

        // Hanya ambil user dengan role panitia
        $users = User::where('role', User::ROLE_PANITIA)
            ->where('status', User::STATUS_ACTIVE)
            ->orderBy('name', 'asc')
            ->select('id', 'name', 'role', 'position', 'seksi')
            ->get();

        return view('admin.administrations.meeting-minutes.create', compact('events', 'structures', 'users'));
    }

    /**
     * Store a newly created meeting minute in storage.
     */
    public function store(MeetingMinuteRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = Auth::id();
        $data['status'] = MeetingMinute::STATUS_DRAFT;

        // Handle document file upload
        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $data['document_file'] = $file->storeAs('meeting-minutes/documents', $filename, 'public');
        }

        // Handle attachments
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $attachments[] = $file->storeAs('meeting-minutes/attachments', $filename, 'public');
            }
            $data['attachments'] = $attachments;
        }

        $minute = MeetingMinute::create($data);

        return redirect()
            ->route('admin.meeting-minutes.show', $minute)
            ->with('success', 'Notulensi rapat berhasil dibuat!');
    }

    /**
     * Display the specified meeting minute.
     */
    public function show(MeetingMinute $meetingMinute)
    {
        $meetingMinute->load([
            'event',
            'structure',
            'chairmanUser',
            'secretaryUser',
            'createdBy',
            'finalizedBy'
        ]);

        // Get participant users
        $participants = [];
        if ($meetingMinute->participants) {
            $participants = User::whereIn('id', $meetingMinute->participants)->get();
        }

        // Get absent member users
        $absentMembers = [];
        if ($meetingMinute->absent_members) {
            $absentMembers = User::whereIn('id', $meetingMinute->absent_members)->get();
        }

        // Get distributed to users
        $distributedToUsers = [];
        if ($meetingMinute->distributed_to) {
            $distributedToUsers = User::whereIn('id', $meetingMinute->distributed_to)->get();
        }

        return view('admin.administrations.meeting-minutes.show', compact(
            'meetingMinute',
            'participants',
            'absentMembers',
            'distributedToUsers'
        ));
    }

    /**
     * Show the form for editing the specified meeting minute.
     */
    public function edit(MeetingMinute $meetingMinute)
    {
        // Only draft minutes can be edited
        if (!$meetingMinute->isDraft()) {
            return redirect()
                ->route('admin.meeting-minutes.show', $meetingMinute)
                ->with('error', 'Hanya notulensi dengan status Draft yang dapat diedit.');
        }

        // Check permission
        if (!$meetingMinute->canBeEditedBy(Auth::user())) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit notulensi ini.');
        }

        $events = Event::select('id', 'title')->get();
        $structures = CommitteeStructure::select('id', 'name')->get();

        // Hanya ambil user dengan role panitia
        $users = User::where('role', User::ROLE_PANITIA)
            ->where('status', User::STATUS_ACTIVE)
            ->orderBy('name', 'asc')
            ->select('id', 'name', 'role', 'position', 'seksi')
            ->get();

        return view('admin.administrations.meeting-minutes.edit', compact('meetingMinute', 'events', 'structures', 'users'));
    }

    /**
     * Update the specified meeting minute in storage.
     */
    public function update(MeetingMinuteRequest $request, MeetingMinute $meetingMinute)
    {
        // Only draft minutes can be updated
        if (!$meetingMinute->isDraft()) {
            return redirect()
                ->route('admin.meeting-minutes.show', $meetingMinute)
                ->with('error', 'Hanya notulensi dengan status Draft yang dapat diupdate.');
        }

        // Check permission
        if (!$meetingMinute->canBeEditedBy(Auth::user())) {
            abort(403, 'Anda tidak memiliki izin untuk mengupdate notulensi ini.');
        }

        $data = $request->validated();

        // Handle document file upload
        if ($request->hasFile('document_file')) {
            // Delete old file
            if ($meetingMinute->document_file && Storage::disk('public')->exists($meetingMinute->document_file)) {
                Storage::disk('public')->delete($meetingMinute->document_file);
            }

            $file = $request->file('document_file');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $data['document_file'] = $file->storeAs('meeting-minutes/documents', $filename, 'public');
        }

        // Handle new attachments
        if ($request->hasFile('attachments')) {
            $existingAttachments = $meetingMinute->attachments ?? [];
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $existingAttachments[] = $file->storeAs('meeting-minutes/attachments', $filename, 'public');
            }
            $data['attachments'] = $existingAttachments;
        }

        $meetingMinute->update($data);

        return redirect()
            ->route('admin.meeting-minutes.show', $meetingMinute)
            ->with('success', 'Notulensi rapat berhasil diupdate!');
    }

    /**
     * Remove the specified meeting minute from storage.
     */
    public function destroy(MeetingMinute $meetingMinute)
    {
        // Only draft minutes can be deleted
        if (!$meetingMinute->isDraft()) {
            return redirect()
                ->route('admin.meeting-minutes.index')
                ->with('error', 'Hanya notulensi dengan status Draft yang dapat dihapus.');
        }

        $meetingMinute->delete();

        return redirect()
            ->route('admin.meeting-minutes.index')
            ->with('success', 'Notulensi rapat berhasil dihapus!');
    }

    /**
     * Finalize the meeting minute
     */
    public function finalize(MeetingMinute $meetingMinute)
    {
        if (!$meetingMinute->canBeFinalizedBy(Auth::user())) {
            return back()->with('error', 'Anda tidak memiliki izin untuk memfinalisasi notulensi ini.');
        }

        if ($meetingMinute->finalize(Auth::id())) {
            return redirect()
                ->route('admin.meeting-minutes.show', $meetingMinute)
                ->with('success', 'Notulensi berhasil difinalisasi!');
        }

        return back()->with('error', 'Gagal memfinalisasi notulensi.');
    }

    /**
     * Send/distribute the meeting minute
     */
    public function send(Request $request, MeetingMinute $meetingMinute)
    {
        $request->validate([
            'distribute_to' => 'required|array|min:1',
            'distribute_to.*' => 'exists:users,id',
        ]);

        if ($meetingMinute->distribute($request->distribute_to)) {
            // Here you can add email notification logic

            return redirect()
                ->route('admin.meeting-minutes.show', $meetingMinute)
                ->with('success', 'Notulensi berhasil didistribusikan!');
        }

        return back()->with('error', 'Gagal mendistribusikan notulensi.');
    }

    /**
     * Download meeting minute document
     */
    public function download(MeetingMinute $meetingMinute)
    {
        if (!$meetingMinute->document_file) {
            return back()->with('error', 'Dokumen notulensi tidak tersedia.');
        }

        if (!Storage::disk('public')->exists($meetingMinute->document_file)) {
            return back()->with('error', 'File dokumen tidak ditemukan.');
        }

        return Storage::disk('public')->download($meetingMinute->document_file);
    }

    /**
     * Print meeting minute
     */
    public function print(MeetingMinute $meetingMinute)
    {
        $meetingMinute->load([
            'event',
            'structure',
            'chairmanUser',
            'secretaryUser',
            'createdBy',
            'finalizedBy'
        ]);

        // Get participant users
        $participants = [];
        if ($meetingMinute->participants) {
            $participants = User::whereIn('id', $meetingMinute->participants)->get();
        }

        // Get absent member users
        $absentMembers = [];
        if ($meetingMinute->absent_members) {
            $absentMembers = User::whereIn('id', $meetingMinute->absent_members)->get();
        }

        return view('admin.administrations.meeting-minutes.print', compact(
            'meetingMinute',
            'participants',
            'absentMembers'
        ));
    }
}
