<?php
// database/migrations/2024_01_10_300003_create_final_event_reports_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('final_event_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            
            // Report Info
            $table->string('report_code')->unique(); // FER-2024-001
            $table->string('title');
            $table->date('report_date');
            
            // Sections
            $table->longText('executive_summary')->nullable();
            $table->longText('event_overview')->nullable();
            $table->longText('objectives_achievement')->nullable();
            $table->longText('implementation_process')->nullable();
            $table->longText('participant_analysis')->nullable();
            $table->longText('financial_report')->nullable();
            $table->longText('challenges_solutions')->nullable();
            $table->longText('lessons_learned')->nullable();
            $table->longText('recommendations')->nullable();
            $table->longText('conclusion')->nullable();
            
            // Statistics
            $table->integer('total_participants')->nullable();
            $table->integer('registered_participants')->nullable();
            $table->integer('attended_participants')->nullable();
            $table->decimal('attendance_rate', 5, 2)->nullable();
            
            // Financial Summary
            $table->decimal('total_budget', 15, 2)->nullable();
            $table->decimal('total_income', 15, 2)->nullable();
            $table->decimal('total_expenses', 15, 2)->nullable();
            $table->decimal('surplus_deficit', 15, 2)->nullable();
            
            // Performance Scores
            $table->decimal('overall_satisfaction', 3, 2)->nullable();
            $table->decimal('content_rating', 3, 2)->nullable();
            $table->decimal('organization_rating', 3, 2)->nullable();
            $table->decimal('venue_rating', 3, 2)->nullable();
            
            // Team Performance
            $table->integer('committee_members')->nullable();
            $table->decimal('team_performance_score', 3, 2)->nullable();
            
            // Documentation
            $table->json('photo_gallery')->nullable();
            $table->json('video_links')->nullable();
            $table->json('supporting_documents')->nullable();
            
            // Generated Report
            $table->string('report_file')->nullable(); // PDF
            $table->string('presentation_file')->nullable(); // PPTX
            
            // Status
            $table->enum('status', ['draft', 'under_review', 'approved', 'published'])->default('draft');
            
            // Workflow
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('published_at')->nullable();
            
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['event_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('final_event_reports');
    }
};