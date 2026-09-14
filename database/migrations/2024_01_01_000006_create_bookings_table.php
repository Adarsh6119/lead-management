<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_id')->unique(); // Auto: CRS + timestamp
            $table->foreignId('lead_id')->nullable()->constrained('leads')->onDelete('set null');
            $table->date('date');
            $table->string('customer_name');
            $table->string('mobile_no', 15);
            $table->string('pickup_city')->nullable();
            $table->string('destination')->nullable();
            $table->date('pickup_date')->nullable();
            $table->string('pickup_time')->nullable();
            $table->date('return_date')->nullable();
            $table->string('trip_type')->default('one_way');
            $table->string('cab_type')->nullable();
            $table->text('reporting_address')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_mobile')->nullable();
            $table->string('cab_number')->nullable();
            $table->decimal('rate', 10, 2)->default(0);
            $table->decimal('advance_payment', 10, 2)->default(0);
            $table->string('payment_mode')->default('UPI'); // Cash, UPI, Card, Bank Transfer
            $table->string('source_tag')->nullable();
            $table->string('booking_status')->default('Confirmed'); // Confirmed, Completed, Cancelled
            $table->foreignId('employee_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('employee_name')->nullable();
            $table->timestamps();

            $table->index('booking_status');
            $table->index('employee_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
