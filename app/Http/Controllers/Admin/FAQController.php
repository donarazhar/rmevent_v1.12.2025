<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FAQRequest;
use App\Models\FAQ;
use App\Models\Category;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    public function index(Request $request)
    {
        $query = FAQ::with('category');

        // Search
        if ($request->search) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->category_id) {
            $query->byCategory($request->category_id);
        }

        // Filter by status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $faqs = $query->ordered()->paginate(20);

        // Get categories for filter
        $categories = Category::ofType(Category::TYPE_GENERAL)
            ->where('slug', 'like', 'faq%')
            ->ordered()
            ->get();

        return view('admin.faqs.index', compact('faqs', 'categories'));
    }

    public function create()
    {
        $categories = Category::active()
            ->ofType(Category::TYPE_GENERAL)
            ->where('slug', 'like', 'faq%')
            ->ordered()
            ->get();

        return view('admin.faqs.create', compact('categories'));
    }

    public function store(FAQRequest $request)
    {
        try {
            $data = $request->validated();
            FAQ::create($data);

            return redirect()
                ->route('admin.faqs.index')
                ->with('success', 'FAQ berhasil dibuat.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(FAQ $faq)
    {
        $categories = Category::active()
            ->ofType(Category::TYPE_GENERAL)
            ->where('slug', 'like', 'faq%')
            ->ordered()
            ->get();

        return view('admin.faqs.edit', compact('faq', 'categories'));
    }

    public function update(FAQRequest $request, FAQ $faq)
    {
        try {
            $data = $request->validated();
            $faq->update($data);

            return redirect()
                ->route('admin.faqs.index')
                ->with('success', 'FAQ berhasil diupdate.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(FAQ $faq)
    {
        try {
            $faq->delete();

            return redirect()
                ->route('admin.faqs.index')
                ->with('success', 'FAQ berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Toggle FAQ status
     */
    public function toggleStatus(FAQ $faq)
    {
        $faq->update(['is_active' => !$faq->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $faq->is_active,
            'message' => 'Status berhasil diupdate.'
        ]);
    }

    /**
     * Reorder FAQs
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'faqs' => 'required|array',
            'faqs.*.id' => 'required|exists:faqs,id',
            'faqs.*.order' => 'required|integer',
        ]);

        try {
            foreach ($request->faqs as $faqData) {
                FAQ::where('id', $faqData['id'])
                    ->update(['order' => $faqData['order']]);
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