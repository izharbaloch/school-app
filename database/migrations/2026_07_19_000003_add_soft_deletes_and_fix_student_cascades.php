<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Deleting a class/section/guardian used to hard-cascade through students,
     * and deleting a student used to hard-cascade through fees, payments, exam
     * results and attendance — permanently destroying financial and academic
     * history with no recovery path. This adds soft deletes to the records that
     * matter, and switches the class/section/guardian foreign keys from cascade
     * to restrict so they can't be deleted while students still reference them.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('student_fees', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('fee_payments', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('exam_results', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['guardian_id']);
            $table->dropForeign(['student_class_id']);
            $table->dropForeign(['section_id']);
        });

        DB::statement('ALTER TABLE students MODIFY user_id BIGINT UNSIGNED NULL');

        Schema::table('students', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('guardian_id')->references('id')->on('guardians')->restrictOnDelete();
            $table->foreign('student_class_id')->references('id')->on('student_classes')->restrictOnDelete();
            $table->foreign('section_id')->references('id')->on('sections')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['guardian_id']);
            $table->dropForeign(['student_class_id']);
            $table->dropForeign(['section_id']);
        });

        DB::statement('ALTER TABLE students MODIFY user_id BIGINT UNSIGNED NOT NULL');

        Schema::table('students', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('guardian_id')->references('id')->on('guardians')->cascadeOnDelete();
            $table->foreign('student_class_id')->references('id')->on('student_classes')->cascadeOnDelete();
            $table->foreign('section_id')->references('id')->on('sections')->cascadeOnDelete();
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('student_fees', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('fee_payments', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('exam_results', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
