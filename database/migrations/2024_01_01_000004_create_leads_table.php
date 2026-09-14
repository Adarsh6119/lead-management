<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->date('date_created');
            $table->string('source'); // IVR, Missed Call, Offer, Website, etc.
            $table->string('mobile_no', 15);
            $table->string('customer_name')->nullable();
            $table->string('pickup_city')->nullable();
            $table->string('destination')->nullable();
            $table->date('pickup_date')->nullable();
            $table->string('pickup_time')->nullable();
            $table->date('return_date')->nullable();
            $table->string('trip_type')->default('one_way'); // one_way, round_trip, local
            $table->string('cab_type')->nullable();
            $table->string('state')->default('Uttar Pradesh');
            $table->decimal('web_rate', 10, 2)->default(0);
            $table->decimal('discounted_rate', 10, 2)->default(0);
            $table->decimal('final_quoted_rate', 10, 2)->default(0);
            $table->decimal('offer_discount', 10, 2)->default(0);
            $table->boolean('quotation_sent')->default(false);
            $table->boolean('ticket_generated')->default(false);
            $table->boolean('app_download')->default(false);
            $table->date('last_followup_date')->nullable();
            $table->date('next_followup_date')->nullable();
            $table->string('status')->default('New Lead'); // New Lead, Follow Up, Confirm Booking, Booking Cancelled, Close / Lost
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->string('employee_name')->nullable();
            $table->timestamps();

            $table->index('mobile_no');
            $table->index('status');
            $table->index('source');
            $table->index('employee_id');
            $table->index('next_followup_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
