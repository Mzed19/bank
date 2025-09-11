<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserTest extends TestCase
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
}
