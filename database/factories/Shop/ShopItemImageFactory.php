<?php

namespace Database\Factories\Shop;

use Illuminate\Database\Eloquent\Factories\Factory;

class ShopItemImageFactory extends Factory
{
    protected $imagePath = 'photos/shop/items';
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'image' => 'https://via.placeholder.com/200',
            'image_path' => @config('app.url').'/'.$this->imagePath.'/'
        ];
    }
}
