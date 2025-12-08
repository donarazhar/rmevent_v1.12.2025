<?php
// database/migrations/2024_01_01_000007_create_feedbacks_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('cascade');
            $table->foreignId('registration_id')->nullable()->constrained('event_registrations')->onDelete('set null');
            
            // Feedback Type
            $table->enum('type', ['testimonial', 'suggestion', 'complaint', 'general'])->default('general');
            
            // Contact Info (if user is not logged in)
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            
            // Rating System
            $table->unsignedTinyInteger('overall_rating')->nullable(); // 1-5
            $table->unsignedTinyInteger('event_rating')->nullable();
            $table->unsignedTinyInteger('facility_rating')->nullable();
            $table->unsignedTinyInteger('service_rating')->nullable();
            
            // Feedback Content
            $table->string('subject')->nullable();
            $table->text('message');
            $table->json('suggestions')->nullable(); // Structured suggestions
            
            // Media
            $table->json('attachments')->nullable(); // Images/documents
            
            // Status & Response
            $table->enum('status', ['new', 'in_review', 'responded', 'resolved', 'archived'])->default('new');
            $table->text('admin_response')->nullable();
            $table->foreignId('responded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('responded_at')->nullable();
            
            // Display Settings
            $table->boolean('is_published')->default(false); // Show as testimonial
            $table->boolean('is_featured')->default(false);
            $table->integer('display_order')->default(0);
            
            // Privacy
            $table->boolean('is_anonymous')->default(false);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['type', 'status']);
            $table->index(['is_published', 'is_featured']);
            $table->index('event_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};