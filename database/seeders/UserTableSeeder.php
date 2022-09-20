<?php

namespace Database\Seeders;

use App\Models\Pet\Pet;
use App\Models\User\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        User::factory(3)->create()->each(function ($user) {
            $user->pets()->saveMany(Pet::factory(2)->make());
        });
    }
}
