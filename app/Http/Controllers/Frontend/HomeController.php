<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Post;
use App\Models\Slider;
use App\Models\Feedback;
use App\Models\Category;
use App\Models\Setting;

class HomeController extends Controller
{
    /**
     * Display homepage
     */
    public function index()
    {
        // Get active sliders for homepage
        $sliders = Slider::active()
            ->forPlacement(Slider::PLACEMENT_HOMEPAGE)
            ->ordered()
            ->get();

        // Get featured events
        $featuredEvents = Event::published()
            ->featured()
            ->upcoming()
            ->with('category')
            ->take(6)
            ->get();

        // Get upcoming events
        $upcomingEvents = Event::published()
            ->upcoming()
            ->registrationOpen()
            ->with('category')
            ->take(4)
            ->get();

        // Get recent posts
        $recentPosts = Post::published()
            ->with(['author', 'category'])
            ->recent(3)
            ->get();

        // Get featured posts
        $featuredPosts = Post::published()
            ->featured()
            ->with(['author', 'category'])
            ->take(3)
            ->get();

        // Get published testimonials
        $testimonials = Feedback::published()
            ->testimonials()
            ->highRated(4)
            ->with('user')
            ->orderBy('display_order')
            ->take(6)
            ->get();

        // Get event categories
        $eventCategories = Category::active()
            ->ofType(Category::TYPE_EVENT)
            ->parents()
            ->withCount('events')
            ->ordered()
            ->get();

        // Get statistics
        $stats = [
            'total_events' => Event::published()->count(),
            'total_participants' => \App\Models\EventRegistration::confirmed()->count(),
            'active_events' => Event::published()->ongoing()->count(),
            'upcoming_events' => Event::published()->upcoming()->count(),
        ];

        return view('frontend.home', compact(
            'sliders',
            'featuredEvents',
            'upcomingEvents',
            'recentPosts',
            'featuredPosts',
            'testimonials',
            'eventCategories',
            'stats'
        ));
    }

    /**
     * Search functionality
     */
    public function search()
    {
        $query = request('q');
        $type = request('type', 'all'); // all, events, posts

        $results = [
            'query' => $query,
            'type' => $type,
        ];

        if ($type === 'all' || $type === 'events') {
            $results['events'] = Event::published()
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->with('category')
                ->paginate(12);
        }

        if ($type === 'all' || $type === 'posts') {
            $results['posts'] = Post::published()
                ->search($query)
                ->with(['author', 'category'])
                ->paginate(12);
        }

        return view('frontend.search', compact('results'));
    }
}