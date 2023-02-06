<?php

namespace Database\Seeders;

use App\Models\Cake;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cakes = [
            [
                'id' => fake()->unique()->uuid(),
                'name' => 'Chocolate',
                'price' => '15.0',
                'weight' => rand(1, 999),
                'quantity' => 0,//rand(0, 99999)
            ],
            [
                'id' => fake()->unique()->uuid(),
                'name' => 'Strawberry',
                'price' => '13.0',
                'weight' => rand(1, 999),
                'quantity' => 0,//rand(0, 99999)
            ],
            [
                'id' => fake()->unique()->uuid(),
                'name' => 'Vanilla',
                'price' => '12.0',
                'weight' => rand(1, 999),
                'quantity' => 0,//rand(0, 99999)
            ],
            [
                'id' => fake()->unique()->uuid(),
                'name' => 'Apple',
                'price' => '20.0',
                'weight' => rand(1, 999),
                'quantity' => 0,//rand(0, 99999)
            ],
        ];

        Cake::insert($cakes);
    }
}
