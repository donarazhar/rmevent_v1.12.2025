<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            
            // User & Event References
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('event_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('registration_id')->nullable()->constrained('event_registrations')->nullOnDelete();
            
            // Feedback Type
            $table->enum('type', ['general', 'event', 'testimonial', 'complaint', 'suggestion'])
                  ->default('general');
            
            // Contact Information (for non-authenticated users)
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            
            // Feedback Content
            $table->string('subject')->nullable();
            $table->text('message');
            
            // Ratings (1-5 scale)
            $table->unsignedTinyInteger('overall_rating')->nullable();
            $table->unsignedTinyInteger('content_rating')->nullable();
            $table->unsignedTinyInteger('speaker_rating')->nullable();
            $table->unsignedTinyInteger('venue_rating')->nullable();
            $table->unsignedTinyInteger('organization_rating')->nullable();
            
            // NPS Score (0-10)
            $table->unsignedTinyInteger('recommendation_score')->nullable();
            
            // Additional Feedback
            $table->text('suggestions')->nullable();
            
            // Publishing Settings
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->integer('display_order')->default(0);
            
            // Status Tracking
            $table->enum('status', ['new', 'in_review', 'responded', 'resolved', 'archived'])
                  ->default('new');
            
            // Admin Response
            $table->text('admin_response')->nullable();
            $table->foreignId('responded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('responded_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('type');
            $table->index('status');
            $table->index('is_published');
            $table->index('overall_rating');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};