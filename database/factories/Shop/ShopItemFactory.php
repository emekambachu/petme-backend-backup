<?php

namespace Database\Factories\Shop;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ShopItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'quantity' => $this->faker->numberBetween(1, 10),
            'shop_category_id' => $this->faker->numberBetween(1, 4),
            'shop_metric_id' => $this->faker->numberBetween(1, 3),
            'cost' => $this->faker->randomFloat(2, 5, 50),
            'status' => 'published',
        ];
    }
}
