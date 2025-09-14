<?php

namespace Tests\Feature;

use App\Enums\TransferTypeEnum;
use App\Models\Deposit;
use App\Models\Account;
use Illuminate\Support\Collection;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    private Collection $accounts;
    private string $firstAccountToken;

    public function setUp(): void
    {
        parent::setUp();

        $this->accounts = Account::factory(2)->has(Deposit::factory(10))->create();
        $this->getToken();
        $this->headers = array_merge($this->headers, ['Authorization: Bearer: ' => $this->firstAccountToken]);
    }

    private function getToken(): void
    {
        $response = $this->post('/api/login', [
            'document' => $this->accounts->first()['document'],
            'password' => 'password'
        ], $this->headers);

        $this->firstAccountToken = $response->json('token');
    }

    public function testSuccessDepositCreation(): void
    {
        $response = $this->post('/api/deposits', [
            'receiverAccountId' => 1,
            'document' => '48306792041',
            'amount' => 1000.00
        ], $this->headers);

        $response->assertCreated();
    }

    public function testSuccessTransferCreation(): void
    {
        $response = $this->post('/api/accounts/transactions/transfers', [
            'receiverAccountId' => 2,
            'amount' => 1000.00,
            'type' => TransferTypeEnum::DEBIT->value
        ], $this->headers);

        $response->assertCreated();
    }

    public function testFailByAutoTransfer(): void
    {
        $response = $this->post('/api/accounts/transactions/transfers', [
            'receiverAccountId' => 1,
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
        $response = $this->post('/api/accounts/transactions/transfers', [
            'receiverAccountId' => 2,
            'amount' => 9999999999999.00,
            'type' => TransferTypeEnum::DEBIT->value
        ], $this->headers);

        $response->assertUnprocessable();

        $this->assertStringContainsString(
            'Saldo insuficiente para realizar a transferência. Seu saldo disponível é ',
            $response->json('error')
        );
    }

    public function testLoggedAccountTransactions(): void
    {
        $response = $this->get('/api/accounts/transactions', $this->headers);

        $this->assertCount(10, $response->json('content'));
    }

    public function testGetAllTransactions(): void
    {
        $response = $this->get('/api/transactions', $this->headers);

        $this->assertCount(15, $response->json('content'));
    }
}
