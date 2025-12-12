<?php
// database/migrations/2024_01_10_000005_create_performance_evaluations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performance_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('evaluator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('structure_id')->nullable()->constrained('committee_structures')->onDelete('set null');
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
            
            // Evaluation Period
            $table->string('evaluation_code')->unique(); // EVAL-2024-001
            $table->enum('period_type', ['monthly', 'quarterly', 'event', 'annual'])->default('event');
            $table->date('period_start');
            $table->date('period_end');
            
            // Scores (1-5 scale)
            $table->decimal('task_completion_score', 3, 2)->nullable();
            $table->decimal('quality_score', 3, 2)->nullable();
            $table->decimal('teamwork_score', 3, 2)->nullable();
            $table->decimal('initiative_score', 3, 2)->nullable();
            $table->decimal('leadership_score', 3, 2)->nullable();
            $table->decimal('discipline_score', 3, 2)->nullable();
            $table->decimal('overall_score', 3, 2)->nullable();
            
            // Qualitative Assessment
            $table->text('strengths')->nullable();
            $table->text('weaknesses')->nullable();
            $table->text('recommendations')->nullable();
            $table->text('achievements')->nullable();
            $table->text('improvement_areas')->nullable();
            
            // Statistics
            $table->integer('tasks_completed')->default(0);
            $table->integer('tasks_assigned')->default(0);
            $table->integer('attendance_days')->default(0);
            $table->integer('total_days')->default(0);
            
            // Status
            $table->enum('status', ['draft', 'submitted', 'reviewed', 'approved', 'published'])->default('draft');
            
            // Approval
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            
            // Feedback
            $table->text('evaluator_comments')->nullable();
            $table->text('employee_feedback')->nullable();
            $table->timestamp('employee_acknowledged_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'period_start', 'period_end']);
            $table->index(['status', 'event_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_evaluations');
    }
};