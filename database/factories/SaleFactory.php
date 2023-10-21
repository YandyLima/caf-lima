<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => 1,
            'tracking' => json_encode([]),
            'amount_paid' => 50.43,
            'transaction_number' => fake()->randomDigit(10),
            'user_id' => \App\Models\User::factory(),
            'payment_type' => fake()->randomDigit(1,2),

        ];
    }
}
