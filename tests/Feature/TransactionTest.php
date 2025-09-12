<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testSuccessDepositCreation(): void
    {
        $response = $this->post('/api/deposit', [
            'userId' => $this->user->id,
            'document' => '48306792041',
            'amount' => 1000.00
        ], $this->headers);

        $response->assertCreated();
    }

    public function testSuccessTransferCreation(): void
    {
        $response = $this->post('/api/transaction/transfer', [
            'userId' => $this->user->id,
            'amount' => 1000.00
        ], $this->headers);

        $response->assertCreated();
    }
}
