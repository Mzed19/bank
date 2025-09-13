<?php

namespace Tests\Feature;

use Tests\TestCase;

class AccountTest extends TestCase
{
    public function testSuccessUserCreation(): void
    {
        $response = $this->post('/api/user', [
            'document' => '48306792041',
            'password' => 'password123',
            'balance' => 1000.00
        ]);

        $response->assertCreated();
    }

    public function testFailUserCreationDueToDocumentAlreadyRegistered(): void
    {
        $this->post('/api/user', [
            'document' => '48306792041',
            'password' => 'password123',
            'balance' => 1000.00
        ]);

        $response = $this->post('/api/user', [
            'document' => '48306792041',
            'password' => 'password123',
            'balance' => 1000.00
        ], $this->headers);

        $response->assertUnprocessable();

        $this->assertStringContainsString(
            'The document has already been taken.',
            $response->getContent()
        );
    }

    public function testListAllAccounts(): void
    {
        $this->post('/api/user', [
            'document' => '48306792041',
            'password' => 'password123',
            'balance' => 1000.00
        ]);

        $this->post('/api/user', [
            'document' => '83684171042',
            'password' => 'password123',
            'balance' => 500.00
        ]);

        $response = $this->get('/api/user', $this->headers);

        $response->assertOk();

        $accounts = json_decode($response->getContent());

        $this->assertCount(2, $accounts);
    }
}
