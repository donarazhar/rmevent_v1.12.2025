<?php
// database/migrations/2024_01_10_300002_create_executive_summaries_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('executive_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
            
            // Summary Info
            $table->string('summary_code')->unique(); // ES-2024-001
            $table->string('title');
            $table->enum('summary_type', ['monthly', 'quarterly', 'event', 'annual'])->default('event');
            
            // Period
            $table->date('period_start');
            $table->date('period_end');
            $table->date('report_date');
            
            // Executive Summary Content
            $table->longText('executive_overview')->nullable();
            $table->longText('key_highlights')->nullable();
            $table->longText('achievements')->nullable();
            $table->longText('challenges')->nullable();
            $table->longText('recommendations')->nullable();
            
            // Key Metrics
            $table->json('financial_summary')->nullable();
            $table->json('performance_metrics')->nullable();
            $table->json('event_statistics')->nullable();
            $table->json('team_performance')->nullable();
            
            // Financial Highlights
            $table->decimal('total_income', 15, 2)->nullable();
            $table->decimal('total_expenses', 15, 2)->nullable();
            $table->decimal('net_result', 15, 2)->nullable();
            $table->decimal('budget_utilization_percentage', 5, 2)->nullable();
            
            // Event Highlights
            $table->integer('events_conducted')->nullable();
            $table->integer('total_participants')->nullable();
            $table->decimal('satisfaction_score', 3, 2)->nullable();
            
            // Charts & Visualizations
            $table->json('charts_data')->nullable();
            
            // Documents
            $table->string('document_file')->nullable(); // Generated PDF
            $table->json('supporting_documents')->nullable();
            
            // Status
            $table->enum('status', ['draft', 'under_review', 'approved', 'published'])->default('draft');
            
            // Workflow
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['event_id', 'summary_type', 'status']);
            $table->index(['period_start', 'period_end']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('executive_summaries');
    }
};