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
        Schema::table('members', function (Blueprint $table) {
            // First check if columns don't exist before adding them
            if (!Schema::hasColumn('members', 'full_name')) {
                $table->string('full_name')->nullable();
            }
            
            if (!Schema::hasColumn('members', 'contact_number')) {
                $table->string('contact_number')->nullable();
            }
            
            if (!Schema::hasColumn('members', 'role')) {
                $table->string('role')->default('Member')->nullable();
            }
            
            if (!Schema::hasColumn('members', 'bio')) {
                $table->text('bio')->nullable();
            }
            
            if (!Schema::hasColumn('members', 'date_joined')) {
                $table->date('date_joined')->nullable();
            }
            
            // Make sure membership_status is properly set
            if (Schema::hasColumn('members', 'membership_status')) {
                // This is a bit tricky as we can't directly modify enum columns
                // We'll do this if needed in a separate migration
            } else {
                $table->enum('membership_status', ['Active', 'Inactive', 'Pending'])->default('Pending')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            // Only drop columns that we added
            $columnsToCheck = ['full_name', 'contact_number', 'role', 'bio', 'date_joined'];
            
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('members', $column)) {
                    $table->dropColumn($column);
                }
            }
            
            // We won't touch the membership_status column in the down method
            // to avoid data loss
        });
    }
};
