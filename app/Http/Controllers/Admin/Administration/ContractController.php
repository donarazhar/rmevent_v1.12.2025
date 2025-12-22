<?php

namespace App\Http\Controllers\Admin\Administration;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Event;
use App\Models\Sponsorship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ContractController extends Controller
{
    /**
     * Display a listing of contracts
     */
    public function index(Request $request)
    {
        $query = Contract::with(['event', 'sponsorship', 'picInternal', 'createdBy']);

        // Filter by type
        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Filter by event
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Date range filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->periodBetween($request->start_date, $request->end_date);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $contracts = $query->paginate($request->get('per_page', 15))->withQueryString();

        // Statistics
        $stats = [
            'total' => Contract::count(),
            'active' => Contract::active()->count(),
            'pending_signature' => Contract::byStatus(Contract::STATUS_PENDING_SIGNATURE)->count(),
            'expiring_soon' => Contract::expiringSoon()->count(),
            'total_value' => Contract::sum('contract_value'),
        ];

        // Get events for filter
        $events = Event::orderBy('title')->get();

        return view('admin.administrations.contracts.index', compact('contracts', 'stats', 'events'));
    }

    /**
     * Show the form for creating a new contract
     */
    public function create()
    {
        $events = Event::orderBy('title')->get();
        $sponsorships = Sponsorship::orderBy('created_at', 'desc')->get();
        $users = User::where('status', 'active')->orderBy('name')->get();

        return view('admin.administrations.contracts.create', compact('events', 'sponsorships', 'users'));
    }

    /**
     * Store a newly created contract
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'nullable|exists:events,id',
            'sponsorship_id' => 'nullable|exists:sponsorships,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => ['required', Rule::in([
                Contract::TYPE_SPONSORSHIP,
                Contract::TYPE_VENDOR,
                Contract::TYPE_VENUE,
                Contract::TYPE_PARTNERSHIP,
                Contract::TYPE_SERVICE,
                Contract::TYPE_EMPLOYMENT,
                Contract::TYPE_OTHER,
            ])],

            // Party A
            'party_a_name' => 'required|string|max:255',
            'party_a_address' => 'nullable|string',
            'party_a_representative' => 'nullable|string|max:255',

            // Party B
            'party_b_name' => 'required|string|max:255',
            'party_b_address' => 'nullable|string',
            'party_b_representative' => 'nullable|string|max:255',
            'party_b_contact' => 'nullable|string|max:255',
            'party_b_email' => 'nullable|email|max:255',

            // Contract Value
            'contract_value' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:3',

            // Terms
            'terms_and_conditions' => 'nullable|string',
            'scope_of_work' => 'nullable|string',

            // Period
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'auto_renewal' => 'boolean',

            // Status
            'status' => ['required', Rule::in([
                Contract::STATUS_DRAFT,
                Contract::STATUS_PENDING_SIGNATURE,
                Contract::STATUS_SIGNED,
                Contract::STATUS_ACTIVE,
            ])],

            // PIC
            'pic_internal' => 'nullable|exists:users,id',

            // Files
            'contract_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'supporting_documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',

            // Notes
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Upload contract file
            if ($request->hasFile('contract_file')) {
                $validated['contract_file'] = $request->file('contract_file')->store('contracts/files', 'public');
            }

            // Upload supporting documents
            $supportingDocs = [];
            if ($request->hasFile('supporting_documents')) {
                foreach ($request->file('supporting_documents') as $file) {
                    $supportingDocs[] = $file->store('contracts/supporting', 'public');
                }
                $validated['supporting_documents'] = $supportingDocs;
            }

            $validated['created_by'] = auth()->id();

            $contract = Contract::create($validated);

            DB::commit();

            return redirect()
                ->route('admin.contracts.show', $contract)
                ->with('success', 'Kontrak berhasil dibuat dengan kode: ' . $contract->contract_code);
        } catch (\Exception $e) {
            DB::rollBack();

            // Clean up uploaded files
            if (isset($validated['contract_file'])) {
                Storage::disk('public')->delete($validated['contract_file']);
            }
            if (!empty($supportingDocs)) {
                Storage::disk('public')->delete($supportingDocs);
            }

            return back()
                ->withInput()
                ->with('error', 'Gagal membuat kontrak: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified contract
     */
    public function show(Contract $contract)
    {
        $contract->load([
            'event',
            'sponsorship',
            'signedByPartyA',
            'picInternal',
            'createdBy'
        ]);

        return view('admin.administrations.contracts.show', compact('contract'));
    }

    /**
     * Show the form for editing contract
     */
    public function edit(Contract $contract)
    {
        $events = Event::orderBy('title')->get();
        $sponsorships = Sponsorship::orderBy('created_at', 'desc')->get();
        $users = User::where('status', 'active')->orderBy('name')->get();

        return view('admin.administrations.contracts.edit', compact('contract', 'events', 'sponsorships', 'users'));
    }

    /**
     * Update the specified contract
     */
    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'event_id' => 'nullable|exists:events,id',
            'sponsorship_id' => 'nullable|exists:sponsorships,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => ['required', Rule::in([
                Contract::TYPE_SPONSORSHIP,
                Contract::TYPE_VENDOR,
                Contract::TYPE_VENUE,
                Contract::TYPE_PARTNERSHIP,
                Contract::TYPE_SERVICE,
                Contract::TYPE_EMPLOYMENT,
                Contract::TYPE_OTHER,
            ])],

            // Party A
            'party_a_name' => 'required|string|max:255',
            'party_a_address' => 'nullable|string',
            'party_a_representative' => 'nullable|string|max:255',

            // Party B
            'party_b_name' => 'required|string|max:255',
            'party_b_address' => 'nullable|string',
            'party_b_representative' => 'nullable|string|max:255',
            'party_b_contact' => 'nullable|string|max:255',
            'party_b_email' => 'nullable|email|max:255',

            // Contract Value
            'contract_value' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:3',

            // Terms
            'terms_and_conditions' => 'nullable|string',
            'scope_of_work' => 'nullable|string',

            // Period
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'auto_renewal' => 'boolean',

            // Status
            'status' => ['required', Rule::in([
                Contract::STATUS_DRAFT,
                Contract::STATUS_PENDING_SIGNATURE,
                Contract::STATUS_SIGNED,
                Contract::STATUS_ACTIVE,
                Contract::STATUS_COMPLETED,
                Contract::STATUS_TERMINATED,
                Contract::STATUS_EXPIRED,
            ])],

            // PIC
            'pic_internal' => 'nullable|exists:users,id',

            // Files
            'contract_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'supporting_documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'remove_contract_file' => 'boolean',

            // Notes
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Handle contract file removal
            if ($request->boolean('remove_contract_file') && $contract->contract_file) {
                Storage::disk('public')->delete($contract->contract_file);
                $validated['contract_file'] = null;
            }

            // Upload new contract file
            if ($request->hasFile('contract_file')) {
                // Delete old file
                if ($contract->contract_file) {
                    Storage::disk('public')->delete($contract->contract_file);
                }
                $validated['contract_file'] = $request->file('contract_file')->store('contracts/files', 'public');
            }

            // Upload new supporting documents
            if ($request->hasFile('supporting_documents')) {
                $supportingDocs = $contract->supporting_documents ?? [];
                foreach ($request->file('supporting_documents') as $file) {
                    $supportingDocs[] = $file->store('contracts/supporting', 'public');
                }
                $validated['supporting_documents'] = $supportingDocs;
            }

            $contract->update($validated);

            DB::commit();

            return redirect()
                ->route('admin.contracts.show', $contract)
                ->with('success', 'Kontrak berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui kontrak: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified contract
     */
    public function destroy(Contract $contract)
    {
        // Only allow deletion of draft contracts
        if (!$contract->isDraft()) {
            return back()->with('error', 'Hanya kontrak dengan status Draft yang dapat dihapus');
        }

        try {
            $contract->delete();

            return redirect()
                ->route('admin.contracts.index')
                ->with('success', 'Kontrak berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus kontrak: ' . $e->getMessage());
        }
    }

    /**
     * Sign contract
     */
    public function sign(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'party' => 'required|in:party_a,party_b',
            'signature_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'signed_date' => 'required|date',
        ]);

        try {
            if ($validated['party'] === 'party_a') {
                // Party A signature (internal)
                $data = [
                    'signed_by_party_a' => auth()->id(),
                    'signed_at_party_a' => $validated['signed_date'],
                ];

                if ($request->hasFile('signature_file')) {
                    if ($contract->signature_file_party_a) {
                        Storage::disk('public')->delete($contract->signature_file_party_a);
                    }
                    $data['signature_file_party_a'] = $request->file('signature_file')
                        ->store('contracts/signatures', 'public');
                }

                $contract->update($data);
            } else {
                // Party B signature (external)
                $data = [
                    'signed_at_party_b' => $validated['signed_date'],
                ];

                if ($request->hasFile('signature_file')) {
                    if ($contract->signature_file_party_b) {
                        Storage::disk('public')->delete($contract->signature_file_party_b);
                    }
                    $data['signature_file_party_b'] = $request->file('signature_file')
                        ->store('contracts/signatures', 'public');
                }

                $contract->update($data);
            }

            // Check if both parties signed and update status
            if ($contract->isBothPartiesSigned() && $contract->status === Contract::STATUS_PENDING_SIGNATURE) {
                $contract->markAsSigned();
            }

            return back()->with('success', 'Tanda tangan berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan tanda tangan: ' . $e->getMessage());
        }
    }

    /**
     * Renew contract
     */
    public function renew(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'start_date' => 'required|date|after:' . $contract->end_date->format('Y-m-d'),
            'end_date' => 'required|date|after:start_date',
            'contract_value' => 'nullable|numeric|min:0',
        ]);

        try {
            $newContract = $contract->renew($validated);

            return redirect()
                ->route('admin.contracts.show', $newContract)
                ->with('success', 'Kontrak berhasil diperpanjang dengan kode: ' . $newContract->contract_code);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperpanjang kontrak: ' . $e->getMessage());
        }
    }

    /**
     * Terminate contract
     */
    public function terminate(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'termination_date' => 'required|date',
            'termination_reason' => 'required|string',
        ]);

        try {
            $contract->terminate($validated['termination_date'], $validated['termination_reason']);

            return back()->with('success', 'Kontrak berhasil diakhiri');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengakhiri kontrak: ' . $e->getMessage());
        }
    }

    /**
     * Download contract file
     */
    public function download(Contract $contract)
    {
        if (!$contract->contract_file || !Storage::disk('public')->exists($contract->contract_file)) {
            return back()->with('error', 'File kontrak tidak ditemukan');
        }

        return Storage::disk('public')->download(
            $contract->contract_file,
            $contract->contract_code . '_' . $contract->title . '.pdf'
        );
    }

    /**
     * Show expiring soon contracts
     */
    public function expiringSoon(Request $request)
    {
        $days = $request->get('days', 30);

        $contracts = Contract::expiringSoon($days)
            ->with(['event', 'picInternal'])
            ->orderBy('end_date')
            ->get();

        return view('admin.administrations.contracts.expiring-soon', compact('contracts', 'days'));
    }
}
