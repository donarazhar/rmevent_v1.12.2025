<?php
// app/Http/Controllers/Frontend/FeedbackController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\FeedbackRequest;
use App\Models\Feedback;
use App\Models\Event;
use App\Models\EventRegistration;

class FeedbackController extends Controller
{
    /**
     * Show feedback form
     */
    public function create($eventSlug = null)
    {
        $event = null;
        $registration = null;

        if ($eventSlug) {
            $event = Event::where('slug', $eventSlug)->firstOrFail();

            // Check if user has registration for this event
            if (auth()->check()) {
                $registration = EventRegistration::where('event_id', $event->id)
                    ->where('user_id', auth()->id())
                    ->whereIn('status', ['confirmed', 'attended'])
                    ->first();
            }
        }

        return view('frontend.feedback.create', compact('event', 'registration'));
    }

    /**
     * Store feedback
     */
    public function store(FeedbackRequest $request)
    {
        $data = $request->validated();

        // Add user info if authenticated
        if (auth()->check()) {
            $data['user_id'] = auth()->id();
        }

        $feedback = Feedback::create($data);

        // Handle file uploads if any
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $feedback->addMedia($file, 'feedback_attachments');
            }
        }

        // Log activity
        $feedback->logActivity('created', 'New feedback submitted');

        return redirect()
            ->route('feedback.success')
            ->with('success', 'Terima kasih atas feedback Anda! Masukan Anda sangat berharga bagi kami.');
    }

    /**
     * Show success page
     */
    public function success()
    {
        return view('frontend.feedback.success');
    }

    /**
     * Display testimonials
     */
    public function testimonials()
    {
        $testimonials = Feedback::published()
            ->testimonials()
            ->with(['user', 'event'])
            ->orderBy('display_order')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $featuredTestimonials = Feedback::published()
            ->testimonials()
            ->featured()
            ->highRated(5)
            ->take(3)
            ->get();

        return view('frontend.feedback.testimonials', compact(
            'testimonials',
            'featuredTestimonials'
        ));
    }
}