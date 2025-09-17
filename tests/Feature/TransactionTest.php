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
            'amount' => 1000.00,
            'description' => 'Renda'
        ], $this->headers);

        $response->assertCreated();
        $this->assertEquals(1000, $response->json('amount'));
        $this->assertEquals('Renda', $response->json('description'));
    }

    public function testSuccessTransferCreation(): void
    {

        $response = $this->post('/api/accounts/transactions/transfers', [
            'receiverAccountId' => 2,
            'amount' => 1000.00,
            'type' => TransferTypeEnum::DEBIT->value,
            'description' => 'Smash Burguer'
        ], $this->headers);

        $response->assertCreated();
        $this->assertEquals('Smash Burguer', $response->json('description'));
        $this->assertEquals($this->accounts->first()['id'], $response->json('senderAccountId'));
        $this->assertEquals(2, $response->json('receiverAccountId'));
        $this->assertEquals(1000, $response->json('amount'));
    }

    public function testFailByAutoTransfer(): void
    {
        $response = $this->post('/api/accounts/transactions/transfers', [
            'receiverAccountId' => $this->accounts->first()['id'],
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

        foreach ($response->json('content') as $transaction) {
            $this->assertEquals($this->accounts->first()['id'], $transaction['accountId']);
            $this->assertNotNull($transaction['amount']);
            $this->assertNotNull($transaction['type']);
        }
    }

    public function testGetAllTransactions(): void
    {
        $response = $this->get('/api/transactions', $this->headers);

        $this->assertCount(15, $response->json('content'));

        foreach ($response->json('content') as $transaction) {
            $this->assertNotNull($transaction['accountId']);
            $this->assertNotNull($transaction['amount']);
            $this->assertNotNull($transaction['type']);
        }
    }
}
