<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type'); // image, video, document
            $table->string('mime_type');
            $table->unsignedBigInteger('file_size'); // in bytes
            $table->string('disk')->default('public'); // storage disk
            
            // Image specific
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->string('thumbnail_path')->nullable();
            
            // Metadata
            $table->json('metadata')->nullable(); // EXIF, etc.
            $table->string('alt_text')->nullable(); // SEO
            
            // Polymorphic relationship
            // morphs() sudah otomatis membuat index, jadi tidak perlu tambahan index manual
            $table->morphs('mediable'); // mediable_type, mediable_id + index
            
            // Organization
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('collection')->nullable(); // gallery, slider, etc.
            $table->integer('order')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index tambahan (hanya untuk kolom yang belum di-index)
            $table->index('file_type');
            $table->index('collection');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};