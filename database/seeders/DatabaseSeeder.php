<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AccountSeeder::class,
        ]);
    }
}
