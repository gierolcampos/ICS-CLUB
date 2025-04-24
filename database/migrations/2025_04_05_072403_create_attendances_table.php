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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->integer('event_id');
            $table->integer('member_id');
            $table->enum('status', ['Going', 'Maybe', 'Not Going'])->default('Going');

            $table->boolean('has_attended')->nullable();
            $table->integer('verified_by');     // FK of table members->id_member

            $table->string('remarks')->nullable();
            $table->timestamps();
            
            // Make event_id and member_id combination unique
            $table->unique(['event_id', 'member_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
