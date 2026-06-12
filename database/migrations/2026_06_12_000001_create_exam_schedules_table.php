<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->foreignId('student_class_id')->constrained('student_classes')->cascadeOnDelete();
            $table->foreignId('section_id')->nullable()->constrained('sections')->nullOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room', 100)->nullable();
            $table->text('remarks')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            // One subject per exam per class/section combination
            $table->unique(
                ['exam_id', 'student_class_id', 'section_id', 'subject_id'],
                'exam_schedule_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_schedules');
    }
};
