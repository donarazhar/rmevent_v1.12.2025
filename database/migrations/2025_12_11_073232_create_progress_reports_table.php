<?php
// database/migrations/2024_01_10_000008_create_progress_reports_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progress_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('structure_id')->nullable()->constrained('committee_structures')->onDelete('set null');
            $table->foreignId('timeline_id')->nullable()->constrained('project_timelines')->onDelete('set null');

            // Report Info
            $table->string('report_code')->unique(); // PR-2024-001
            $table->string('title');
            $table->enum('report_type', ['daily', 'weekly', 'monthly', 'milestone', 'ad_hoc'])->default('weekly');

            // Period
            $table->date('period_start');
            $table->date('period_end');
            $table->date('report_date');

            // Content
            $table->text('executive_summary')->nullable();
            $table->longText('activities_completed')->nullable();
            $table->longText('ongoing_activities')->nullable();
            $table->longText('planned_activities')->nullable();
            $table->longText('issues_challenges')->nullable();
            $table->longText('solutions_recommendations')->nullable();

            // Progress Metrics
            $table->integer('overall_progress')->default(0); // 0-100
            $table->integer('tasks_planned')->default(0);
            $table->integer('tasks_completed')->default(0);
            $table->integer('tasks_delayed')->default(0);

            // Budget (if applicable)
            $table->decimal('budget_allocated', 15, 2)->nullable();
            $table->decimal('budget_used', 15, 2)->nullable();
            $table->decimal('budget_variance', 15, 2)->nullable();

            // Resources
            $table->integer('team_members_involved')->nullable();
            $table->integer('hours_spent')->nullable();

            // Attachments
            $table->json('attachments')->nullable(); // Photos, documents, etc.

            // Status
            $table->enum('status', ['draft', 'submitted', 'reviewed', 'approved', 'rejected'])->default('draft');

            // Author & Approver
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('submitted_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();

            // Feedback
            $table->text('reviewer_feedback')->nullable();
            $table->text('approval_notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['event_id', 'structure_id', 'report_date']);
            $table->index(['status', 'report_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress_reports');
    }
};
