<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_class_id')->constrained('student_classes')->cascadeOnDelete();
            $table->foreignId('section_id')->nullable()->constrained('sections')->nullOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
            $table->tinyInteger('day_of_week'); // 1=Mon, 2=Tue ... 7=Sun
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->unique(['student_class_id', 'section_id', 'day_of_week', 'start_time'], 'timetable_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timetables');
    }
};
