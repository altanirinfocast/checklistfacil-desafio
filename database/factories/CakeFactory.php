<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cake>
 */
class CakeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $fake_locale = fake('pt_BR');
        return [
            'name' => $fake_locale->name(),
            'price' => rand(100, 9999)/100,
            'weight' => rand(1, 999),
            'quantity' => rand(0, 99999)
        ];
    }
}
