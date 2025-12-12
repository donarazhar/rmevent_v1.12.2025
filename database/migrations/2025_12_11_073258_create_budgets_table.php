<?php
// database/migrations/2024_01_10_100001_create_budgets_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');

            // Budget Info
            $table->string('budget_code')->unique(); // RAB-2024-001
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('fiscal_year', 4);

            // Budget Version
            $table->integer('version')->default(1);
            $table->foreignId('parent_budget_id')->nullable()->constrained('budgets')->onDelete('set null');

            // Amounts
            $table->decimal('total_planned', 15, 2)->default(0);
            $table->decimal('total_approved', 15, 2)->default(0);
            $table->decimal('total_allocated', 15, 2)->default(0);
            $table->decimal('total_spent', 15, 2)->default(0);
            $table->decimal('total_remaining', 15, 2)->default(0);

            // Period
            $table->date('valid_from');
            $table->date('valid_until');

            // Status
            $table->enum('status', ['draft', 'submitted', 'under_review', 'approved', 'rejected', 'revised', 'active', 'closed'])->default('draft');

            // Approval Workflow
            $table->foreignId('submitted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('submitted_at')->nullable();

            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();

            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_notes')->nullable();

            // Revision
            $table->text('revision_reason')->nullable();
            $table->text('rejection_reason')->nullable();

            // Metadata
            $table->json('attachments')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['event_id', 'status', 'fiscal_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
