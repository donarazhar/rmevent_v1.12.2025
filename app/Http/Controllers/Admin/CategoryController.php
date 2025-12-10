<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::with('parent');

        // Filter by type
        if ($request->type) {
            $query->ofType($request->type);
        }

        // Search
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $categories = $query->ordered()->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = Category::parents()->ordered()->get();
        
        $types = [
            Category::TYPE_POST => 'Post/Artikel',
            Category::TYPE_EVENT => 'Event/Kegiatan',
            Category::TYPE_GENERAL => 'General/Umum',
        ];

        return view('admin.categories.create', compact('parentCategories', 'types'));
    }

    public function store(CategoryRequest $request)
    {
        try {
            $data = $request->validated();
            $data['slug'] = Str::slug($data['name']);

            Category::create($data);

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Kategori berhasil dibuat.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::parents()
            ->where('id', '!=', $category->id)
            ->ordered()
            ->get();
        
        $types = [
            Category::TYPE_POST => 'Post/Artikel',
            Category::TYPE_EVENT => 'Event/Kegiatan',
            Category::TYPE_GENERAL => 'General/Umum',
        ];

        return view('admin.categories.edit', compact('category', 'parentCategories', 'types'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        try {
            $data = $request->validated();
            
            if ($data['name'] !== $category->name) {
                $data['slug'] = Str::slug($data['name']);
            }

            // Prevent category from being its own parent
            if (isset($data['parent_id']) && $data['parent_id'] == $category->id) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Kategori tidak bisa menjadi parent dari dirinya sendiri.');
            }

            $category->update($data);

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Kategori berhasil diupdate.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Category $category)
    {
        // Check if category has posts or events
        $hasItems = $category->posts()->count() > 0 || $category->events()->count() > 0;
        
        if ($hasItems) {
            return redirect()
                ->back()
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki post/event.');
        }

        // Check if has children
        if ($category->children()->count() > 0) {
            return redirect()
                ->back()
                ->with('error', 'Kategori tidak dapat dihapus karena memiliki sub-kategori.');
        }

        try {
            $category->delete();

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Kategori berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reorder categories
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:categories,id',
            'categories.*.order' => 'required|integer',
        ]);

        try {
            foreach ($request->categories as $categoryData) {
                Category::where('id', $categoryData['id'])
                    ->update(['order' => $categoryData['order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Urutan berhasil diupdate.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}