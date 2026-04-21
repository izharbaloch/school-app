<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Section;
use App\Models\Guardian;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $classes = StudentClass::all();
        $sections = Section::all();

        foreach ($classes as $class) {
            foreach ($sections as $section) {
                // Create 10 student-guardian pairs for each class/section combination
                for ($i = 0; $i < 10; $i++) {
                    // Step 1: Create Guardian User
                    $guardianUserData = Guardian::factory()->make();
                    $guardianUser = User::create([
                        'name'     => $guardianUserData->father_name,
                        'email'    => $guardianUserData->email,
                        'password' => Hash::make('password'),
                    ]);

                    // Assign parent role to guardian user
                    $parentRole = Role::where('name', 'parent')->first();
                    if ($parentRole) {
                        $guardianUser->assignRole($parentRole->name);
                    }

                    // Step 2: Create Guardian and link to User
                    $guardian = Guardian::create([
                        'user_id'           => $guardianUser->id,
                        'father_name'       => $guardianUserData->father_name,
                        'mother_name'       => $guardianUserData->mother_name,
                        'guardian_phone'    => $guardianUserData->guardian_phone,
                        'guardian_cnic_no'  => $guardianUserData->guardian_cnic_no,
                        'email'             => $guardianUserData->email,
                        'address'           => $guardianUserData->address,
                        'status'            => 1,
                    ]);

                    // Step 3: Create Student User
                    $studentData = Student::factory()->make();
                    $studentUser = User::create([
                        'name'     => $studentData->first_name . ' ' . $studentData->last_name,
                        'email'    => $studentData->email,
                        'password' => Hash::make('password'),
                    ]);

                    // Assign student role to student user
                    $studentRole = Role::where('name', 'student')->first();
                    if ($studentRole) {
                        $studentUser->assignRole($studentRole->name);
                    }

                    // Step 4: Create Student and link to User and Guardian
                    Student::create([
                        'user_id'           => $studentUser->id,
                        'guardian_id'       => $guardian->id,
                        'admission_no'      => $studentData->admission_no,
                        'roll_no'           => $studentData->roll_no,
                        'first_name'        => $studentData->first_name,
                        'last_name'         => $studentData->last_name,
                        'gender'            => $studentData->gender,
                        'date_of_birth'     => $studentData->date_of_birth,
                        'phone'             => $studentData->phone,
                        'email'             => $studentData->email,
                        'father_name'       => $studentData->father_name,
                        'mother_name'       => $studentData->mother_name,
                        'guardian_phone'    => $guardian->guardian_phone,
                        'guardian_cnic_no'  => $guardian->guardian_cnic_no,
                        'address'           => $studentData->address,
                        'admission_date'    => now(),
                        'student_class_id'  => $class->id,
                        'section_id'        => $section->id,
                        'status'            => 1,
                    ]);
                }
            }
        }
    }
}
