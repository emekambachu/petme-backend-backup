<?php

namespace Database\Seeders;

use App\Models\Shop\ShopItem;
use App\Models\Shop\ShopItemDiscount;
use App\Models\Shop\ShopItemImage;
use Illuminate\Database\Seeder;

class ShopItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        ShopItem::factory(6)->create()->each(function ($item) {
            $item->images()->saveMany(ShopItemImage::factory(3)->make());
            $item->discount()->saveMany(ShopItemDiscount::factory(1)->make());
        });
    }
}
