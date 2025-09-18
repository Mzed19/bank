<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    public $headers = ['Accept' => 'application/json'];
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('key:generate');
        Artisan::call('migrate');
    }

    public function getJWTToken(string $document, string $password): string
    {
        $response = $this->post('/api/login', [
            'document' => $document,
            'password' => $password
        ], $this->headers);

        return $response->json('token');
    }
}
