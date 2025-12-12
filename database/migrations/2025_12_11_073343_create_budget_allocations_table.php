<?php
// database/migrations/2024_01_10_100003_create_budget_allocations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budget_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->constrained()->onDelete('cascade');
            $table->foreignId('structure_id')->nullable()->constrained('committee_structures')->onDelete('set null');
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
            
            // Allocation Info
            $table->string('allocation_code')->unique(); // ALLOC-001
            $table->string('title');
            $table->text('description')->nullable();
            
            // Type
            $table->enum('allocation_type', ['division', 'event', 'project', 'activity'])->default('division');
            
            // Amounts
            $table->decimal('allocated_amount', 15, 2)->default(0);
            $table->decimal('spent_amount', 15, 2)->default(0);
            $table->decimal('remaining_amount', 15, 2)->default(0);
            $table->decimal('committed_amount', 15, 2)->default(0); // Amount that's promised but not yet spent
            
            // Period
            $table->date('valid_from');
            $table->date('valid_until');
            
            // Status
            $table->enum('status', ['active', 'depleted', 'cancelled', 'closed'])->default('active');
            
            // Responsible
            $table->foreignId('allocated_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            
            // Metadata
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['budget_id', 'structure_id', 'status']);
            $table->index(['allocation_type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_allocations');
    }
};