<?php

namespace Database\Factories;

use App\Helpers\PersonDocumentHelper;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'document' => PersonDocumentHelper::generateRandomValidDocument(),
            'password' => Hash::make('password'),
        ];
    }
}
