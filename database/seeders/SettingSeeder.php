<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\StudentClass;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            /*
            |--------------------------------------------------------------------------
            | 1) Seed Student Classes
            |--------------------------------------------------------------------------
            */
            $classesData = [
                ['name' => 'Class 1', 'numeric_name' => 1, 'status' => 1],
                ['name' => 'Class 2', 'numeric_name' => 2, 'status' => 1],
                ['name' => 'Class 3', 'numeric_name' => 3, 'status' => 1],
                ['name' => 'Class 4', 'numeric_name' => 4, 'status' => 1],
                ['name' => 'Class 5', 'numeric_name' => 5, 'status' => 1],
            ];

            $classes = [];

            foreach ($classesData as $classData) {
                $class = StudentClass::create($classData);
                $classes[$class->numeric_name] = $class;
            }

            /*
            |--------------------------------------------------------------------------
            | 2) Seed Sections Directly With class_id
            |--------------------------------------------------------------------------
            */
            $sectionsData = [
                1 => ['A', 'B', 'C', 'D'],
                2 => ['A', 'B', 'C', 'D'],
                3 => ['A', 'B'],
                4 => ['A', 'B'],
                5 => ['A'],
            ];

            foreach ($sectionsData as $classNumeric => $sectionNames) {
                foreach ($sectionNames as $sectionName) {
                    Section::create([
                        'class_id' => $classes[$classNumeric]->id,
                        'name'     => $sectionName,
                        'room_no'  => null,
                        'status'   => 1,
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 3) Seed Subjects
            |--------------------------------------------------------------------------
            */
            $subjectsData = [
                ['name' => 'Urdu', 'code' => 'URD', 'status' => 1],
                ['name' => 'English', 'code' => 'ENG', 'status' => 1],
                ['name' => 'Math', 'code' => 'MTH', 'status' => 1],
                ['name' => 'Science', 'code' => 'SCI', 'status' => 1],
                ['name' => 'Islamiyat', 'code' => 'ISL', 'status' => 1],
                ['name' => 'Computer', 'code' => 'CMP', 'status' => 1],
            ];

            $subjects = [];

            foreach ($subjectsData as $subjectData) {
                $subject = Subject::create($subjectData);
                $subjects[$subject->name] = $subject;
            }

            /*
            |--------------------------------------------------------------------------
            | 4) Assign Subjects to Classes
            |--------------------------------------------------------------------------
            */
            $classSubjectData = [
                1 => ['Urdu', 'English', 'Math'],
                2 => ['Urdu', 'English', 'Math', 'Islamiyat'],
                3 => ['Urdu', 'English', 'Math', 'Science'],
                4 => ['Urdu', 'English', 'Math', 'Science', 'Computer'],
                5 => ['Urdu', 'English', 'Math', 'Science', 'Computer', 'Islamiyat'],
            ];

            foreach ($classSubjectData as $classNumeric => $subjectNames) {
                $subjectIds = collect($subjectNames)
                    ->map(fn($name) => $subjects[$name]->id)
                    ->toArray();

                $classes[$classNumeric]->subjects()->syncWithoutDetaching($subjectIds);
            }
        });
    }
}
