<?php
// database/migrations/2024_01_10_300001_create_custom_reports_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
            
            // Report Info
            $table->string('report_code')->unique(); // CR-2024-001
            $table->string('title');
            $table->text('description')->nullable();
            
            // Report Configuration
            $table->enum('report_type', [
                'financial',
                'performance',
                'event',
                'registration',
                'custom'
            ])->default('custom');
            
            $table->json('data_sources')->nullable(); // Tables/models to query
            $table->json('filters')->nullable(); // Applied filters
            $table->json('metrics')->nullable(); // Metrics to calculate
            $table->json('dimensions')->nullable(); // Group by fields
            $table->json('chart_config')->nullable(); // Chart settings
            
            // Period
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            
            // Generated Data
            $table->json('report_data')->nullable(); // Cached report results
            $table->timestamp('last_generated_at')->nullable();
            
            // Scheduling
            $table->boolean('is_scheduled')->default(false);
            $table->enum('schedule_frequency', ['daily', 'weekly', 'monthly', 'quarterly'])->nullable();
            $table->json('schedule_config')->nullable();
            
            // Sharing
            $table->enum('visibility', ['private', 'team', 'public'])->default('private');
            $table->json('shared_with')->nullable(); // Array of user IDs
            
            // Status
            $table->enum('status', ['draft', 'saved', 'published'])->default('draft');
            
            // Metadata
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->integer('view_count')->default(0);
            $table->integer('export_count')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['report_type', 'status']);
            $table->index(['created_by', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_reports');
    }
};