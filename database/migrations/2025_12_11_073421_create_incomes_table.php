<?php
// database/migrations/2024_01_10_100005_create_incomes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('budget_id')->nullable()->constrained()->onDelete('set null');
            
            // Income Info
            $table->string('income_code')->unique(); // IN-2024-001
            $table->string('title');
            $table->text('description')->nullable();
            
            // Category
            $table->enum('category', [
                'registration_fee',
                'sponsorship',
                'donation',
                'infaq',
                'merchandise',
                'grant',
                'other'
            ])->default('other');
            
            // Source
            $table->string('source_name'); // Nama pemberi/donatur
            $table->string('source_contact')->nullable();
            $table->string('source_email')->nullable();
            
            // Related Records
            $table->foreignId('registration_id')->nullable()->constrained('event_registrations')->onDelete('set null');
            $table->foreignId('sponsorship_id')->nullable()->constrained()->onDelete('set null');
            
            // Amount
            $table->decimal('amount', 15, 2);
            
            // Payment
            $table->enum('payment_method', [
                'cash',
                'bank_transfer',
                'e_wallet',
                'credit_card',
                'check',
                'other'
            ])->default('bank_transfer');
            
            $table->string('payment_reference')->nullable(); // No. transfer, no. cek, dll
            $table->string('bank_account')->nullable();
            $table->date('payment_date');
            $table->date('received_date')->nullable();
            
            // Receipt
            $table->string('receipt_number')->nullable();
            $table->string('receipt_file')->nullable();
            
            // Status
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            
            // Verification
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();
            
            // Metadata
            $table->json('attachments')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['event_id', 'category', 'payment_date']);
            $table->index(['status', 'received_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};