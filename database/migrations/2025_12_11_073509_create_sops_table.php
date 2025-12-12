<?php
// database/migrations/2024_01_10_200001_create_sops_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sops', function (Blueprint $table) {
            $table->id();
            
            // SOP Info
            $table->string('sop_code')->unique(); // SOP-001
            $table->string('title');
            $table->text('purpose')->nullable(); // Tujuan SOP
            $table->text('scope')->nullable(); // Ruang lingkup
            
            // Category
            $table->enum('category', [
                'event_management',
                'finance',
                'registration',
                'documentation',
                'emergency',
                'general',
                'other'
            ])->default('general');
            
            // Content
            $table->longText('content'); // Rich text content
            $table->json('procedures')->nullable(); // Step-by-step procedures
            $table->json('responsibilities')->nullable(); // Who is responsible for what
            
            // Related Documents
            $table->json('related_forms')->nullable(); // Forms needed
            $table->json('related_templates')->nullable(); // Templates needed
            $table->json('attachments')->nullable();
            
            // Version Control
            $table->string('version', 10)->default('1.0');
            $table->foreignId('parent_sop_id')->nullable()->constrained('sops')->onDelete('set null');
            $table->text('version_notes')->nullable(); // What changed in this version
            
            // Dates
            $table->date('effective_date');
            $table->date('review_date')->nullable(); // Next review date
            $table->date('expiry_date')->nullable();
            
            // Status
            $table->enum('status', ['draft', 'under_review', 'approved', 'published', 'archived'])->default('draft');
            
            // Approval
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            
            // Metadata
            $table->text('notes')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('download_count')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['category', 'status']);
            $table->index(['effective_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sops');
    }
};