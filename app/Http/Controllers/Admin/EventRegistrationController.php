<?php
// app/Http/Controllers/Admin/EventRegistrationController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventRegistration;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventRegistrationController extends Controller
{
    /**
     * Display registrations listing
     */
    public function index(Request $request)
    {
        $query = EventRegistration::with(['event', 'user']);

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%")
                  ->orWhere('registration_code', 'like', "%{$request->search}%");
            });
        }

        // Filter by event
        if ($request->event_id) {
            $query->where('event_id', $request->event_id);
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by date range
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $registrations = $query->paginate(20);

        // Get events for filter
        $events = Event::orderBy('start_datetime', 'desc')->get();

        // Statistics
        $stats = [
            'total' => EventRegistration::count(),
            'pending' => EventRegistration::pending()->count(),
            'confirmed' => EventRegistration::confirmed()->count(),
            'attended' => EventRegistration::attended()->count(),
            'cancelled' => EventRegistration::where('status', 'cancelled')->count(),
        ];

        return view('admin.registrations.index', compact('registrations', 'events', 'stats'));
    }

    /**
     * Show registration detail
     */
    public function show(EventRegistration $registration)
    {
        $registration->load(['event', 'user', 'feedback']);

        return view('admin.registrations.show', compact('registration'));
    }

    /**
     * Confirm registration
     */
    public function confirm(EventRegistration $registration)
    {
        if ($registration->isConfirmed()) {
            return redirect()
                ->back()
                ->with('info', 'Pendaftaran sudah dikonfirmasi sebelumnya.');
        }

        try {
            $registration->confirm();

            // Send notification email
            // $registration->notify(new RegistrationConfirmed());

            return redirect()
                ->back()
                ->with('success', 'Pendaftaran berhasil dikonfirmasi.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Check-in registration
     */
    public function checkIn(EventRegistration $registration)
    {
        if ($registration->isAttended()) {
            return redirect()
                ->back()
                ->with('info', 'Peserta sudah check-in sebelumnya.');
        }

        try {
            $registration->checkIn(auth()->user()->name);

            return redirect()
                ->back()
                ->with('success', 'Check-in berhasil.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Cancel registration
     */
    public function cancel(EventRegistration $registration)
    {
        if ($registration->isCancelled()) {
            return redirect()
                ->back()
                ->with('info', 'Pendaftaran sudah dibatalkan sebelumnya.');
        }

        DB::beginTransaction();
        try {
            $registration->cancel();
            $registration->event->decrementParticipants();

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Pendaftaran berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update admin notes
     */
    public function updateNotes(Request $request, EventRegistration $registration)
    {
        $request->validate([
            'admin_notes' => 'nullable|string'
        ]);

        $registration->update([
            'admin_notes' => $request->admin_notes
        ]);

        return redirect()
            ->back()
            ->with('success', 'Catatan berhasil diupdate.');
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $action = $request->action;
        $registrationIds = $request->registration_ids;

        if (!$registrationIds || !is_array($registrationIds)) {
            return redirect()
                ->back()
                ->with('error', 'Tidak ada pendaftaran yang dipilih.');
        }

        try {
            switch ($action) {
                case 'confirm':
                    EventRegistration::whereIn('id', $registrationIds)
                        ->where('status', EventRegistration::STATUS_PENDING)
                        ->update([
                            'status' => EventRegistration::STATUS_CONFIRMED,
                            'confirmation_sent_at' => now()
                        ]);
                    $message = 'Pendaftaran berhasil dikonfirmasi.';
                    break;

                case 'cancel':
                    DB::beginTransaction();
                    $registrations = EventRegistration::whereIn('id', $registrationIds)->get();
                    foreach ($registrations as $reg) {
                        if (!$reg->isCancelled()) {
                            $reg->cancel();
                            $reg->event->decrementParticipants();
                        }
                    }
                    DB::commit();
                    $message = 'Pendaftaran berhasil dibatalkan.';
                    break;

                case 'delete':
                    EventRegistration::whereIn('id', $registrationIds)->delete();
                    $message = 'Pendaftaran berhasil dihapus.';
                    break;

                default:
                    return redirect()->back()->with('error', 'Aksi tidak valid.');
            }

            return redirect()
                ->route('admin.registrations.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Export to Excel
     */
    public function export(Request $request)
    {
        // Implement export functionality
        return (new \App\Exports\RegistrationsExport($request->all()))
            ->download('registrations-' . date('Y-m-d') . '.xlsx');
    }
}