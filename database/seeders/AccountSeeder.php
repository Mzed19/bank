<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Deposit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Account::factory(10)->has(Deposit::factory(5))->create();
    }
}
