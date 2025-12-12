<?php
// database/migrations/2024_01_10_200009_create_official_letters_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('official_letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
            
            // Letter Info
            $table->string('letter_number')->unique(); // 001/RM-1447H/XII/2024
            $table->enum('letter_type', [
                'invitation',
                'announcement',
                'notification',
                'request',
                'response',
                'thank_you',
                'cooperation',
                'recommendation',
                'other'
            ])->default('notification');
            
            $table->enum('direction', ['incoming', 'outgoing'])->default('outgoing');
            
            // Subject & Content
            $table->string('subject');
            $table->longText('content')->nullable();
            
            // Sender (for outgoing) or Source (for incoming)
            $table->foreignId('sender_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('sender_name')->nullable(); // For incoming letters
            $table->string('sender_organization')->nullable();
            
            // Recipient(s)
            $table->string('recipient_name');
            $table->string('recipient_organization')->nullable();
            $table->text('recipient_address')->nullable();
            $table->string('recipient_email')->nullable();
            $table->json('cc_recipients')->nullable(); // Carbon copy
            
            // Attachments
            $table->integer('attachment_count')->default(0);
            $table->json('attachment_list')->nullable();
            
            // References
            $table->string('reference_number')->nullable(); // Nomor surat yang dirujuk
            $table->foreignId('replied_to_letter')->nullable()->constrained('official_letters')->onDelete('set null');
            
            // Dates
            $table->date('letter_date');
            $table->date('received_date')->nullable(); // For incoming
            $table->date('sent_date')->nullable(); // For outgoing
            $table->date('due_date')->nullable(); // If requires response
            
            // Priority & Classification
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->enum('classification', ['public', 'internal', 'confidential', 'secret'])->default('internal');
            
            // Status
            $table->enum('status', [
                'draft',
                'pending_approval',
                'approved',
                'sent',
                'received',
                'archived'
            ])->default('draft');
            
            // Approval (for outgoing)
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            
            // Documents
            $table->string('letter_file')->nullable(); // PDF file
            $table->string('signed_file')->nullable(); // Signed PDF
            $table->json('supporting_files')->nullable();
            
            // Signature
            $table->foreignId('signatory')->nullable()->constrained('users')->onDelete('set null');
            $table->string('signatory_name')->nullable();
            $table->string('signatory_position')->nullable();
            $table->string('signature_file')->nullable();
            
            // Metadata
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->text('internal_notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['event_id', 'letter_type', 'direction']);
            $table->index(['status', 'letter_date']);
            $table->fullText(['subject', 'content']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('official_letters');
    }
};