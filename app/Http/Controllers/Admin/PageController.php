<?php
// app/Http/Controllers/Admin/PageController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageRequest;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::with('parent')
            ->orderBy('order')
            ->paginate(20);

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        $pages = Page::parents()->ordered()->get();
        $templates = [
            'default' => 'Default',
            'landing' => 'Landing Page',
            'contact' => 'Contact',
            'faq' => 'FAQ',
            'gallery' => 'Gallery',
        ];

        return view('admin.pages.create', compact('pages', 'templates'));
    }

    public function store(PageRequest $request)
    {
        try {
            $data = $request->validated();
            $data['slug'] = Str::slug($data['title']);

            // Check if set as homepage
            if (isset($data['is_homepage']) && $data['is_homepage']) {
                Page::where('is_homepage', true)->update(['is_homepage' => false]);
            }

            $page = Page::create($data);

            return redirect()
                ->route('admin.pages.index')
                ->with('success', 'Halaman berhasil dibuat.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Page $page)
    {
        $pages = Page::parents()
            ->where('id', '!=', $page->id)
            ->ordered()
            ->get();

        $templates = [
            'default' => 'Default',
            'landing' => 'Landing Page',
            'contact' => 'Contact',
            'faq' => 'FAQ',
            'gallery' => 'Gallery',
        ];

        return view('admin.pages.edit', compact('page', 'pages', 'templates'));
    }

    public function update(PageRequest $request, Page $page)
    {
        try {
            $data = $request->validated();

            if ($data['title'] !== $page->title) {
                $data['slug'] = Str::slug($data['title']);
            }

            // Check if set as homepage
            if (isset($data['is_homepage']) && $data['is_homepage'] && !$page->is_homepage) {
                Page::where('is_homepage', true)->update(['is_homepage' => false]);
            }

            $page->update($data);

            return redirect()
                ->route('admin.pages.index')
                ->with('success', 'Halaman berhasil diupdate.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Page $page)
    {
        // Prevent deletion of homepage
        if ($page->is_homepage) {
            return redirect()
                ->back()
                ->with('error', 'Homepage tidak dapat dihapus.');
        }

        try {
            $page->delete();

            return redirect()
                ->route('admin.pages.index')
                ->with('success', 'Halaman berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}