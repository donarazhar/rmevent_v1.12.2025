<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_descriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('structure_id')->nullable()->constrained('committee_structures')->onDelete('cascade');
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
            
            // Job Info
            $table->string('title');
            $table->string('code')->unique(); // JOB-001
            $table->text('description');
            
            // Requirements
            $table->json('responsibilities')->nullable(); // Array tugas dan tanggung jawab
            $table->json('requirements')->nullable(); // Array persyaratan
            $table->json('skills_required')->nullable(); // Array skill yang dibutuhkan
            
            // Workload
            $table->integer('estimated_hours')->nullable(); // Estimasi jam kerja
            $table->enum('workload_level', ['light', 'medium', 'heavy'])->default('medium');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            
            // Assignment
            $table->integer('required_members')->default(1);
            $table->integer('assigned_members')->default(0);
            
            // Period
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            
            // Status
            $table->enum('status', ['draft', 'active', 'filled', 'closed'])->default('draft');
            
            // Metadata
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['structure_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_descriptions');
    }
};