<?php
// database/migrations/2024_01_10_100006_create_expenses_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('budget_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('budget_item_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('budget_allocation_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('structure_id')->nullable()->constrained('committee_structures')->onDelete('set null');
            
            // Expense Info
            $table->string('expense_code')->unique(); // EXP-2024-001
            $table->string('title');
            $table->text('description')->nullable();
            
            // Category
            $table->enum('category', [
                'operational',
                'event_execution',
                'equipment',
                'logistics',
                'marketing',
                'transportation',
                'accommodation',
                'meals',
                'honorarium',
                'utilities',
                'other'
            ])->default('operational');
            
            // Vendor/Payee
            $table->string('vendor_name');
            $table->string('vendor_contact')->nullable();
            $table->text('vendor_address')->nullable();
            $table->string('vendor_tax_id')->nullable(); // NPWP
            
            // Amount
            $table->decimal('requested_amount', 15, 2);
            $table->decimal('approved_amount', 15, 2)->nullable();
            $table->decimal('paid_amount', 15, 2)->nullable();
            
            // Dates
            $table->date('request_date');
            $table->date('needed_by_date')->nullable();
            $table->date('approved_date')->nullable();
            $table->date('payment_date')->nullable();
            
            // Payment
            $table->enum('payment_method', [
                'cash',
                'bank_transfer',
                'check',
                'petty_cash',
                'e_wallet',
                'other'
            ])->default('bank_transfer');
            
            $table->string('payment_reference')->nullable();
            $table->string('bank_account')->nullable();
            
            // Status
            $table->enum('status', [
                'draft',
                'submitted',
                'under_review',
                'approved',
                'rejected',
                'paid',
                'cancelled'
            ])->default('draft');
            
            // Approval Workflow
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_notes')->nullable();
            
            // Payment Processing
            $table->foreignId('paid_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('paid_at')->nullable();
            
            // Documents
            $table->string('receipt_file')->nullable();
            $table->string('invoice_file')->nullable();
            $table->json('supporting_documents')->nullable();
            
            // Tax & Reporting
            $table->decimal('tax_amount', 15, 2)->nullable();
            $table->string('tax_type')->nullable(); // PPh21, PPh23, PPN, dll
            $table->boolean('has_tax_invoice')->default(false);
            
            // Metadata
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['event_id', 'category', 'status']);
            $table->index(['status', 'request_date']);
            $table->index(['requested_by', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};