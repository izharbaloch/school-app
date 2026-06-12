<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('title', 200);
            $table->enum('incident_type', ['warning', 'detention', 'suspension', 'expulsion', 'misconduct', 'other'])->default('other');
            $table->enum('severity', ['minor', 'moderate', 'severe'])->default('minor');
            $table->date('incident_date');
            $table->text('description');
            $table->text('action_taken')->nullable();
            $table->date('suspension_from')->nullable();
            $table->date('suspension_to')->nullable();
            $table->enum('status', ['open', 'resolved', 'closed'])->default('open');
            $table->text('resolution_notes')->nullable();
            $table->foreignId('reported_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_incidents');
    }
};
