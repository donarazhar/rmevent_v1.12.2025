<?php
// database/migrations/2024_01_10_100002_create_budget_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budget_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('budget_items')->onDelete('cascade');

            // Item Info
            $table->string('code'); // RAB-001
            $table->string('name');
            $table->text('description')->nullable();

            // Category
            $table->enum('category', [
                'operational',
                'event',
                'equipment',
                'logistics',
                'marketing',
                'personnel',
                'miscellaneous'
            ])->default('operational');

            // Hierarchy
            $table->integer('level')->default(1); // 1=Category, 2=Item, 3=Sub-item
            $table->integer('order')->default(0);

            // Calculation
            $table->integer('quantity')->default(1);
            $table->string('unit')->default('pcs'); // pcs, set, person, day, month, dll
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0); // quantity * unit_price

            // Amounts
            $table->decimal('planned_amount', 15, 2)->default(0);
            $table->decimal('approved_amount', 15, 2)->default(0);
            $table->decimal('allocated_amount', 15, 2)->default(0);
            $table->decimal('spent_amount', 15, 2)->default(0);
            $table->decimal('remaining_amount', 15, 2)->default(0);

            // Priority
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->boolean('is_mandatory')->default(false);

            // Notes
            $table->text('notes')->nullable();
            $table->text('justification')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['budget_id', 'category', 'level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_items');
    }
};
