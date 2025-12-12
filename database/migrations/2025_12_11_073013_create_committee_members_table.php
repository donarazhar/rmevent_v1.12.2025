<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('committee_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('structure_id')->constrained('committee_structures')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Position
            $table->string('position'); // Ketua, Wakil, Anggota, Koordinator, dll
            $table->text('specific_role')->nullable(); // Role spesifik
            
            // Period
            $table->date('start_date');
            $table->date('end_date')->nullable();
            
            // Status
            $table->enum('status', ['active', 'inactive', 'resigned'])->default('active');
            
            // Performance
            $table->integer('tasks_completed')->default(0);
            $table->integer('tasks_assigned')->default(0);
            $table->decimal('performance_score', 5, 2)->nullable();
            
            // Metadata
            $table->text('notes')->nullable();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['structure_id', 'user_id']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('committee_members');
    }
};