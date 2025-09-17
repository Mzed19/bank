<?php

namespace Tests\Feature;

use Tests\TestCase;

class AccountTest extends TestCase
{
    public function testSuccessUserCreation(): void
    {
        $response = $this->post('/api/accounts', [
            'document' => '48306792041',
            'password' => 'password123',
            'balance' => 1000.00
        ]);

        $response->assertCreated();

        $this->assertEquals(1, $response->json('id'));
        $this->assertEquals('48306792041', $response->json('document'));
    }

    public function testFailUserCreationDueToDocumentAlreadyRegistered(): void
    {
        $this->post('/api/accounts', [
            'document' => '48306792041',
            'password' => 'password123',
            'balance' => 1000.00
        ]);

        $response = $this->post('/api/accounts', [
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
        $firstResponse = $this->post('/api/accounts', [
            'document' => '48306792041',
            'password' => 'password123',
            'balance' => 1000.00
        ]);

        $secondResponse = $this->post('/api/accounts', [
            'document' => '83684171042',
            'password' => 'password123',
            'balance' => 500.00
        ]);

        $response = $this->get('/api/accounts', $this->headers);

        $response->assertOk();

        $this->assertCount(2, $response->json('content'));
        $this->assertEquals(1, $firstResponse->json('id'));
        $this->assertEquals('48306792041', $firstResponse->json('document'));
        $this->assertEquals(2, $secondResponse->json('id'));
        $this->assertEquals('83684171042', $secondResponse->json('document'));
    }
}
