<?php
// app/Http/Controllers/Frontend/PageController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\FAQ;
use App\Models\Category;

class PageController extends Controller
{
    /**
     * Display page by slug
     */
    public function show($slug)
    {
        $page = Page::published()
            ->with('media')
            ->where('slug', $slug)
            ->firstOrFail();

        // Special handling for FAQ page
        if ($page->template === 'faq') {
            return $this->showFaq($page);
        }

        // Special handling for Gallery page
        if ($page->template === 'gallery') {
            return $this->showGallery($page);
        }

        // Special handling for Contact page
        if ($page->template === 'contact') {
            return $this->showContact($page);
        }

        return view('frontend.pages.show', compact('page'));
    }

    /**
     * Display FAQ page
     */
    private function showFaq($page)
    {
        $categories = Category::active()
            ->ofType(Category::TYPE_GENERAL)
            ->where('slug', 'like', 'faq%')
            ->withCount('faqs')
            ->ordered()
            ->get();

        $faqs = FAQ::active()
            ->with('category')
            ->ordered()
            ->get()
            ->groupBy('category.name');

        return view('frontend.pages.faq', compact('page', 'categories', 'faqs'));
    }

    /**
     * Display Gallery page
     */
    private function showGallery($page)
    {
        // Get media from events and posts
        $eventMedia = \App\Models\Media::whereIn('mediable_type', [
            'App\Models\Event',
            'App\Models\Post'
        ])
            ->images()
            ->inCollection('gallery')
            ->with('mediable')
            ->orderBy('created_at', 'desc')
            ->paginate(24);

        return view('frontend.pages.gallery', compact('page', 'eventMedia'));
    }

    /**
     * Display Contact page
     */
    private function showContact($page)
    {
        $contactInfo = [
            'email' => setting('contact_email'),
            'phone' => setting('contact_phone'),
            'address' => setting('contact_address'),
            'maps_url' => setting('contact_maps_url'),
            'maps_embed' => setting('contact_maps_embed'),
        ];

        return view('frontend.pages.contact', compact('page', 'contactInfo'));
    }
}