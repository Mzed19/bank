<?php

namespace App\Services\Login;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class LoginService
{
    public function execute(string $document, string $password): array
    {
        $token = Auth::attempt(['document' => $document, 'password' => $password]);

        if (!$token) {
            throw new UnprocessableEntityHttpException('Credenciais inválidas.');
        }

        $account = Auth::user();

        return [
            'token' => $token,
            'account' => [
                'id' => $account->id,
                'document' => $account->document,
            ],
        ];
    }
}
