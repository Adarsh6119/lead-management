<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accountings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_id');
            $table->string('customer_name');
            $table->string('mobile_no', 15);
            $table->decimal('estimated_amount', 10, 2)->default(0);
            $table->decimal('advance', 10, 2)->default(0);
            $table->decimal('gst_on_advance', 10, 2)->default(0); // 5% of advance
            $table->decimal('igst_advance', 10, 2)->default(0);
            $table->decimal('cgst_advance', 10, 2)->default(0);
            $table->decimal('sgst_advance', 10, 2)->default(0);
            $table->decimal('pending', 10, 2)->default(0);
            $table->decimal('gst_on_pending', 10, 2)->default(0); // 5% of pending
            $table->decimal('igst_pending', 10, 2)->default(0);
            $table->decimal('cgst_pending', 10, 2)->default(0);
            $table->decimal('sgst_pending', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('total_gst', 10, 2)->default(0);
            $table->string('customer_state')->nullable();
            $table->string('payment_mode')->nullable(); // UPI, Bank Transfer, Cash
            $table->string('transaction_id')->nullable();
            $table->boolean('bank_reco_status')->default(false);
            $table->string('payment_status')->default('Advance Paid'); // Advance Paid, Fully Paid, Pending
            $table->foreignId('employee_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index('booking_id');
            $table->index('payment_status');
            $table->index('customer_state');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accountings');
    }
};
