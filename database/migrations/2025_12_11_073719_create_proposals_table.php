<?php
// database/migrations/2024_01_10_200006_create_proposals_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('structure_id')->nullable()->constrained('committee_structures')->onDelete('set null');
            
            // Proposal Info
            $table->string('proposal_code')->unique(); // PROP-2024-001
            $table->string('title');
            $table->text('description')->nullable();
            
            // Type
            $table->enum('type', [
                'event',
                'sponsorship',
                'partnership',
                'funding',
                'project',
                'other'
            ])->default('event');
            
            // Content
            $table->longText('executive_summary')->nullable();
            $table->longText('background')->nullable();
            $table->longText('objectives')->nullable();
            $table->longText('methodology')->nullable();
            $table->longText('timeline')->nullable();
            $table->longText('budget_overview')->nullable();
            $table->longText('expected_outcomes')->nullable();
            
            // Target & Recipient
            $table->string('submitted_to')->nullable(); // Organization/Person
            $table->string('recipient_contact')->nullable();
            $table->string('recipient_email')->nullable();
            
            // Amounts
            $table->decimal('requested_amount', 15, 2)->nullable();
            $table->decimal('approved_amount', 15, 2)->nullable();
            
            // Dates
            $table->date('submission_date')->nullable();
            $table->date('response_deadline')->nullable();
            $table->date('approved_date')->nullable();
            
            // Status
            $table->enum('status', [
                'draft',
                'under_review',
                'submitted',
                'approved',
                'rejected',
                'revision_needed',
                'withdrawn'
            ])->default('draft');
            
            // Workflow
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('submitted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('submitted_at')->nullable();
            
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_feedback')->nullable();
            
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_notes')->nullable();
            
            // Documents
            $table->string('document_file')->nullable();
            $table->json('supporting_documents')->nullable();
            
            // Metadata
            $table->text('notes')->nullable();
            $table->text('revision_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['event_id', 'type', 'status']);
            $table->index(['status', 'submission_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};