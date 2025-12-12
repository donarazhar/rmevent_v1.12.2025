<?php
// database/migrations/2024_01_10_200008_create_contracts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('sponsorship_id')->nullable()->constrained()->onDelete('set null');
            
            // Contract Info
            $table->string('contract_code')->unique(); // CTR-2024-001
            $table->string('title');
            $table->text('description')->nullable();
            
            // Type
            $table->enum('type', [
                'sponsorship',
                'vendor',
                'venue',
                'partnership',
                'service',
                'employment',
                'other'
            ])->default('vendor');
            
            // Parties
            $table->string('party_a_name'); // Usually our organization
            $table->text('party_a_address')->nullable();
            $table->string('party_a_representative')->nullable();
            
            $table->string('party_b_name'); // Vendor/Partner/Sponsor
            $table->text('party_b_address')->nullable();
            $table->string('party_b_representative')->nullable();
            $table->string('party_b_contact')->nullable();
            $table->string('party_b_email')->nullable();
            
            // Contract Value
            $table->decimal('contract_value', 15, 2)->nullable();
            $table->string('currency', 3)->default('IDR');
            
            // Terms
            $table->longText('terms_and_conditions')->nullable();
            $table->longText('scope_of_work')->nullable();
            $table->json('deliverables')->nullable();
            $table->json('payment_terms')->nullable();
            
            // Period
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('duration_days')->nullable();
            $table->boolean('auto_renewal')->default(false);
            
            // Status
            $table->enum('status', [
                'draft',
                'pending_signature',
                'signed',
                'active',
                'completed',
                'terminated',
                'expired'
            ])->default('draft');
            
            // Signatures
            $table->foreignId('signed_by_party_a')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('signed_at_party_a')->nullable();
            $table->string('signature_file_party_a')->nullable();
            
            $table->date('signed_at_party_b')->nullable();
            $table->string('signature_file_party_b')->nullable();
            
            // Documents
            $table->string('contract_file')->nullable();
            $table->json('supporting_documents')->nullable();
            
            // Renewal & Termination
            $table->date('renewal_notice_date')->nullable();
            $table->date('termination_date')->nullable();
            $table->text('termination_reason')->nullable();
            
            // PIC (Person In Charge)
            $table->foreignId('pic_internal')->nullable()->constrained('users')->onDelete('set null');
            
            // Metadata
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['event_id', 'type', 'status']);
            $table->index(['status', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};