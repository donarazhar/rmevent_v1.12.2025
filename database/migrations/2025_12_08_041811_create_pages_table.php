<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            $table->string('template')->default('default'); // default, landing, contact, etc.
            $table->json('sections')->nullable(); // Untuk page builder sections
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            
            // Hierarchy
            $table->foreignId('parent_id')->nullable()->constrained('pages')->onDelete('set null');
            $table->integer('order')->default(0);
            
            // Status
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->boolean('show_in_menu')->default(true);
            $table->boolean('is_homepage')->default(false);
            
            // Custom Settings
            $table->json('custom_css')->nullable();
            $table->json('custom_js')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('slug');
            $table->index(['status', 'show_in_menu']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};