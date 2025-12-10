<?php
// app/Http/Controllers/Admin/TagController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $query = Tag::query();

        // Search
        if ($request->search) {
            $query->search($request->search);
        }

        $tags = $query->withCount(['posts', 'events'])
            ->orderBy('name')
            ->paginate(20);

        return view('admin.tags.index', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
        ]);

        try {
            Tag::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'color' => $request->color ?? '#6B7280',
            ]);

            return redirect()
                ->back()
                ->with('success', 'Tag berhasil dibuat.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $tag->id,
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
        ]);

        try {
            $tag->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'color' => $request->color,
            ]);

            return redirect()
                ->back()
                ->with('success', 'Tag berhasil diupdate.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Tag $tag)
    {
        try {
            $tag->delete();

            return redirect()
                ->route('admin.tags.index')
                ->with('success', 'Tag berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Merge tags
     */
    public function merge(Request $request)
    {
        $request->validate([
            'source_tag_id' => 'required|exists:tags,id',
            'target_tag_id' => 'required|exists:tags,id|different:source_tag_id',
        ]);

        try {
            $sourceTag = Tag::findOrFail($request->source_tag_id);
            $targetTag = Tag::findOrFail($request->target_tag_id);

            // Move all posts and events from source to target
            \DB::table('taggables')
                ->where('tag_id', $sourceTag->id)
                ->update(['tag_id' => $targetTag->id]);

            // Delete source tag
            $sourceTag->delete();

            return redirect()
                ->back()
                ->with('success', 'Tag berhasil digabungkan.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}