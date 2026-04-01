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

            $sections = ['A', 'B', 'C', 'D'];

            foreach ($sections as $section) {
                Section::create([
                    'name'   => $section,
                    'status' => 1,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 3) Seed Subjects
            |--------------------------------------------------------------------------
            */
            $subjectsData = [
                ['name' => 'Urdu', 'status' => 1],
                ['name' => 'English', 'status' => 1],
                ['name' => 'Math', 'status' => 1],
                ['name' => 'Science', 'status' => 1],
                ['name' => 'Islamiyat', 'status' => 1],
                ['name' => 'Computer', 'status' => 1],
            ];

            $subjects = [];

            foreach ($subjectsData as $subjectData) {
                $subject = Subject::create($subjectData);
                $subjects[$subject->name] = $subject;
            }

            // /*
            // |--------------------------------------------------------------------------
            // | 4) Assign Subjects to Classes
            // |--------------------------------------------------------------------------
            // */
            // $classSubjectData = [
            //     1 => ['Urdu', 'English', 'Math'],
            //     2 => ['Urdu', 'English', 'Math', 'Islamiyat'],
            //     3 => ['Urdu', 'English', 'Math', 'Science'],
            //     4 => ['Urdu', 'English', 'Math', 'Science', 'Computer'],
            //     5 => ['Urdu', 'English', 'Math', 'Science', 'Computer', 'Islamiyat'],
            // ];

            // foreach ($classSubjectData as $classNumeric => $subjectNames) {
            //     $subjectIds = collect($subjectNames)
            //         ->map(fn($name) => $subjects[$name]->id)
            //         ->toArray();

            //     $classes[$classNumeric]->subjects()->syncWithoutDetaching($subjectIds);
            // }
        });
    }
}
