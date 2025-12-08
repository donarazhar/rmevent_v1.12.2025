<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable(); // Ringkasan
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            
            // SEO Fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            
            // Status & Publishing
            $table->enum('status', ['draft', 'published', 'scheduled', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            
            // Engagement Metrics
            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedInteger('reading_time')->nullable(); // dalam menit
            
            // Settings
            $table->boolean('is_featured')->default(false);
            $table->boolean('allow_comments')->default(true);
            $table->boolean('is_sticky')->default(false); // Pinned post
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('slug');
            $table->index(['status', 'published_at']);
            $table->index(['is_featured', 'published_at']);
            $table->fullText(['title', 'content']); // Full-text search
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};