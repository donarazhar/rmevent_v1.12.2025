<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Post;
use App\Models\User;
use App\Models\Feedback;
use App\Models\ContactMessage;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics Cards
        $stats = [
            'total_events' => Event::count(),
            'active_events' => Event::published()->ongoing()->count(),
            'upcoming_events' => Event::published()->upcoming()->count(),
            'total_registrations' => EventRegistration::count(),
            'confirmed_registrations' => EventRegistration::confirmed()->count(),
            'total_participants' => EventRegistration::attended()->count(),
            'total_posts' => Post::count(),
            'published_posts' => Post::published()->count(),
            'total_users' => User::count(),
            'jamaah_count' => User::jamaah()->count(),
            'panitia_count' => User::panitia()->count(),
            'new_feedbacks' => Feedback::where('status', Feedback::STATUS_NEW)->count(),
            'new_messages' => ContactMessage::new()->count(),
        ];

        // Recent Registrations
        $recentRegistrations = EventRegistration::with(['event', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Upcoming Events
        $upcomingEvents = Event::published()
            ->upcoming()
            ->with('category')
            ->orderBy('start_datetime')
            ->take(5)
            ->get();

        // Recent Activities
        $recentActivities = ActivityLog::with(['user', 'subject'])
            ->orderBy('created_at', 'desc')
            ->take(15)
            ->get();

        // Registration Statistics (Last 30 days)
        $registrationStats = EventRegistration::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Event Categories Statistics
        $eventsByCategory = Event::select('categories.name', DB::raw('COUNT(*) as count'))
            ->join('categories', 'events.category_id', '=', 'categories.id')
            ->groupBy('categories.name')
            ->get();

        // Top Events by Registrations
        $topEvents = Event::withCount('registrations')
            ->orderBy('registrations_count', 'desc')
            ->take(5)
            ->get();

        // New Contact Messages
        $newMessages = ContactMessage::new()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Pending Feedbacks
        $pendingFeedbacks = Feedback::where('status', Feedback::STATUS_NEW)
            ->with(['event', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentRegistrations',
            'upcomingEvents',
            'recentActivities',
            'registrationStats',
            'eventsByCategory',
            'topEvents',
            'newMessages',
            'pendingFeedbacks'
        ));
    }
}