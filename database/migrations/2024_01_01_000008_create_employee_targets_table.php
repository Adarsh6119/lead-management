<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->integer('month');
            $table->integer('year');
            $table->integer('lead_target')->default(50);
            $table->integer('booking_target')->default(10);
            $table->decimal('revenue_target', 12, 2)->default(100000.00);
            $table->timestamps();

            $table->unique(['employee_id', 'month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_targets');
    }
};
