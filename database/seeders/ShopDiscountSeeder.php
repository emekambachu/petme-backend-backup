<?php

namespace Database\Seeders;

use App\Models\Admin\Admin;
use App\Models\Shop\ShopDiscount;
use Illuminate\Database\Seeder;

class ShopDiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShopDiscount::factory()->count(3)->create();
    }
}
