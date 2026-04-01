<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Section;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $classes = StudentClass::all();
        $sections = Section::all();

        foreach ($classes as $class) {
            foreach ($sections as $section) {

                // har class + section mein 10 students
                Student::factory()->count(10)->create([
                    'student_class_id' => $class->id,
                    'section_id'       => $section->id,
                ]);
            }
        }
    }
}
