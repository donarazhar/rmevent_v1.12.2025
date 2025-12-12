<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('timeline_id')->nullable()->constrained('project_timelines')->onDelete('set null');
            
            // Milestone Info
            $table->string('name');
            $table->string('code')->unique(); // MLS-001
            $table->text('description')->nullable();
            
            // Timeline
            $table->date('target_date');
            $table->date('actual_date')->nullable();
            
            // Criteria & Deliverables
            $table->json('success_criteria')->nullable(); // Array of criteria
            $table->json('deliverables')->nullable(); // Array of expected deliverables
            
            // Progress
            $table->integer('progress_percentage')->default(0);
            $table->enum('status', ['pending', 'in_progress', 'completed', 'delayed', 'cancelled'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            
            // Responsibility
            $table->foreignId('responsible_person')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('structure_id')->nullable()->constrained('committee_structures')->onDelete('set null');
            
            // Completion
            $table->text('completion_notes')->nullable();
            $table->json('completion_proof')->nullable(); // Array of file URLs
            $table->foreignId('completed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('completed_at')->nullable();
            
            // Verification
            $table->boolean('is_verified')->default(false);
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();
            
            // Order
            $table->integer('order')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['event_id', 'status', 'target_date']);
            $table->index(['responsible_person', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('milestones');
    }
};