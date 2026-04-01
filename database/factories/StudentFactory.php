<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Section;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            // like this formate ADM-0001-year
            'admission_no' => 'ADM-' . $this->faker->unique()->numerify('####') . '-' . now()->year,
            'roll_no'      => $this->faker->numberBetween(1, 50),

            'first_name'   => $this->faker->firstName,
            'last_name'    => $this->faker->lastName,
            'gender'       => $this->faker->randomElement(['male', 'female']),
            'date_of_birth'=> $this->faker->date(),

            'phone'        => $this->faker->phoneNumber,
            'email'        => $this->faker->safeEmail,

            'father_name'  => $this->faker->name,
            'mother_name'  => $this->faker->name,
            'guardian_phone' => $this->faker->phoneNumber,
            'guardian_cnic_no' => $this->faker->numerify('#####-#######-#'),

            'address'      => $this->faker->address,
            'admission_date' => now(),

            // default (override karenge seeder mein)
            'student_class_id' => StudentClass::inRandomOrder()->first()->id ?? 1,
            'section_id'       => Section::inRandomOrder()->first()->id ?? 1,

            'status'       => 1,
        ];
    }
}
