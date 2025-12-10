<?php
// app/Http/Controllers/Frontend/EventController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use App\Models\Tag;

class EventController extends Controller
{
    /**
     * Display events listing
     */
    public function index()
    {
        $query = Event::published()->with('category');

        // Filter by category
        if (request('category')) {
            $query->whereHas('category', function ($q) {
                $q->where('slug', request('category'));
            });
        }

        // Filter by tag
        if (request('tag')) {
            $query->withAnyTags([request('tag')]);
        }

        // Filter by date range
        if (request('date_from')) {
            $query->where('start_datetime', '>=', request('date_from'));
        }
        if (request('date_to')) {
            $query->where('start_datetime', '<=', request('date_to'));
        }

        // Filter by status
        $status = request('status', 'upcoming');
        switch ($status) {
            case 'upcoming':
                $query->upcoming();
                break;
            case 'ongoing':
                $query->ongoing();
                break;
            case 'past':
                $query->past();
                break;
        }

        // Filter by availability
        if (request('available') === '1') {
            $query->available();
        }

        // Search
        if (request('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = request('sort', 'date_asc');
        switch ($sort) {
            case 'date_desc':
                $query->orderBy('start_datetime', 'desc');
                break;
            case 'date_asc':
                $query->orderBy('start_datetime', 'asc');
                break;
            case 'popular':
                $query->orderBy('current_participants', 'desc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
        }

        $events = $query->paginate(12);

        // Get categories for filter
        $categories = Category::active()
            ->ofType(Category::TYPE_EVENT)
            ->withCount('events')
            ->ordered()
            ->get();

        // Get popular tags
        $popularTags = Tag::popular(10)->get();

        return view('frontend.events.index', compact('events', 'categories', 'popularTags'));
    }

    /**
     * Display event detail
     */
    public function show($slug)
    {
        $event = Event::published()
            ->with(['category', 'tags', 'media'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment views
        $event->increment('views_count');

        // Log activity
        if (auth()->check()) {
            $event->logActivity('viewed', 'Viewed event: ' . $event->title);
        }

        // Get related events
        $relatedEvents = Event::published()
            ->where('id', '!=', $event->id)
            ->where('category_id', $event->category_id)
            ->upcoming()
            ->take(3)
            ->get();

        // Get testimonials for this event
        $testimonials = $event->feedbacks()
            ->published()
            ->highRated(4)
            ->with('user')
            ->take(6)
            ->get();

        // Check if user already registered
        $userRegistration = null;
        if (auth()->check()) {
            $userRegistration = $event->registrations()
                ->where('user_id', auth()->id())
                ->whereIn('status', ['pending', 'confirmed'])
                ->first();
        }

        return view('frontend.events.show', compact(
            'event',
            'relatedEvents',
            'testimonials',
            'userRegistration'
        ));
    }

    /**
     * Display events by category
     */
    public function category($slug)
    {
        $category = Category::active()
            ->ofType(Category::TYPE_EVENT)
            ->where('slug', $slug)
            ->firstOrFail();

        $events = Event::published()
            ->where('category_id', $category->id)
            ->upcoming()
            ->with('category')
            ->paginate(12);

        return view('frontend.events.category', compact('category', 'events'));
    }

    /**
     * Display events by tag
     */
    public function tag($slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $events = Event::published()
            ->withAnyTags([$tag->id])
            ->with('category')
            ->paginate(12);

        return view('frontend.events.tag', compact('tag', 'events'));
    }
}