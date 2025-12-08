<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action');
            $table->string('description')->nullable();
            
            // Subject (what was acted upon)
            $table->morphs('subject'); // ✅ Sudah include index otomatis
            
            // Changes
            $table->json('properties')->nullable();
            
            // Metadata
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            
            $table->timestamps();
            
            // ❌ HAPUS BARIS INI jika ada:
            // $table->index(['subject_type', 'subject_id']);
            
            $table->index('user_id');
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};