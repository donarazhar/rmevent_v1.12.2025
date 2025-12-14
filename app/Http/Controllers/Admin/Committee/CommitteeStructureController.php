<?php
// app/Http/Controllers/Admin/Committee/CommitteeStructureController.php

namespace App\Http\Controllers\Admin\Committee;

use App\Http\Controllers\Controller;
use App\Models\CommitteeStructure;
use App\Models\CommitteeMember;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CommitteeStructureController extends Controller
{
    /**
     * Display structure tree
     */
    public function index(Request $request)
    {
        $eventId = $request->get('event_id');

        // Get all events for filter
        $events = Event::select('id', 'title')->orderBy('start_datetime', 'desc')->get();

        // Get structure tree
        $structures = CommitteeStructure::with([
            'children' => function ($query) {
                $query->orderBy('order')->with('leader', 'viceLeader', 'activeMembers');
            },
            'leader',
            'viceLeader',
            'activeMembers'
        ])
            ->whereNull('parent_id')
            ->when($eventId, function ($query, $eventId) {
                $query->where('event_id', $eventId);
            })
            ->orderBy('order')
            ->get();

        // Get all users for assignment
        $users = User::select('id', 'name', 'email')
            ->whereIn('role', ['admin', 'panitia'])
            ->orderBy('name')
            ->get();

        // Statistics
        $stats = [
            'total_structures' => CommitteeStructure::when($eventId, function ($q, $eventId) {
                $q->where('event_id', $eventId);
            })->count(),
            'active_structures' => CommitteeStructure::active()
                ->when($eventId, function ($q, $eventId) {
                    $q->where('event_id', $eventId);
                })->count(),
            'total_members' => CommitteeMember::whereHas('structure', function ($q) use ($eventId) {
                if ($eventId) {
                    $q->where('event_id', $eventId);
                }
            })->count(),
            'active_members' => CommitteeMember::active()
                ->whereHas('structure', function ($q) use ($eventId) {
                    if ($eventId) {
                        $q->where('event_id', $eventId);
                    }
                })->count(),
        ];

        return view('admin.committee.structure.index', compact(
            'structures',
            'events',
            'users',
            'stats',
            'eventId'
        ));
    }

    /**
     * Show create form (optional - jika mau halaman terpisah)
     */
    public function create()
    {
        // Get all events
        $events = Event::select('id', 'title')->orderBy('start_datetime', 'desc')->get();

        // Get available parent structures
        $availableParents = CommitteeStructure::orderBy('level')
            ->orderBy('order')
            ->get()
            ->map(function ($item) {
                $item->full_name = $this->getFullName($item);
                return $item;
            });

        // Get all users for assignment
        $users = User::select('id', 'name', 'email')
            ->whereIn('role', ['admin', 'panitia'])
            ->orderBy('name')
            ->get();

        return view('admin.committee.structure.create', compact(
            'events',
            'availableParents',
            'users'
        ));
    }

    /**
     * Store new structure
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'parent_id' => 'nullable|exists:committee_structures,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:committee_structures,code',
            'description' => 'nullable|string',
            'level' => 'required|integer|min:1',
            'order' => 'nullable|integer',
            'leader_id' => 'nullable|exists:users,id',
            'vice_leader_id' => 'nullable|exists:users,id',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive,archived',
            'responsibilities' => 'nullable|array',
            'authorities' => 'nullable|array',
        ]);

        // Auto-generate code if not provided
        if (empty($validated['code'])) {
            $validated['code'] = $this->generateCode($validated['name']);
        }

        // Set order
        if (empty($validated['order'])) {
            $maxOrder = CommitteeStructure::where('parent_id', $validated['parent_id'] ?? null)
                ->where('event_id', $validated['event_id'])
                ->max('order') ?? 0;
            $validated['order'] = $maxOrder + 1;
        }

        DB::beginTransaction();
        try {
            $structure = CommitteeStructure::create($validated);

            // Auto-assign leader and vice leader as members
            if (!empty($validated['leader_id'])) {
                CommitteeMember::create([
                    'structure_id' => $structure->id,
                    'user_id' => $validated['leader_id'],
                    'position' => 'leader',
                    'specific_role' => 'Ketua',
                    'start_date' => now(),
                    'status' => 'active',
                    'assigned_by' => auth()->id(),
                ]);
            }

            if (!empty($validated['vice_leader_id'])) {
                CommitteeMember::create([
                    'structure_id' => $structure->id,
                    'user_id' => $validated['vice_leader_id'],
                    'position' => 'vice_leader',
                    'specific_role' => 'Wakil Ketua',
                    'start_date' => now(),
                    'status' => 'active',
                    'assigned_by' => auth()->id(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Struktur organisasi berhasil ditambahkan',
                'data' => $structure->load('leader', 'viceLeader', 'children')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan struktur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show edit form
     */
    public function edit(CommitteeStructure $structure)
    {
        // Get all events
        $events = Event::select('id', 'title')->orderBy('start_datetime', 'desc')->get();

        // Get available parent structures (exclude self and descendants)
        $availableParents = CommitteeStructure::where('event_id', $structure->event_id)
            ->where('id', '!=', $structure->id)
            ->whereNotIn('id', $this->getDescendantIds($structure))
            ->orderBy('level')
            ->orderBy('order')
            ->get()
            ->map(function ($item) {
                $item->full_name = $this->getFullName($item);
                return $item;
            });

        // Get all users for assignment
        $users = User::select('id', 'name', 'email')
            ->whereIn('role', ['admin', 'panitia'])
            ->orderBy('name')
            ->get();

        return view('admin.committee.structure.edit', compact(
            'structure',
            'events',
            'availableParents',
            'users'
        ));
    }

    /**
     * Update structure
     */
    public function update(Request $request, CommitteeStructure $structure)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'parent_id' => 'nullable|exists:committee_structures,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:committee_structures,code,' . $structure->id,
            'description' => 'nullable|string',
            'level' => 'required|integer|min:1',
            'order' => 'nullable|integer',
            'leader_id' => 'nullable|exists:users,id',
            'vice_leader_id' => 'nullable|exists:users,id',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive,archived',
            'responsibilities' => 'nullable|array',
            'authorities' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            // Update leader membership
            if ($structure->leader_id != ($validated['leader_id'] ?? null)) {
                // Remove old leader
                if ($structure->leader_id) {
                    CommitteeMember::where('structure_id', $structure->id)
                        ->where('user_id', $structure->leader_id)
                        ->where('position', 'leader')
                        ->delete();
                }

                // Add new leader
                if (!empty($validated['leader_id'])) {
                    CommitteeMember::updateOrCreate(
                        [
                            'structure_id' => $structure->id,
                            'user_id' => $validated['leader_id'],
                            'position' => 'leader'
                        ],
                        [
                            'specific_role' => 'Ketua',
                            'start_date' => now(),
                            'status' => 'active',
                            'assigned_by' => auth()->id(),
                        ]
                    );
                }
            }

            // Update vice leader membership
            if ($structure->vice_leader_id != ($validated['vice_leader_id'] ?? null)) {
                // Remove old vice leader
                if ($structure->vice_leader_id) {
                    CommitteeMember::where('structure_id', $structure->id)
                        ->where('user_id', $structure->vice_leader_id)
                        ->where('position', 'vice_leader')
                        ->delete();
                }

                // Add new vice leader
                if (!empty($validated['vice_leader_id'])) {
                    CommitteeMember::updateOrCreate(
                        [
                            'structure_id' => $structure->id,
                            'user_id' => $validated['vice_leader_id'],
                            'position' => 'vice_leader'
                        ],
                        [
                            'specific_role' => 'Wakil Ketua',
                            'start_date' => now(),
                            'status' => 'active',
                            'assigned_by' => auth()->id(),
                        ]
                    );
                }
            }

            $structure->update($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Struktur organisasi berhasil diperbarui',
                'data' => $structure->fresh(['leader', 'viceLeader', 'children'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui struktur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete structure
     */
    public function destroy(CommitteeStructure $structure)
    {
        if ($structure->hasChildren()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus struktur yang memiliki sub-struktur'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Delete all members
            $structure->members()->delete();

            // Delete structure
            $structure->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Struktur organisasi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus struktur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reorder structures
     */
    public function reorder(Request $request)
    {
        $orders = $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:committee_structures,id',
            'orders.*.order' => 'required|integer',
            'orders.*.parent_id' => 'nullable|exists:committee_structures,id',
        ])['orders'];

        DB::beginTransaction();
        try {
            foreach ($orders as $item) {
                CommitteeStructure::where('id', $item['id'])->update([
                    'order' => $item['order'],
                    'parent_id' => $item['parent_id'] ?? null,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Urutan struktur berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui urutan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export structure
     */
    public function export(Request $request)
    {
        $eventId = $request->get('event_id');

        $structures = CommitteeStructure::with([
            'event',
            'parent',
            'leader',
            'viceLeader',
            'members.user'
        ])
            ->when($eventId, function ($query, $eventId) {
                $query->where('event_id', $eventId);
            })
            ->orderBy('level')
            ->orderBy('order')
            ->get();

        // In a real application, use Laravel Excel or similar
        // For now, return as JSON
        return response()->json([
            'success' => true,
            'data' => $structures
        ]);
    }

    // ========================================
    // PRIVATE HELPER METHODS
    // ========================================

    /**
     * Get descendant IDs to prevent circular reference
     */
    private function getDescendantIds(CommitteeStructure $structure): array
    {
        $ids = [];
        $children = $structure->children;

        foreach ($children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->getDescendantIds($child));
        }

        return $ids;
    }

    /**
     * Get full name with hierarchy
     */
    private function getFullName(CommitteeStructure $structure): string
    {
        $names = [$structure->name];
        $parent = $structure->parent;

        while ($parent) {
            array_unshift($names, $parent->name);
            $parent = $parent->parent;
        }

        return implode(' > ', $names);
    }

    /**
     * Generate unique code
     */
    private function generateCode($name)
    {
        $code = strtoupper(Str::slug(Str::limit($name, 20, ''), '-'));
        $original = $code;
        $counter = 1;

        while (CommitteeStructure::where('code', $code)->exists()) {
            $code = $original . '-' . $counter;
            $counter++;
        }

        return $code;
    }
}
