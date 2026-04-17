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
        Schema::create('student_promotions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')->constrained()->cascadeOnDelete();

            $table->foreignId('from_class_id')->constrained('student_classes');
            $table->foreignId('to_class_id')->constrained('student_classes');

            $table->foreignId('from_section_id')->nullable()->constrained('sections');
            $table->foreignId('to_section_id')->nullable()->constrained('sections');

            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_promotions');
    }
};
