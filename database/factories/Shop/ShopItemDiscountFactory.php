<?php

namespace Database\Factories\Shop;

use Illuminate\Database\Eloquent\Factories\Factory;

class ShopItemDiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'percent' => $this->faker->numberBetween(1, 20),
        ];
    }
}
