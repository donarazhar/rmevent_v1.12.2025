<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('image');
            $table->string('image_mobile')->nullable(); // Responsive image
            
            // Call to Action
            $table->string('button_text')->nullable();
            $table->string('button_url')->nullable();
            $table->enum('button_style', ['primary', 'secondary', 'outline'])->default('primary');
            
            // Positioning & Styling
            $table->enum('text_position', ['left', 'center', 'right'])->default('left');
            $table->string('overlay_color', 7)->default('#000000');
            $table->unsignedTinyInteger('overlay_opacity')->default(50); // 0-100
            
            // Animation
            $table->string('animation_effect')->default('fade'); // fade, slide, zoom
            
            // Settings
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->dateTime('active_from')->nullable();
            $table->dateTime('active_until')->nullable();
            
            // Target
            $table->enum('placement', ['homepage', 'events', 'about', 'all'])->default('homepage');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['is_active', 'order']);
            $table->index('placement');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};