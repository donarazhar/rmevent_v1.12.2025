<?php

namespace App\Http\Controllers\Admin\Knowledge;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentFolder;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::with(['folder', 'uploadedBy', 'event'])
            ->visibleTo(Auth::id());

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by folder
        if ($request->filled('folder_id')) {
            $query->where('folder_id', $request->folder_id);
        }

        // Filter by event
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('document_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('document_date', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $documents = $query->paginate(20)->withQueryString();

        // Get folders for filter
        $folders = DocumentFolder::topLevel()
            ->accessibleBy(Auth::id())
            ->get();

        // Get events for filter
        $events = Event::select('id', 'title')->get();

        // Statistics
        $stats = [
            'total' => Document::visibleTo(Auth::id())->count(),
            'by_category' => Document::visibleTo(Auth::id())
                ->select('category', DB::raw('count(*) as total'))
                ->groupBy('category')
                ->pluck('total', 'category'),
            'total_size' => Document::visibleTo(Auth::id())->sum('file_size'),
            'recent_uploads' => Document::visibleTo(Auth::id())
                ->whereDate('created_at', '>=', now()->subDays(7))
                ->count(),
        ];

        return view('admin.knowledge.documents.index', compact('documents', 'folders', 'events', 'stats'));
    }

    public function byFolder(DocumentFolder $folder)
    {
        // Check access
        if (!$folder->canAccess(Auth::id())) {
            abort(403, 'Anda tidak memiliki akses ke folder ini.');
        }

        $documents = Document::where('folder_id', $folder->id)
            ->visibleTo(Auth::id())
            ->with(['uploadedBy', 'event'])
            ->latest()
            ->paginate(20);

        $subfolders = $folder->children()
            ->accessibleBy(Auth::id())
            ->get();

        $breadcrumbs = $this->getFolderBreadcrumbs($folder);

        return view('admin.knowledge.documents.by-folder', compact('folder', 'documents', 'subfolders', 'breadcrumbs'));
    }

    public function create(Request $request)
    {
        $folders = DocumentFolder::topLevel()
            ->accessibleBy(Auth::id())
            ->get();

        $events = Event::select('id', 'title')->get();

        $categories = [
            Document::CATEGORY_PROPOSAL => 'Proposal',
            Document::CATEGORY_REPORT => 'Laporan',
            Document::CATEGORY_MEETING_NOTES => 'Notulen Rapat',
            Document::CATEGORY_CONTRACT => 'Kontrak',
            Document::CATEGORY_LETTER => 'Surat',
            Document::CATEGORY_CERTIFICATE => 'Sertifikat',
            Document::CATEGORY_PRESENTATION => 'Presentasi',
            Document::CATEGORY_PHOTO => 'Foto',
            Document::CATEGORY_VIDEO => 'Video',
            Document::CATEGORY_OTHER => 'Lainnya',
        ];

        $selectedFolder = $request->get('folder_id');
        $selectedEvent = $request->get('event_id');

        return view('admin.knowledge.documents.create', compact('folders', 'events', 'categories', 'selectedFolder', 'selectedEvent'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:' . implode(',', [
                Document::CATEGORY_PROPOSAL,
                Document::CATEGORY_REPORT,
                Document::CATEGORY_MEETING_NOTES,
                Document::CATEGORY_CONTRACT,
                Document::CATEGORY_LETTER,
                Document::CATEGORY_CERTIFICATE,
                Document::CATEGORY_PRESENTATION,
                Document::CATEGORY_PHOTO,
                Document::CATEGORY_VIDEO,
                Document::CATEGORY_OTHER,
            ]),
            'folder_id' => 'nullable|exists:document_folders,id',
            'event_id' => 'nullable|exists:events,id',
            'file' => 'required|file|max:51200', // Max 50MB
            'document_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:document_date',
            'visibility' => 'required|in:public,private,restricted',
            'status' => 'required|in:draft,final,archived',
            'tags' => 'nullable|string',
            'allow_download' => 'boolean',
            'allow_print' => 'boolean',
            'shared_with_users' => 'nullable|array',
            'shared_with_users.*' => 'exists:users,id',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Handle file upload
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $fileName, 'public');

            // Create document
            $document = Document::create([
                'folder_id' => $validated['folder_id'],
                'event_id' => $validated['event_id'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'category' => $validated['category'],
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'document_date' => $validated['document_date'],
                'expiry_date' => $validated['expiry_date'],
                'visibility' => $validated['visibility'],
                'status' => $validated['status'],
                'allow_download' => $request->boolean('allow_download', true),
                'allow_print' => $request->boolean('allow_print', true),
                'shared_with_users' => $validated['shared_with_users'] ?? null,
                'notes' => $validated['notes'],
                'uploaded_by' => Auth::id(),
            ]);

            // Add tags
            if ($request->filled('tags')) {
                $tags = array_map('trim', explode(',', $request->tags));
                $document->update(['tags' => $tags]);
            }

            DB::commit();

            return redirect()->route('admin.documents.show', $document)
                ->with('success', 'Dokumen berhasil diunggah.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded file if exists
            if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat mengunggah dokumen: ' . $e->getMessage());
        }
    }

    public function show(Document $document)
    {
        // Check access
        if (!$document->canBeViewedBy(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }

        $document->load(['folder', 'uploadedBy', 'event', 'versions.uploadedBy', 'parentDocument']);

        // Increment view count
        $document->incrementViewCount();

        // Get related documents (same category & folder)
        $relatedDocuments = Document::where('id', '!=', $document->id)
            ->where('category', $document->category)
            ->where('folder_id', $document->folder_id)
            ->visibleTo(Auth::id())
            ->limit(5)
            ->get();

        return view('admin.knowledge.documents.show', compact('document', 'relatedDocuments'));
    }

    public function edit(Document $document)
    {
        // Check if user can edit (owner or admin)
        if ($document->uploaded_by !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit dokumen ini.');
        }

        $folders = DocumentFolder::topLevel()
            ->accessibleBy(Auth::id())
            ->get();

        $events = Event::select('id', 'title')->get();

        $categories = [
            Document::CATEGORY_PROPOSAL => 'Proposal',
            Document::CATEGORY_REPORT => 'Laporan',
            Document::CATEGORY_MEETING_NOTES => 'Notulen Rapat',
            Document::CATEGORY_CONTRACT => 'Kontrak',
            Document::CATEGORY_LETTER => 'Surat',
            Document::CATEGORY_CERTIFICATE => 'Sertifikat',
            Document::CATEGORY_PRESENTATION => 'Presentasi',
            Document::CATEGORY_PHOTO => 'Foto',
            Document::CATEGORY_VIDEO => 'Video',
            Document::CATEGORY_OTHER => 'Lainnya',
        ];

        return view('admin.knowledge.documents.edit', compact('document', 'folders', 'events', 'categories'));
    }

    public function update(Request $request, Document $document)
    {
        // Check if user can edit
        if ($document->uploaded_by !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit dokumen ini.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:' . implode(',', [
                Document::CATEGORY_PROPOSAL,
                Document::CATEGORY_REPORT,
                Document::CATEGORY_MEETING_NOTES,
                Document::CATEGORY_CONTRACT,
                Document::CATEGORY_LETTER,
                Document::CATEGORY_CERTIFICATE,
                Document::CATEGORY_PRESENTATION,
                Document::CATEGORY_PHOTO,
                Document::CATEGORY_VIDEO,
                Document::CATEGORY_OTHER,
            ]),
            'folder_id' => 'nullable|exists:document_folders,id',
            'event_id' => 'nullable|exists:events,id',
            'document_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:document_date',
            'visibility' => 'required|in:public,private,restricted',
            'status' => 'required|in:draft,final,archived',
            'tags' => 'nullable|string',
            'allow_download' => 'boolean',
            'allow_print' => 'boolean',
            'shared_with_users' => 'nullable|array',
            'shared_with_users.*' => 'exists:users,id',
            'notes' => 'nullable|string',
        ]);

        try {
            $document->update([
                'folder_id' => $validated['folder_id'],
                'event_id' => $validated['event_id'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'category' => $validated['category'],
                'document_date' => $validated['document_date'],
                'expiry_date' => $validated['expiry_date'],
                'visibility' => $validated['visibility'],
                'status' => $validated['status'],
                'allow_download' => $request->boolean('allow_download', true),
                'allow_print' => $request->boolean('allow_print', true),
                'shared_with_users' => $validated['shared_with_users'] ?? null,
                'notes' => $validated['notes'],
            ]);

            // Update tags
            if ($request->filled('tags')) {
                $tags = array_map('trim', explode(',', $request->tags));
                $document->update(['tags' => $tags]);
            } else {
                $document->update(['tags' => null]);
            }

            return redirect()->route('admin.documents.show', $document)
                ->with('success', 'Dokumen berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui dokumen: ' . $e->getMessage());
        }
    }

    public function destroy(Document $document)
    {
        // Check if user can delete
        if ($document->uploaded_by !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus dokumen ini.');
        }

        try {
            // Delete versions first
            foreach ($document->versions as $version) {
                if (Storage::disk('public')->exists($version->file_path)) {
                    Storage::disk('public')->delete($version->file_path);
                }
                $version->forceDelete();
            }

            // Delete main document
            $document->delete();

            return redirect()->route('admin.documents.index')
                ->with('success', 'Dokumen berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus dokumen: ' . $e->getMessage());
        }
    }

    public function download(Document $document)
    {
        // Check access
        if (!$document->canBeDownloadedBy(Auth::user())) {
            abort(403, 'Anda tidak memiliki izin untuk mengunduh dokumen ini.');
        }

        // Increment download count
        $document->incrementDownloadCount();

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    public function upload(Request $request, Document $document)
    {
        // Check if user can upload new version
        if ($document->uploaded_by !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403, 'Anda tidak memiliki izin untuk mengunggah versi baru.');
        }

        $request->validate([
            'file' => 'required|file|max:51200',
            'version_notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $fileName, 'public');

            $newVersion = $document->createNewVersion([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'version_notes' => $request->version_notes,
                'uploaded_by' => Auth::id(),
            ]);

            DB::commit();

            return back()->with('success', 'Versi baru berhasil diunggah: v' . $newVersion->version);
        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function share(Request $request, Document $document)
    {
        // Check if user can share
        if ($document->uploaded_by !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403, 'Anda tidak memiliki izin untuk membagikan dokumen ini.');
        }

        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        try {
            $document->update([
                'shared_with_users' => $request->user_ids,
            ]);

            return back()->with('success', 'Dokumen berhasil dibagikan ke ' . count($request->user_ids) . ' pengguna.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function createFolder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:document_folders,id',
            'event_id' => 'nullable|exists:events,id',
            'visibility' => 'required|in:public,private,restricted',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
        ]);

        try {
            DB::beginTransaction();

            $parentFolder = $request->parent_id ? DocumentFolder::find($request->parent_id) : null;

            $folder = DocumentFolder::create([
                'parent_id' => $request->parent_id,
                'event_id' => $request->event_id,
                'name' => $request->name,
                'description' => $request->description,
                'path' => $parentFolder ? $parentFolder->path . '/' . $request->name : '/' . $request->name,
                'level' => $parentFolder ? $parentFolder->level + 1 : 1,
                'visibility' => $request->visibility,
                'color' => $request->color ?? '#6B7280',
                'icon' => $request->icon ?? 'folder',
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return back()->with('success', 'Folder berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function getFolderBreadcrumbs(DocumentFolder $folder)
    {
        $breadcrumbs = collect([$folder]);
        $current = $folder;

        while ($current->parent) {
            $current = $current->parent;
            $breadcrumbs->prepend($current);
        }

        return $breadcrumbs;
    }
}
