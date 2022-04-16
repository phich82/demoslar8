<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'items' => $this->faker->randomElement([
                '1,2,3', '2,4,5', '6,7,9', '5,6,1', '3,5,7',
                '9,2,3', '2,9,5', '6,7,9', '5,9,1', '3,9,7',
                '8,2,3', '8,4,5', '8,7,9', '5,8,1', '8,5,7',
            ]),
            'vat' => 0.01,
            'subtotal' => 1000,
            'total' => 1010,
            'user_id' => $this->faker->numberBetween(1, 1000),
        ];
    }
}
