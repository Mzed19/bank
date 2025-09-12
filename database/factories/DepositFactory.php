<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deposit>
 */
class DepositFactory extends Factory
{
    public function definition(): array
    {
        return [
            'receiver_id' => User::factory(),
            'amount' => $this->faker->randomFloat(2, 100),
            'description' => $this->faker->text(200)
        ];
    }
}
