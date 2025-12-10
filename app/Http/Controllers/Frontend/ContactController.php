<?php
// app/Http/Controllers/Frontend/ContactController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ContactMessageRequest;
use App\Models\ContactMessage;
use App\Notifications\ContactMessageReceived;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    /**
     * Store contact message
     */
    public function store(ContactMessageRequest $request)
    {
        $contactMessage = ContactMessage::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'type' => $request->type ?? ContactMessage::TYPE_GENERAL,
            'status' => ContactMessage::STATUS_NEW,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Send notification to admin
        $adminEmail = setting('contact_email');
        if ($adminEmail) {
            Notification::route('mail', $adminEmail)
                ->notify(new ContactMessageReceived($contactMessage));
        }

        // Log activity
        $contactMessage->logActivity('created', 'New contact message received');

        return redirect()
            ->back()
            ->with('success', 'Terima kasih! Pesan Anda telah berhasil dikirim. Tim kami akan segera menghubungi Anda.');
    }
}