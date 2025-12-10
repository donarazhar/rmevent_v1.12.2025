<?php
// app/Http/Controllers/Frontend/RegistrationController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\EventRegistrationRequest;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Notifications\EventRegistrationConfirmed;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    /**
     * Show registration form
     */
    public function create($eventSlug)
    {
        $event = Event::published()
            ->where('slug', $eventSlug)
            ->firstOrFail();

        // Check if registration is open
        if (!$event->canRegister()) {
            return redirect()
                ->route('events.show', $event->slug)
                ->with('error', 'Maaf, pendaftaran untuk event ini sudah ditutup atau kuota sudah penuh.');
        }

        // Check if user already registered
        if (auth()->check()) {
            $existingRegistration = EventRegistration::where('event_id', $event->id)
                ->where('user_id', auth()->id())
                ->whereIn('status', ['pending', 'confirmed'])
                ->first();

            if ($existingRegistration) {
                return redirect()
                    ->route('registrations.show', $existingRegistration->registration_code)
                    ->with('info', 'Anda sudah terdaftar untuk event ini.');
            }
        }

        return view('frontend.registrations.create', compact('event'));
    }

    /**
     * Store registration
     */
    public function store(EventRegistrationRequest $request, $eventSlug)
    {
        $event = Event::published()
            ->where('slug', $eventSlug)
            ->firstOrFail();

        // Double check registration status
        if (!$event->canRegister()) {
            return redirect()
                ->route('events.show', $event->slug)
                ->with('error', 'Maaf, pendaftaran untuk event ini sudah ditutup atau kuota sudah penuh.');
        }

        DB::beginTransaction();
        try {
            $data = $request->validated();
            $data['event_id'] = $event->id;
            $data['status'] = EventRegistration::STATUS_PENDING;

            // Add user_id if authenticated
            if (auth()->check()) {
                $data['user_id'] = auth()->id();
            }

            // Set payment amount
            $data['payment_amount'] = $event->price;
            $data['payment_status'] = $event->is_free 
                ? EventRegistration::PAYMENT_PAID 
                : EventRegistration::PAYMENT_UNPAID;

            $registration = EventRegistration::create($data);

            // Handle payment proof upload if provided
            if ($request->hasFile('payment_proof')) {
                $registration->addMedia(
                    $request->file('payment_proof'),
                    'payment_proofs'
                );
            }

            // Increment event participants
            $event->incrementParticipants();

            // Auto-confirm for free events
            if ($event->is_free) {
                $registration->confirm();
            }

            // Send confirmation email
            if ($registration->email) {
                $registration->notify(new EventRegistrationConfirmed($registration));
            }

            // Log activity
            $registration->logActivity('created', 'New event registration');

            DB::commit();

            return redirect()
                ->route('registrations.show', $registration->registration_code)
                ->with('success', 'Pendaftaran berhasil! Silakan cek email Anda untuk konfirmasi.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memproses pendaftaran. Silakan coba lagi.');
        }
    }

    /**
     * Show registration detail
     */
    public function show($registrationCode)
    {
        $registration = EventRegistration::with('event')
            ->where('registration_code', $registrationCode)
            ->firstOrFail();

        // Check authorization
        if (auth()->check() && $registration->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to registration');
        }

        return view('frontend.registrations.show', compact('registration'));
    }

    /**
     * Cancel registration
     */
    public function cancel($registrationCode)
    {
        $registration = EventRegistration::where('registration_code', $registrationCode)
            ->firstOrFail();

        // Check authorization
        if (auth()->check() && $registration->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        // Check if can be cancelled
        if ($registration->isCancelled() || $registration->isAttended()) {
            return redirect()
                ->back()
                ->with('error', 'Pendaftaran tidak dapat dibatalkan.');
        }

        DB::beginTransaction();
        try {
            $registration->cancel();
            $registration->event->decrementParticipants();

            // Log activity
            $registration->logActivity('cancelled', 'Registration cancelled');

            DB::commit();

            return redirect()
                ->route('registrations.show', $registration->registration_code)
                ->with('success', 'Pendaftaran berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat membatalkan pendaftaran.');
        }
    }

    /**
     * Download registration ticket/certificate
     */
    public function downloadTicket($registrationCode)
    {
        $registration = EventRegistration::with('event')
            ->where('registration_code', $registrationCode)
            ->firstOrFail();

        // Check authorization
        if (auth()->check() && $registration->user_id !== auth()->id()) {
            abort(403);
        }

        // Generate PDF ticket
        $pdf = \PDF::loadView('frontend.registrations.ticket', compact('registration'));
        
        return $pdf->download('ticket-' . $registration->registration_code . '.pdf');
    }
}