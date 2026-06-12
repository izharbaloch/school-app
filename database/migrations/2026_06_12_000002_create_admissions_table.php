<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->string('application_no')->unique();

            // Applicant info
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('date_of_birth')->nullable();

            // Guardian / parent info
            $table->string('father_name');
            $table->string('mother_name')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->string('guardian_email')->nullable();
            $table->string('guardian_cnic_no')->nullable();
            $table->text('address')->nullable();

            // Application details
            $table->foreignId('applied_class_id')->constrained('student_classes')->cascadeOnDelete();
            $table->foreignId('applied_section_id')->nullable()->constrained('sections')->nullOnDelete();
            $table->string('academic_year');
            $table->string('previous_school')->nullable();
            $table->text('remarks')->nullable();

            // Workflow status
            $table->enum('status', ['pending', 'under_review', 'accepted', 'rejected', 'enrolled'])
                  ->default('pending');
            $table->text('rejection_reason')->nullable();

            // Audit fields
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('enrolled_student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};
