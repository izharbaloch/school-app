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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('guardian_id')->constrained('guardians')->onDelete('cascade');
            $table->string('admission_no')->unique();
            $table->string('roll_no')->nullable();

            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('date_of_birth')->nullable();

            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            $table->string('father_name');
            $table->string('mother_name')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->string('guardian_cnic_no')->unique()->nullable();
            $table->string('guardian_email')->nullable();

            $table->text('address')->nullable();
            $table->date('admission_date')->nullable();

            $table->foreignId('student_class_id')->constrained('student_classes')->onDelete('cascade');
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');

            $table->enum('status', ['active', 'inactive', 'pass_out', 'dropped', 'failed'])->default('active');
            $table->boolean('is_failed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
