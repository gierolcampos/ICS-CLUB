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
        Schema::create('members', function (Blueprint $table) {
            $table->id('id_member');
            $table->string('student_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->date('date_of_birth');

            // Student Info
            $table->string('id_course');
            $table->string('id_major');
            $table->string('section');
            $table->enum('year_level', ['1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year', 'Graduate'])->nullable();

            // Contact Information
            $table->string('email')->unique();
            $table->string('phone_no')->nullable();
            $table->longText('profile_photo_path')->nullable();

            $table->integer('id_school_calendar');

            $table->timestamps();

            
            // $table->text('address');
            // $table->text('barangay');
            // $table->text('municipality');
            // $table->text('province')->nullable();

            // $table->enum('membership_status', ['Active', 'Inactive', 'Pending'])->default('Pending');
            // $table->date('membership_date')->nullable();
            // $table->date('membership_expiry')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
