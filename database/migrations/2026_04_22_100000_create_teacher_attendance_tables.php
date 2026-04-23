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
        // Create teacher_attendance_dates table first
        Schema::create('teacher_attendance_dates', function (Blueprint $table) {
            $table->id();

            $table->date('attendance_date')->index();
            $table->foreignId('taken_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->unique('attendance_date');
        });

        // Create attendance_teachers table
        Schema::create('attendance_teachers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('attendance_date_id')->nullable()->constrained('teacher_attendance_dates')->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained()->nullOnDelete();

            $table->enum('status', ['present', 'absent', 'leave', 'late'])->default('present');
            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->unique(['attendance_date_id', 'teacher_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_teachers');
        Schema::dropIfExists('teacher_attendance_dates');
    }
};
