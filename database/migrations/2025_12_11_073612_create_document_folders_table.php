<?php
// database/migrations/2024_01_10_200004_create_document_folders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_folders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('document_folders')->onDelete('cascade');
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
            
            // Folder Info
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('path'); // Full path for easy navigation
            
            // Hierarchy
            $table->integer('level')->default(1);
            $table->integer('order')->default(0);
            
            // Permissions
            $table->enum('visibility', ['public', 'private', 'restricted'])->default('private');
            $table->json('allowed_users')->nullable(); // Array of user IDs who can access
            $table->json('allowed_structures')->nullable(); // Array of structure IDs
            
            // Color & Icon (for UI)
            $table->string('color')->default('#6B7280');
            $table->string('icon')->default('folder');
            
            // Metadata
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['parent_id', 'event_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_folders');
    }
};