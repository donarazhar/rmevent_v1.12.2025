<?php
// database/migrations/2024_01_10_200002_create_work_instructions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_instructions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sop_id')->nullable()->constrained()->onDelete('set null');
            
            // Instruction Info
            $table->string('instruction_code')->unique(); // WI-001
            $table->string('title');
            $table->text('description')->nullable();
            
            // Category
            $table->enum('category', [
                'setup',
                'execution',
                'troubleshooting',
                'maintenance',
                'reporting',
                'other'
            ])->default('execution');
            
            // Content
            $table->longText('content'); // Rich text
            $table->json('steps')->nullable(); // Step-by-step instructions
            $table->json('tools_required')->nullable(); // Tools/equipment needed
            $table->json('materials_required')->nullable(); // Materials needed
            
            // Safety & Precautions
            $table->text('safety_notes')->nullable();
            $table->json('precautions')->nullable();
            
            // Time & Difficulty
            $table->integer('estimated_time')->nullable(); // in minutes
            $table->enum('difficulty_level', ['easy', 'medium', 'hard'])->default('medium');
            
            // Related Resources
            $table->json('related_sops')->nullable();
            $table->json('related_templates')->nullable();
            $table->json('attachments')->nullable(); // Images, videos, files
            
            // Version
            $table->string('version', 10)->default('1.0');
            $table->date('effective_date');
            
            // Status
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            
            // Metadata
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            
            $table->integer('view_count')->default(0);
            $table->integer('download_count')->default(0);
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['category', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_instructions');
    }
};