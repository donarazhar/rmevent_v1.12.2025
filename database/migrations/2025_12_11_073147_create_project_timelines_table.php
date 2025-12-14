<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_timelines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('project_timelines')->onDelete('cascade');
            $table->foreignId('structure_id')->nullable()->constrained('committee_structures')->onDelete('set null');
            
            // Task Info
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('code')->unique(); // TASK-001
            
            // Hierarchy & Order
            $table->integer('level')->default(0); // Changed from 1 to 0
            $table->integer('order')->default(0);
            
            // Timeline
            $table->date('start_date');
            $table->date('end_date');
            $table->date('actual_start_date')->nullable();
            $table->date('actual_end_date')->nullable();
            $table->integer('duration_days')->nullable(); // Auto calculated
            
            // Assignment
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->json('team_members')->nullable(); // Array of user IDs
            
            // Progress
            $table->integer('progress_percentage')->default(0);
            $table->enum('status', ['not_started', 'in_progress', 'completed', 'delayed', 'cancelled'])->default('not_started');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium'); // Changed 'critical' to 'urgent'
            
            // Dependencies
            $table->json('dependencies')->nullable(); // Array of timeline IDs that must be completed first
            
            // Resources
            $table->decimal('estimated_budget', 15, 2)->nullable();
            $table->decimal('actual_budget', 15, 2)->nullable();
            $table->decimal('estimated_hours', 10, 2)->nullable(); // Changed to decimal for 0.5 hours support
            $table->decimal('actual_hours', 10, 2)->nullable(); // Changed to decimal for 0.5 hours support
            
            // Notes & Updates
            $table->text('notes')->nullable();
            $table->text('completion_notes')->nullable();
            $table->json('attachments')->nullable();
            
            // Metadata
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['event_id', 'status', 'start_date']);
            $table->index(['assigned_to', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_timelines');
    }
};