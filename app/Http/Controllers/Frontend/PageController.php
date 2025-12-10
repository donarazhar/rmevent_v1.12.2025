<?php

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
        // Try to find page
        $page = Page::published()
            ->with('media')
            ->where('slug', $slug)
            ->first();

        // If page not found, create basic page data for special templates
        if (!$page) {
            // For special pages, create temporary page object
            if (in_array($slug, ['contact', 'about', 'faq', 'gallery', 'privacy-policy', 'terms-of-service'])) {
                $page = new Page([
                    'title' => ucwords(str_replace('-', ' ', $slug)),
                    'slug' => $slug,
                    'content' => '',
                    'template' => $this->getTemplateForSlug($slug),
                    'status' => Page::STATUS_PUBLISHED,
                ]);
            } else {
                // For other pages, throw 404
                abort(404, 'Page not found');
            }
        }

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
     * Get template name for slug
     */
    private function getTemplateForSlug($slug)
    {
        $templates = [
            'contact' => 'contact',
            'faq' => 'faq',
            'gallery' => 'gallery',
        ];

        return $templates[$slug] ?? 'default';
    }

    /**
     * Display FAQ page
     */
    private function showFaq($page)
    {
        // Get FAQs grouped by category
        $faqs = FAQ::active()
            ->with('category')
            ->ordered()
            ->get()
            ->groupBy('category.name');

        return view('frontend.pages.faq', compact('page', 'faqs'));
    }

    /**
     * Display Gallery page
     */
    private function showGallery($page)
    {
        // Get media from events and posts
        $eventMedia = collect([]); // Empty collection for now
        
        // TODO: Implement media retrieval when Media model is ready
        // $eventMedia = \App\Models\Media::whereIn('mediable_type', [
        //     'App\Models\Event',
        //     'App\Models\Post'
        // ])
        //     ->images()
        //     ->inCollection('gallery')
        //     ->with('mediable')
        //     ->orderBy('created_at', 'desc')
        //     ->paginate(24);

        return view('frontend.pages.gallery', compact('page', 'eventMedia'));
    }

    /**
     * Display Contact page
     */
    private function showContact($page)
    {
        // Use hardcoded contact info for now
        $contactInfo = [
            'email' => config('mail.from.address', 'info@ramadhan1447.id'),
            'phone' => '+62 812-3456-7890',
            'address' => 'Jl. Contoh No. 123, Jakarta Selatan 12345',
            'maps_url' => 'https://maps.google.com',
            'maps_embed' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126920.23565174238!2d106.68942999999999!3d-6.229386!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e945e34b9d%3A0x5371bf0fdad786a2!2sJakarta!5e0!3m2!1sen!2sid!4v1234567890" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
        ];

        return view('frontend.pages.contact', compact('page', 'contactInfo'));
    }
}