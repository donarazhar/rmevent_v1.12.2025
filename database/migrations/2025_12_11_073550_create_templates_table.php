<?php
// database/migrations/2024_01_10_200003_create_templates_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            
            // Template Info
            $table->string('template_code')->unique(); // TPL-001
            $table->string('name');
            $table->text('description')->nullable();
            
            // Category
            $table->enum('category', [
                'document',
                'form',
                'presentation',
                'spreadsheet',
                'email',
                'report',
                'certificate',
                'letter',
                'other'
            ])->default('document');
            
            // Template Type
            $table->string('file_type')->nullable(); // docx, xlsx, pptx, pdf, etc
            $table->string('file_path'); // Path to template file
            $table->integer('file_size')->nullable(); // in bytes
            
            // Usage
            $table->text('usage_instructions')->nullable();
            $table->json('variables')->nullable(); // List of variables that can be replaced
            $table->json('tags')->nullable(); // Tags for easier search
            
            // Preview
            $table->string('preview_image')->nullable();
            $table->text('preview_description')->nullable();
            
            // Status
            $table->enum('status', ['active', 'inactive', 'archived'])->default('active');
            
            // Metadata
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->integer('download_count')->default(0);
            $table->integer('usage_count')->default(0);
            
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['category', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};