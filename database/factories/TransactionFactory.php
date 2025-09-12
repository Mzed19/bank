<?php

namespace Database\Factories;

use App\Enums\TransactionTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomBoolean = (mt_rand(0, 1) === 0);

        $type = $randomBoolean ? TransactionTypeEnum::DEPOSIT : TransactionTypeEnum::TRANSFER;

        return [
            'user_id' => 1,
            'amount' => $this->faker->randomFloat(),
            'type' => $type->value,
            'imported_id' => 1
        ];
    }
}
