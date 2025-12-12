<?php
// database/migrations/2024_01_10_200007_create_meeting_minutes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meeting_minutes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('structure_id')->nullable()->constrained('committee_structures')->onDelete('set null');
            
            // Meeting Info
            $table->string('minute_code')->unique(); // MM-2024-001
            $table->string('meeting_title');
            $table->enum('meeting_type', [
                'coordination',
                'planning',
                'evaluation',
                'emergency',
                'general',
                'other'
            ])->default('general');
            
            // Schedule
            $table->dateTime('meeting_date');
            $table->string('location')->nullable();
            $table->string('meeting_link')->nullable(); // For online meetings
            $table->integer('duration_minutes')->nullable();
            
            // Participants
            $table->json('participants')->nullable(); // Array of user IDs
            $table->json('absent_members')->nullable(); // Array of user IDs
            $table->json('external_participants')->nullable(); // Non-user participants
            
            // Roles
            $table->foreignId('chairman')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('secretary')->nullable()->constrained('users')->onDelete('set null');
            
            // Content
            $table->longText('agenda')->nullable();
            $table->longText('discussion_summary')->nullable();
            $table->longText('decisions')->nullable();
            $table->longText('action_items')->nullable(); // To-do items
            $table->longText('next_meeting_agenda')->nullable();
            
            // Action Items (structured)
            $table->json('action_items_list')->nullable(); // [{task, assignee, deadline, status}]
            
            // Documents
            $table->string('document_file')->nullable(); // PDF of the minutes
            $table->json('attachments')->nullable();
            
            // Next Meeting
            $table->dateTime('next_meeting_date')->nullable();
            $table->string('next_meeting_location')->nullable();
            
            // Status
            $table->enum('status', ['draft', 'finalized', 'distributed', 'archived'])->default('draft');
            
            // Workflow
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('finalized_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('finalized_at')->nullable();
            
            $table->timestamp('distributed_at')->nullable();
            $table->json('distributed_to')->nullable(); // Array of user IDs
            
            // Metadata
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['event_id', 'meeting_date', 'status']);
            $table->index(['meeting_type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meeting_minutes');
    }
};