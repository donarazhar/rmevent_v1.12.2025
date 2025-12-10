<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('full_description')->nullable();
            $table->string('featured_image')->nullable();
            
            // Event Details
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->string('location')->nullable();
            $table->string('location_maps_url')->nullable(); // Google Maps URL
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->string('timezone')->default('Asia/Jakarta');
            
            // Registration
            $table->boolean('is_registration_open')->default(true);
            $table->dateTime('registration_start')->nullable();
            $table->dateTime('registration_end')->nullable();
            $table->integer('max_participants')->nullable(); // null = unlimited
            $table->integer('current_participants')->default(0);
            $table->boolean('is_free')->default(true);
            $table->decimal('price', 10, 2)->nullable()->default(0);
            
            // Requirements
            $table->json('registration_fields')->nullable(); // Custom form fields
            $table->text('requirements')->nullable(); // Syarat & ketentuan
            
            // Contact Person
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            
            // Status
            $table->enum('status', ['draft', 'published', 'cancelled', 'completed'])->default('published');
            $table->boolean('is_featured')->default(false);
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('slug');
            $table->index(['status', 'start_datetime']);
            $table->index(['is_registration_open', 'registration_end']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};