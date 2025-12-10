<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('registration_code')->unique()->nullable();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Personal Data
            $table->string('name');
            $table->string('email');
            $table->string('phone', 20);
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            
            // Custom Fields (from event.registration_fields)
            $table->json('custom_data')->nullable();
            
            // Status & Payment
            $table->enum('status', ['pending', 'confirmed', 'attended', 'cancelled', 'no_show'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');
            $table->decimal('payment_amount', 10, 2)->default(0);
            $table->string('payment_proof')->nullable();
            $table->timestamp('payment_date')->nullable();
            
            // Attendance
            $table->timestamp('checked_in_at')->nullable();
            $table->string('checked_in_by')->nullable();
            
            // Communication
            $table->text('notes')->nullable(); // Catatan dari jamaah
            $table->text('admin_notes')->nullable(); // Catatan dari panitia
            $table->timestamp('confirmation_sent_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('registration_code');
            $table->index(['event_id', 'status']);
            $table->index('email');
            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};