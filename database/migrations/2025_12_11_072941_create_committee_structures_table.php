<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('committee_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('committee_structures')->onDelete('cascade');
            
            // Structure Info
            $table->string('name'); // Nama divisi/seksi
            $table->string('code')->unique(); // Kode divisi (misal: DIV-ACARA)
            $table->text('description')->nullable();
            
            // Hierarchy
            $table->integer('level')->default(1); // 1=Ketua, 2=Wakil, 3=Divisi, 4=Seksi
            $table->integer('order')->default(0);
            
            // Leader Info
            $table->foreignId('leader_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('vice_leader_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Contact
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            
            // Status
            $table->enum('status', ['active', 'inactive'])->default('active');
            
            // Metadata
            $table->json('responsibilities')->nullable(); // Array tanggung jawab
            $table->json('authorities')->nullable(); // Array wewenang
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['event_id', 'parent_id', 'level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('committee_structures');
    }
};