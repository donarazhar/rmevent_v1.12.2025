<?php
// app/Http/Controllers/Admin/FeedbackController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Event;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $query = Feedback::with(['user', 'event']);

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('subject', 'like', "%{$request->search}%")
                  ->orWhere('message', 'like', "%{$request->search}%")
                  ->orWhere('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        // Filter by type
        if ($request->type) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by event
        if ($request->event_id) {
            $query->where('event_id', $request->event_id);
        }

        // Filter by rating
        if ($request->min_rating) {
            $query->where('overall_rating', '>=', $request->min_rating);
        }

        $feedbacks = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get events for filter
        $events = Event::orderBy('start_datetime', 'desc')->get();

        // Statistics
        $stats = [
            'total' => Feedback::count(),
            'new' => Feedback::where('status', Feedback::STATUS_NEW)->count(),
            'testimonials' => Feedback::testimonials()->count(),
            'average_rating' => Feedback::whereNotNull('overall_rating')->avg('overall_rating'),
        ];

        return view('admin.feedbacks.index', compact('feedbacks', 'events', 'stats'));
    }

    public function show(Feedback $feedback)
    {
        $feedback->load(['user', 'event', 'registration', 'respondedBy']);

        return view('admin.feedbacks.show', compact('feedback'));
    }

    public function respond(Request $request, Feedback $feedback)
    {
        $request->validate([
            'admin_response' => 'required|string|min:10'
        ]);

        try {
            $feedback->respond($request->admin_response, auth()->id());

            // Send notification email to user
            // $feedback->notify(new FeedbackResponded());

            return redirect()
                ->back()
                ->with('success', 'Respon berhasil dikirim.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, Feedback $feedback)
    {
        $request->validate([
            'status' => 'required|in:new,in_review,responded,resolved,archived'
        ]);

        $feedback->update(['status' => $request->status]);

        return redirect()
            ->back()
            ->with('success', 'Status berhasil diupdate.');
    }

    public function publish(Feedback $feedback)
    {
        $feedback->publish();

        return redirect()
            ->back()
            ->with('success', 'Feedback berhasil dipublish sebagai testimonial.');
    }

    public function unpublish(Feedback $feedback)
    {
        $feedback->unpublish();

        return redirect()
            ->back()
            ->with('success', 'Feedback berhasil di-unpublish.');
    }

    public function toggleFeatured(Feedback $feedback)
    {
        $feedback->update(['is_featured' => !$feedback->is_featured]);

        return response()->json([
            'success' => true,
            'is_featured' => $feedback->is_featured,
            'message' => 'Status featured berhasil diupdate.'
        ]);
    }

    public function destroy(Feedback $feedback)
    {
        try {
            $feedback->delete();

            return redirect()
                ->route('admin.feedbacks.index')
                ->with('success', 'Feedback berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}