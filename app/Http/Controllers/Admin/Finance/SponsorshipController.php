<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Sponsorship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SponsorshipController extends Controller
{
    /**
     * Display a listing of sponsorships.
     */
    public function index(Request $request)
    {
        $query = Sponsorship::with(['event', 'picInternal']);

        // Filter by event
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        // Filter by tier
        if ($request->filled('tier')) {
            $query->where('tier', $request->tier);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('sponsor_code', 'like', "%{$search}%")
                    ->orWhere('contact_person', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $sponsorships = $query->paginate(15)->withQueryString();

        // Summary statistics
        $stats = [
            'total_count' => Sponsorship::count(),
            'total_committed' => Sponsorship::sum('committed_amount'),
            'total_received' => Sponsorship::sum('received_amount'),
            'total_outstanding' => Sponsorship::sum('outstanding_amount'),
            'prospecting' => Sponsorship::where('status', 'prospecting')->count(),
            'confirmed' => Sponsorship::whereIn('status', ['confirmed', 'delivered', 'completed'])->count(),
            'cancelled' => Sponsorship::where('status', 'cancelled')->count(),
        ];

        $events = Event::orderBy('title')->get();

        return view('admin.finance.sponsorships.index', compact('sponsorships', 'stats', 'events'));
    }

    /**
     * Show the form for creating a new sponsorship.
     */
    public function create()
    {
        $events = Event::orderBy('title')->get();
        $users = User::orderBy('name')->get();

        // Generate sponsor code
        $lastSponsorship = Sponsorship::latest('id')->first();
        $nextNumber = $lastSponsorship ? (intval(substr($lastSponsorship->sponsor_code, -3)) + 1) : 1;
        $sponsorCode = 'SPR-' . date('Y') . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return view('admin.finance.sponsorships.create', compact('events', 'users', 'sponsorCode'));
    }

    /**
     * Store a newly created sponsorship in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'sponsor_code' => 'required|unique:sponsorships,sponsor_code',
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'tier' => 'required|in:platinum,gold,silver,bronze',
            'type' => 'required|in:cash,in_kind,mixed',
            'committed_amount' => 'required|numeric|min:0',
            'in_kind_description' => 'nullable|string',
            'in_kind_value' => 'nullable|numeric|min:0',
            'benefits_package' => 'nullable|array',
            'logo_placements' => 'nullable|array',
            'deliverables' => 'nullable|array',
            'status' => 'required|in:prospecting,negotiating,committed,confirmed,delivered,completed,cancelled',
            'proposal_sent_date' => 'nullable|date',
            'commitment_date' => 'nullable|date',
            'contract_date' => 'nullable|date',
            'fulfillment_date' => 'nullable|date',
            'payment_schedule' => 'nullable|array',
            'proposal_document' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'contract_document' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'notes' => 'nullable|string',
            'internal_notes' => 'nullable|string',
            'pic_internal' => 'nullable|exists:users,id',
        ]);

        // Handle file uploads
        if ($request->hasFile('proposal_document')) {
            $validated['proposal_document'] = $request->file('proposal_document')->store('sponsorships/proposals', 'public');
        }

        if ($request->hasFile('contract_document')) {
            $validated['contract_document'] = $request->file('contract_document')->store('sponsorships/contracts', 'public');
        }

        // Calculate outstanding amount
        $validated['outstanding_amount'] = $validated['committed_amount'];
        $validated['received_amount'] = 0;
        $validated['created_by'] = Auth::id();

        Sponsorship::create($validated);

        return redirect()->route('admin.sponsorships.index')
            ->with('success', 'Sponsorship berhasil ditambahkan!');
    }

    /**
     * Display the specified sponsorship.
     */
    public function show(Sponsorship $sponsorship)
    {
        $sponsorship->load(['event', 'picInternal', 'creator', 'incomes.createdBy']);

        return view('admin.finance.sponsorships.show', compact('sponsorship'));
    }

    /**
     * Show the form for editing the specified sponsorship.
     */
    public function edit(Sponsorship $sponsorship)
    {
        $events = Event::orderBy('title')->get();
        $users = User::orderBy('name')->get();

        return view('admin.finance.sponsorships.edit', compact('sponsorship', 'events', 'users'));
    }

    /**
     * Update the specified sponsorship in storage.
     */
    public function update(Request $request, Sponsorship $sponsorship)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'tier' => 'required|in:platinum,gold,silver,bronze',
            'type' => 'required|in:cash,in_kind,mixed',
            'committed_amount' => 'required|numeric|min:0',
            'in_kind_description' => 'nullable|string',
            'in_kind_value' => 'nullable|numeric|min:0',
            'benefits_package' => 'nullable|array',
            'logo_placements' => 'nullable|array',
            'deliverables' => 'nullable|array',
            'status' => 'required|in:prospecting,negotiating,committed,confirmed,delivered,completed,cancelled',
            'proposal_sent_date' => 'nullable|date',
            'commitment_date' => 'nullable|date',
            'contract_date' => 'nullable|date',
            'fulfillment_date' => 'nullable|date',
            'payment_schedule' => 'nullable|array',
            'proposal_document' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'contract_document' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'notes' => 'nullable|string',
            'internal_notes' => 'nullable|string',
            'pic_internal' => 'nullable|exists:users,id',
        ]);

        // Handle file uploads
        if ($request->hasFile('proposal_document')) {
            if ($sponsorship->proposal_document) {
                Storage::disk('public')->delete($sponsorship->proposal_document);
            }
            $validated['proposal_document'] = $request->file('proposal_document')->store('sponsorships/proposals', 'public');
        }

        if ($request->hasFile('contract_document')) {
            if ($sponsorship->contract_document) {
                Storage::disk('public')->delete($sponsorship->contract_document);
            }
            $validated['contract_document'] = $request->file('contract_document')->store('sponsorships/contracts', 'public');
        }

        // Recalculate outstanding amount if committed amount changed
        if ($validated['committed_amount'] != $sponsorship->committed_amount) {
            $validated['outstanding_amount'] = $validated['committed_amount'] - $sponsorship->received_amount;
        }

        $sponsorship->update($validated);

        return redirect()->route('admin.sponsorships.index')
            ->with('success', 'Sponsorship berhasil diperbarui!');
    }

    /**
     * Remove the specified sponsorship from storage.
     */
    public function destroy(Sponsorship $sponsorship)
    {
        // Delete associated files
        if ($sponsorship->proposal_document) {
            Storage::disk('public')->delete($sponsorship->proposal_document);
        }
        if ($sponsorship->contract_document) {
            Storage::disk('public')->delete($sponsorship->contract_document);
        }

        $sponsorship->delete();

        return redirect()->route('admin.sponsorships.index')
            ->with('success', 'Sponsorship berhasil dihapus!');
    }

    /**
     * Confirm sponsorship commitment.
     */
    public function confirm(Sponsorship $sponsorship)
    {
        $sponsorship->confirm();

        return back()->with('success', 'Sponsorship berhasil dikonfirmasi!');
    }

    /**
     * Cancel sponsorship.
     */
    public function cancel(Request $request, Sponsorship $sponsorship)
    {
        $reason = $request->input('reason', 'No reason provided');
        $sponsorship->cancel($reason);

        return back()->with('success', 'Sponsorship berhasil dibatalkan!');
    }

    /**
     * Generate invoice for sponsorship.
     */
    public function generateInvoice(Sponsorship $sponsorship)
    {
        // This is a placeholder - implement PDF generation logic
        // You can use packages like barryvdh/laravel-dompdf or similar

        return back()->with('info', 'Invoice generation feature coming soon!');
    }

    /**
     * Generate receipt for sponsorship.
     */
    public function generateReceipt(Sponsorship $sponsorship)
    {
        // This is a placeholder - implement PDF generation logic

        return back()->with('info', 'Receipt generation feature coming soon!');
    }

    /**
     * Export sponsorships data.
     */
    public function export(Request $request)
    {
        // This is a placeholder - implement export logic (Excel/CSV)
        // You can use packages like maatwebsite/excel or similar

        return back()->with('info', 'Export feature coming soon!');
    }
}
