<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EventRequest;
use App\Models\Event;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * Display events listing
     */
    public function index(Request $request)
    {
        $query = Event::with('category');

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by date range
        if ($request->date_from) {
            $query->whereDate('start_datetime', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('start_datetime', '<=', $request->date_to);
        }

        // Filter by time status
        if ($request->time_status) {
            switch ($request->time_status) {
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
        }

        // Sorting
        $sortField = $request->get('sort', 'start_datetime');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $events = $query->paginate(20);

        // Get categories for filter
        $categories = Category::ofType(Category::TYPE_EVENT)->ordered()->get();

        return view('admin.events.index', compact('events', 'categories'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $categories = Category::active()
            ->ofType(Category::TYPE_EVENT)
            ->ordered()
            ->get();

        $tags = Tag::orderBy('name')->get();

        return view('admin.events.create', compact('categories', 'tags'));
    }

    /**
     * Store new event
     */
    public function store(EventRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            
            // Generate slug
            $data['slug'] = Str::slug($data['title']);

            // Handle featured image
            if ($request->hasFile('featured_image')) {
                $path = $request->file('featured_image')->store('events', 'public');
                $data['featured_image'] = $path;
            }

            // Initialize current_participants
            $data['current_participants'] = 0;

            $event = Event::create($data);

            // Attach tags
            if ($request->has('tags')) {
                $event->syncTags($request->tags);
            }

            // Handle gallery images
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $image) {
                    $event->addMedia($image, 'gallery');
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.events.index')
                ->with('success', 'Event berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show event detail
     */
    public function show(Event $event)
    {
        $event->load([
            'category',
            'tags',
            'registrations' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'feedbacks' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ]);

        // Registration statistics
        $registrationStats = [
            'total' => $event->registrations()->count(),
            'pending' => $event->registrations()->pending()->count(),
            'confirmed' => $event->registrations()->confirmed()->count(),
            'attended' => $event->registrations()->attended()->count(),
            'cancelled' => $event->registrations()->where('status', 'cancelled')->count(),
        ];

        // Rating statistics
        $feedbackStats = [
            'total' => $event->feedbacks()->count(),
            'average_rating' => $event->feedbacks()->avg('overall_rating'),
            'total_testimonials' => $event->feedbacks()->testimonials()->count(),
        ];

        return view('admin.events.show', compact('event', 'registrationStats', 'feedbackStats'));
    }

    /**
     * Show edit form
     */
    public function edit(Event $event)
    {
        $event->load(['category', 'tags', 'media']);

        $categories = Category::active()
            ->ofType(Category::TYPE_EVENT)
            ->ordered()
            ->get();

        $tags = Tag::orderBy('name')->get();

        return view('admin.events.edit', compact('event', 'categories', 'tags'));
    }

    /**
     * Update event
     */
    public function update(EventRequest $request, Event $event)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            // Update slug if title changed
            if ($data['title'] !== $event->title) {
                $data['slug'] = Str::slug($data['title']);
            }

            // Handle featured image
            if ($request->hasFile('featured_image')) {
                // Delete old image
                if ($event->featured_image) {
                    \Storage::disk('public')->delete($event->featured_image);
                }
                $path = $request->file('featured_image')->store('events', 'public');
                $data['featured_image'] = $path;
            }

            $event->update($data);

            // Sync tags
            if ($request->has('tags')) {
                $event->syncTags($request->tags);
            }

            // Handle gallery images
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $image) {
                    $event->addMedia($image, 'gallery');
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.events.index')
                ->with('success', 'Event berhasil diupdate.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete event
     */
    public function destroy(Event $event)
    {
        // Check if event has registrations
        if ($event->registrations()->count() > 0) {
            return redirect()
                ->back()
                ->with('error', 'Event tidak dapat dihapus karena sudah memiliki pendaftaran.');
        }

        try {
            // Delete featured image
            if ($event->featured_image) {
                \Storage::disk('public')->delete($event->featured_image);
            }

            // Delete all media
            $event->clearMediaCollection();

            $event->delete();

            return redirect()
                ->route('admin.events.index')
                ->with('success', 'Event berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Duplicate event
     */
    public function duplicate(Event $event)
    {
        DB::beginTransaction();
        try {
            $newEvent = $event->replicate();
            $newEvent->title = $event->title . ' (Copy)';
            $newEvent->slug = Str::slug($newEvent->title);
            $newEvent->current_participants = 0;
            $newEvent->status = Event::STATUS_DRAFT;
            $newEvent->save();

            // Copy tags
            $newEvent->syncTags($event->tags->pluck('id')->toArray());

            DB::commit();

            return redirect()
                ->route('admin.events.edit', $newEvent)
                ->with('success', 'Event berhasil diduplikasi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Export registrations to Excel
     */
    public function exportRegistrations(Event $event)
    {
        // Implement Excel export using Laravel Excel package
        return (new \App\Exports\EventRegistrationsExport($event))->download(
            'registrations-' . $event->slug . '.xlsx'
        );
    }
}