<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->renumberDuplicateRollNumbers();

        Schema::table('students', function (Blueprint $table) {
            $table->unique(['student_class_id', 'roll_no']);
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropUnique(['student_class_id', 'roll_no']);
        });
    }

    /**
     * Existing seeded/demo data can contain duplicate roll numbers within the
     * same class (nothing previously enforced uniqueness). Renumber each
     * class's students sequentially so the new unique index can be applied
     * without failing on pre-existing data.
     */
    private function renumberDuplicateRollNumbers(): void
    {
        $classIds = DB::table('students')->distinct()->pluck('student_class_id');

        foreach ($classIds as $classId) {
            $students = DB::table('students')
                ->where('student_class_id', $classId)
                ->orderByRaw('CAST(roll_no AS UNSIGNED) IS NULL, CAST(roll_no AS UNSIGNED), id')
                ->get(['id', 'roll_no']);

            $rollNumbers = $students->pluck('roll_no')->filter()->map(fn($n) => (string) $n);
            $hasDuplicates = $rollNumbers->count() !== $rollNumbers->unique()->count();

            if (!$hasDuplicates) {
                continue;
            }

            foreach ($students as $index => $student) {
                DB::table('students')
                    ->where('id', $student->id)
                    ->update(['roll_no' => $index + 1]);
            }
        }
    }
};
