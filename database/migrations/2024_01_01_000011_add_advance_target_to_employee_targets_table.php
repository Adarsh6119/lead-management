<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('employee_targets')) {
            Schema::table('employee_targets', function (Blueprint $table) {
                if (!Schema::hasColumn('employee_targets', 'advance_target')) {
                    $table->decimal('advance_target', 12, 2)->default(50000.00)->after('year');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('employee_targets')) {
            Schema::table('employee_targets', function (Blueprint $table) {
                if (Schema::hasColumn('employee_targets', 'advance_target')) {
                    $table->dropColumn('advance_target');
                }
            });
        }
    }
};
