<?php

namespace Database\Factories\Pet;

use Illuminate\Database\Eloquent\Factories\Factory;

class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'pet_type_id' => $this->faker->numberBetween(1, 4),
            'name' => $this->faker->firstName(),
            'distinguishing_marks' => $this->faker->paragraph(),
            'photo' => $this->faker->imageUrl(150, 150),
            'gender' => $this->faker->randomElement(array ('male','female')),
            'dob' => $this->faker->date(),
            'registration_number' => $this->faker->randomNumber(NULL, false),
        ];
    }
}
