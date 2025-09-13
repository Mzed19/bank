<?php

namespace Tests\Feature;

use App\Enums\TransferTypeEnum;
use App\Models\Deposit;
use App\Models\User;
use Illuminate\Support\Collection;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    private Collection $users;
    private string $firstUserToken;

    public function setUp(): void
    {
        parent::setUp();

        $this->users = User::factory()->count(2)->has(Deposit::factory()->count(10))->create();
        $this->headers = array_merge($this->headers, ['Authorization: Bearer: ' => $this->getToken()]);
    }

    private function getToken(): void
    {
        $response = $this->post('/api/login', [
            'document' => $this->users->first()['document'],
            'password' => 'password'
        ], $this->headers);

        $this->firstUserToken = $response->json('token');
    }

    public function testSuccessDepositCreation(): void
    {
        $response = $this->post('/api/deposit', [
            'receiverId' => 1,
            'document' => '48306792041',
            'amount' => 1000.00
        ], $this->headers);

        $response->assertCreated();
    }

    public function testSuccessTransferCreation(): void
    {
        $response = $this->post('/api/transaction/transfer', [
            'receiverId' => 2,
            'amount' => 1000.00,
            'type' => TransferTypeEnum::DEBIT->value
        ], $this->headers);

        $response->assertCreated();
    }

    public function testFailByAutoTransfer(): void
    {
        $response = $this->post('/api/transaction/transfer', [
            'receiverId' => 1,
            'amount' => 10.00,
            'type' => TransferTypeEnum::DEBIT->value
        ], $this->headers);

        $response->assertUnprocessable();

        $this->assertEquals(
            'Transferências para o próprio usuário não são permitidas.',
            $response->json('error')
        );
    }

    public function testFailByUnavailableAmount(): void
    {
        $response = $this->post('/api/transaction/transfer', [
            'receiverId' => 2,
            'amount' => 9999999999999.00,
            'type' => TransferTypeEnum::DEBIT->value
        ], $this->headers);

        $response->assertUnprocessable();

        $this->assertStringContainsString(
            'Saldo insuficiente para realizar a transferência. Seu saldo disponível é ',
            $response->json('error')
        );
    }
}
