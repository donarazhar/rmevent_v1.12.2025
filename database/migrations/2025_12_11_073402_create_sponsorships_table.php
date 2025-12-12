<?php
// database/migrations/2024_01_10_100004_create_sponsorships_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sponsorships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
            
            // Sponsor Info
            $table->string('sponsor_code')->unique(); // SPO-001
            $table->string('company_name');
            $table->string('contact_person');
            $table->string('email');
            $table->string('phone');
            $table->text('address')->nullable();
            $table->string('website')->nullable();
            
            // Sponsorship Details
            $table->enum('tier', ['platinum', 'gold', 'silver', 'bronze', 'partner'])->default('bronze');
            $table->decimal('committed_amount', 15, 2)->default(0);
            $table->decimal('received_amount', 15, 2)->default(0);
            $table->decimal('outstanding_amount', 15, 2)->default(0);
            
            // Type
            $table->enum('type', ['cash', 'in_kind', 'mixed'])->default('cash');
            $table->text('in_kind_description')->nullable(); // Deskripsi bantuan barang/jasa
            $table->decimal('in_kind_value', 15, 2)->nullable();
            
            // Benefits & Packages
            $table->json('benefits_package')->nullable(); // Array of benefits
            $table->json('logo_placements')->nullable(); // Where sponsor logo will be placed
            $table->json('deliverables')->nullable(); // What we need to deliver
            
            // Status
            $table->enum('status', ['prospecting', 'negotiating', 'committed', 'confirmed', 'delivered', 'completed', 'cancelled'])->default('prospecting');
            
            // Dates
            $table->date('proposal_sent_date')->nullable();
            $table->date('commitment_date')->nullable();
            $table->date('contract_date')->nullable();
            $table->date('fulfillment_date')->nullable();
            
            // Payment Schedule
            $table->json('payment_schedule')->nullable(); // Array of payment milestones
            
            // Documents
            $table->string('contract_document')->nullable();
            $table->string('proposal_document')->nullable();
            $table->json('attachments')->nullable();
            
            // Notes
            $table->text('notes')->nullable();
            $table->text('internal_notes')->nullable();
            
            // Relationship Management
            $table->foreignId('pic_internal')->nullable()->constrained('users')->onDelete('set null'); // PIC dari panitia
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['event_id', 'status', 'tier']);
            $table->index(['company_name', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sponsorships');
    }
};