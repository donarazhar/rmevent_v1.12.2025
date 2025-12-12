<?php
// database/migrations/2024_01_10_200005_create_documents_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('folder_id')->nullable()->constrained('document_folders')->onDelete('set null');
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
            
            // Document Info
            $table->string('document_code')->unique(); // DOC-001
            $table->string('title');
            $table->text('description')->nullable();
            
            // Category
            $table->enum('category', [
                'proposal',
                'report',
                'meeting_notes',
                'contract',
                'letter',
                'certificate',
                'presentation',
                'photo',
                'video',
                'other'
            ])->default('other');
            
            // File Info
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type'); // pdf, docx, xlsx, jpg, etc
            $table->integer('file_size'); // in bytes
            $table->string('mime_type');
            
            // Version Control
            $table->string('version', 10)->default('1.0');
            $table->foreignId('parent_document_id')->nullable()->constrained('documents')->onDelete('set null');
            $table->text('version_notes')->nullable();
            
            // Permissions & Sharing
            $table->enum('visibility', ['public', 'private', 'restricted'])->default('private');
            $table->json('shared_with_users')->nullable(); // Array of user IDs
            $table->json('shared_with_structures')->nullable(); // Array of structure IDs
            $table->boolean('allow_download')->default(true);
            $table->boolean('allow_print')->default(true);
            
            // Tags & Metadata
            $table->json('tags')->nullable();
            $table->json('custom_fields')->nullable();
            
            // Status
            $table->enum('status', ['draft', 'final', 'archived'])->default('final');
            
            // Dates
            $table->date('document_date')->nullable(); // Date on the document
            $table->date('expiry_date')->nullable();
            
            // Tracking
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->integer('view_count')->default(0);
            $table->integer('download_count')->default(0);
            $table->timestamp('last_viewed_at')->nullable();
            
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['folder_id', 'category', 'status']);
            $table->index(['event_id', 'category']);
            $table->fullText(['title', 'description']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};