<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sports_activities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->enum('category', ['sport', 'club', 'art', 'other'])->default('sport');
            $table->text('description')->nullable();
            $table->string('coach_name', 150)->nullable();
            $table->string('venue', 200)->nullable();
            $table->string('schedule', 200)->nullable();
            $table->smallInteger('max_members')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('student_activity_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('sports_activity_id')->constrained('sports_activities')->cascadeOnDelete();
            $table->enum('role', ['member', 'captain', 'coordinator'])->default('member');
            $table->date('joined_date');
            $table->text('notes')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->unique(['student_id', 'sports_activity_id'], 'sae_student_activity_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_activity_enrollments');
        Schema::dropIfExists('sports_activities');
    }
};
