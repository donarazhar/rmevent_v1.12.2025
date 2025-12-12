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
        Schema::table('media', function (Blueprint $table) {
            // Make mediable columns nullable
            $table->string('mediable_type')->nullable()->change();
            $table->unsignedBigInteger('mediable_id')->nullable()->change();

            // Make other optional columns nullable if not already
            $table->string('title')->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->string('alt_text')->nullable()->change();
            $table->string('thumbnail_path')->nullable()->change();
            $table->json('metadata')->nullable()->change();
            $table->integer('width')->nullable()->change();
            $table->integer('height')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            // Revert changes if needed
            // Note: This may fail if there are null values
            $table->string('mediable_type')->nullable(false)->change();
            $table->unsignedBigInteger('mediable_id')->nullable(false)->change();
        });
    }
};
