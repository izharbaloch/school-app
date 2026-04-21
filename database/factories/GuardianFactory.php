<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Guardian;

class GuardianFactory extends Factory
{
    protected $model = Guardian::class;

    public function definition(): array
    {
        return [
            'user_id'           => null, // will be set in seeder
            'father_name'       => $this->faker->name('male'),
            'mother_name'       => $this->faker->name('female'),
            'guardian_phone'    => $this->faker->phoneNumber,
            'guardian_cnic_no'  => $this->faker->numerify('#####-#######-#'),
            'email'             => $this->faker->safeEmail,
            'address'           => $this->faker->address,
            'status'            => 1,
        ];
    }
}
