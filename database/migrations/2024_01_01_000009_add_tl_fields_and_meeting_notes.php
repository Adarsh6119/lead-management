<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add TL suggestion fields to leads table
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'tl_note')) {
                $table->text('tl_note')->nullable()->after('status');
                $table->string('tl_note_by')->nullable()->after('tl_note');
                $table->timestamp('tl_note_at')->nullable()->after('tl_note_by');
            }
        });

        // Create 1-on-1 employee meeting notes table
        if (!Schema::hasTable('employee_meeting_notes')) {
            Schema::create('employee_meeting_notes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('tl_id')->constrained('users')->onDelete('cascade');
                $table->date('meeting_date');
                
                // Evaluation Checkpoints
                $table->boolean('quotation_not_sending')->default(false);
                $table->boolean('images_not_sending')->default(false);
                $table->boolean('followup_not_regular')->default(false);
                $table->boolean('cannot_convince_customer')->default(false);
                $table->boolean('not_providing_discount')->default(false);
                $table->boolean('conversation_not_good')->default(false);
                
                $table->text('remarks')->nullable();
                $table->timestamps();

                $table->index('employee_id');
                $table->index('meeting_date');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_meeting_notes');

        Schema::table('leads', function (Blueprint $table) {
            if (Schema::hasColumn('leads', 'tl_note')) {
                $table->dropColumn(['tl_note', 'tl_note_by', 'tl_note_at']);
            }
        });
    }
};
