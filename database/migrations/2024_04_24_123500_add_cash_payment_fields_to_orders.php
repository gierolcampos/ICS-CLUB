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
        Schema::table('orders', function (Blueprint $table) {
            // Add cash payment fields
            if (!Schema::hasColumn('orders', 'officer_in_charge')) {
                $table->string('officer_in_charge')->nullable();
            }
            if (!Schema::hasColumn('orders', 'receipt_control_number')) {
                $table->string('receipt_control_number')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'officer_in_charge',
                'receipt_control_number'
            ]);
        });
    }
}; 