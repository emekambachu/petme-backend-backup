<?php

namespace Database\Factories\User;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->firstName().' '.$this->faker->unique()->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'mobile' => $this->faker->unique()->phoneNumber(),
            'address' => $this->faker->address(),
            'image' => 'https://via.placeholder.com/200',
            'image_path' => null,
            'email_verified_at' => now(),
            'password' => Hash::make('11111111'),
            'remember_token' => Str::random(10),
            'last_login' => $this->faker->dateTime(),
            'verification_token' => Str::random(10),
            'status' => 'verified',
        ];
    }
}
