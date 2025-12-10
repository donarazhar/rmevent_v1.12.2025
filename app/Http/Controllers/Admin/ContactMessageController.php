<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('subject', 'like', "%{$request->search}%")
                  ->orWhere('message', 'like', "%{$request->search}%");
            });
        }

        // Filter by type
        if ($request->type) {
            $query->byType($request->type);
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $messages = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistics
        $stats = [
            'total' => ContactMessage::count(),
            'new' => ContactMessage::new()->count(),
            'unreplied' => ContactMessage::unreplied()->count(),
            'replied' => ContactMessage::replied()->count(),
        ];

        return view('admin.contact-messages.index', compact('messages', 'stats'));
    }

    public function show(ContactMessage $contactMessage)
    {
        $contactMessage->load('repliedBy');

        // Mark as in progress if new
        if ($contactMessage->isNew()) {
            $contactMessage->markAsInProgress();
        }

        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    public function reply(Request $request, ContactMessage $contactMessage)
    {
        $request->validate([
            'admin_reply' => 'required|string|min:10'
        ]);

        try {
            $contactMessage->reply($request->admin_reply, auth()->id());

            // Send notification email
            // Mail::to($contactMessage->email)->send(new ContactMessageReplied($contactMessage));

            return redirect()
                ->back()
                ->with('success', 'Balasan berhasil dikirim.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function markAsResolved(ContactMessage $contactMessage)
    {
        $contactMessage->markAsResolved();

        return redirect()
            ->back()
            ->with('success', 'Pesan ditandai sebagai resolved.');
    }

    public function archive(ContactMessage $contactMessage)
    {
        $contactMessage->archive();

        return redirect()
            ->back()
            ->with('success', 'Pesan berhasil diarsipkan.');
    }

    public function destroy(ContactMessage $contactMessage)
    {
        try {
            $contactMessage->delete();

            return redirect()
                ->route('admin.contact-messages.index')
                ->with('success', 'Pesan berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function bulkAction(Request $request)
    {
        $action = $request->action;
        $messageIds = $request->message_ids;

        if (!$messageIds || !is_array($messageIds)) {
            return redirect()
                ->back()
                ->with('error', 'Tidak ada pesan yang dipilih.');
        }

        try {
            switch ($action) {
                case 'mark_resolved':
                    ContactMessage::whereIn('id', $messageIds)
                        ->update(['status' => ContactMessage::STATUS_RESOLVED]);
                    $message = 'Pesan berhasil ditandai sebagai resolved.';
                    break;

                case 'archive':
                    ContactMessage::whereIn('id', $messageIds)
                        ->update(['status' => ContactMessage::STATUS_ARCHIVED]);
                    $message = 'Pesan berhasil diarsipkan.';
                    break;

                case 'delete':
                    ContactMessage::whereIn('id', $messageIds)->delete();
                    $message = 'Pesan berhasil dihapus.';
                    break;

                default:
                    return redirect()->back()->with('error', 'Aksi tidak valid.');
            }

            return redirect()
                ->route('admin.contact-messages.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}