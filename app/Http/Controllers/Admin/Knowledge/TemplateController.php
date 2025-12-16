<?php

namespace App\Http\Controllers\Admin\Knowledge;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    /**
     * Display a listing of templates.
     */
    public function index(Request $request)
    {
        $query = Template::with('creator');

        // Filter by category
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Filter by file type
        if ($request->filled('file_type')) {
            $query->byFileType($request->file_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $templates = $query->paginate(15)->withQueryString();

        // Summary statistics
        $stats = [
            'total_count' => Template::count(),
            'active_count' => Template::active()->count(),
            'inactive_count' => Template::where('status', 'inactive')->count(),
            'total_downloads' => Template::sum('download_count'),
            'total_usage' => Template::sum('usage_count'),
        ];

        // Popular templates
        $popularTemplates = Template::active()->popular(5)->get();

        // Category breakdown
        $categoryBreakdown = Template::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category');

        return view('admin.knowledge.templates.index', compact(
            'templates',
            'stats',
            'popularTemplates',
            'categoryBreakdown'
        ));
    }

    /**
     * Show the form for creating a new template.
     */
    public function create()
    {
        // Generate template code
        $lastTemplate = Template::latest('id')->first();
        $nextNumber = $lastTemplate ? (intval(substr($lastTemplate->template_code, 4)) + 1) : 1;
        $templateCode = 'TPL-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return view('admin.knowledge.templates.create', compact('templateCode'));
    }

    /**
     * Store a newly created template in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'template_code' => 'required|unique:templates,template_code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:document,form,presentation,spreadsheet,email,report,certificate,letter,other',
            'file' => 'required|file|max:20480', // 20MB max
            'usage_instructions' => 'nullable|string',
            'variables' => 'nullable|string',
            'tags' => 'nullable|string',
            'preview_image' => 'nullable|image|max:5120', // 5MB max
            'preview_description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('templates/files', $fileName, 'public');

            $validated['file_path'] = $filePath;
            $validated['file_size'] = $file->getSize();
            $validated['file_type'] = $file->getClientOriginalExtension();
        }

        // Handle preview image
        if ($request->hasFile('preview_image')) {
            $previewImage = $request->file('preview_image');
            $previewFileName = time() . '_preview_' . $previewImage->getClientOriginalName();
            $previewPath = $previewImage->storeAs('templates/previews', $previewFileName, 'public');
            $validated['preview_image'] = $previewPath;
        }

        // Parse variables and tags
        $validated['variables'] = $request->variables
            ? json_encode(array_map('trim', explode(',', $request->variables)))
            : null;

        $validated['tags'] = $request->tags
            ? json_encode(array_map('trim', explode(',', $request->tags)))
            : null;

        $validated['created_by'] = Auth::id();
        $validated['download_count'] = 0;
        $validated['usage_count'] = 0;

        Template::create($validated);

        return redirect()->route('admin.templates.index')
            ->with('success', 'Template berhasil dibuat!');
    }

    /**
     * Display the specified template.
     */
    public function show(Template $template)
    {
        $template->load('creator');

        return view('admin.knowledge.templates.show', compact('template'));
    }

    /**
     * Show the form for editing the specified template.
     */
    public function edit(Template $template)
    {
        return view('admin.knowledge.templates.edit', compact('template'));
    }

    /**
     * Update the specified template in storage.
     */
    public function update(Request $request, Template $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:document,form,presentation,spreadsheet,email,report,certificate,letter,other',
            'file' => 'nullable|file|max:20480',
            'usage_instructions' => 'nullable|string',
            'variables' => 'nullable|string',
            'tags' => 'nullable|string',
            'preview_image' => 'nullable|image|max:5120',
            'preview_description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        // Handle file replacement
        if ($request->hasFile('file')) {
            // Delete old file
            if ($template->file_path) {
                Storage::disk('public')->delete($template->file_path);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('templates/files', $fileName, 'public');

            $validated['file_path'] = $filePath;
            $validated['file_size'] = $file->getSize();
            $validated['file_type'] = $file->getClientOriginalExtension();
        }

        // Handle preview image replacement
        if ($request->hasFile('preview_image')) {
            // Delete old preview
            if ($template->preview_image) {
                Storage::disk('public')->delete($template->preview_image);
            }

            $previewImage = $request->file('preview_image');
            $previewFileName = time() . '_preview_' . $previewImage->getClientOriginalName();
            $previewPath = $previewImage->storeAs('templates/previews', $previewFileName, 'public');
            $validated['preview_image'] = $previewPath;
        }

        // Parse variables and tags
        if ($request->filled('variables')) {
            $validated['variables'] = json_encode(array_map('trim', explode(',', $request->variables)));
        }

        if ($request->filled('tags')) {
            $validated['tags'] = json_encode(array_map('trim', explode(',', $request->tags)));
        }

        $template->update($validated);

        return redirect()->route('admin.templates.index')
            ->with('success', 'Template berhasil diupdate!');
    }

    /**
     * Remove the specified template from storage.
     */
    public function destroy(Template $template)
    {
        // Delete associated files
        if ($template->file_path) {
            Storage::disk('public')->delete($template->file_path);
        }
        if ($template->preview_image) {
            Storage::disk('public')->delete($template->preview_image);
        }

        $template->delete();

        return redirect()->route('admin.templates.index')
            ->with('success', 'Template berhasil dihapus!');
    }

    /**
     * Download template file.
     */
    public function download(Template $template)
    {
        if (!$template->file_path || !Storage::disk('public')->exists($template->file_path)) {
            return back()->with('error', 'File template tidak ditemukan!');
        }

        // Increment download count
        $template->incrementDownloadCount();

        $filePath = Storage::disk('public')->path($template->file_path);
        $fileName = $template->name . '.' . $template->file_type;

        return response()->download($filePath, $fileName);
    }

    /**
     * Duplicate template.
     */
    public function duplicate(Template $template)
    {
        try {
            $newTemplate = $template->duplicate(Auth::id());

            // Copy file if exists
            if ($template->file_path && Storage::disk('public')->exists($template->file_path)) {
                $extension = pathinfo($template->file_path, PATHINFO_EXTENSION);
                $newFileName = time() . '_copy_' . basename($template->file_path);
                $newFilePath = 'templates/files/' . $newFileName;

                Storage::disk('public')->copy($template->file_path, $newFilePath);
                $newTemplate->file_path = $newFilePath;
                $newTemplate->save();
            }

            return redirect()->route('admin.templates.edit', $newTemplate)
                ->with('success', 'Template berhasil diduplikasi! Silakan edit sesuai kebutuhan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menduplikasi template: ' . $e->getMessage());
        }
    }

    /**
     * Show templates by category.
     */
    public function byCategory($category)
    {
        $templates = Template::with('creator')
            ->byCategory($category)
            ->active()
            ->paginate(15);

        $stats = [
            'total_count' => Template::byCategory($category)->count(),
            'active_count' => Template::byCategory($category)->active()->count(),
        ];

        return view('admin.knowledge.templates.by-category', compact('templates', 'category', 'stats'));
    }
}
