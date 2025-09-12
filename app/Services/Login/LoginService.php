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

        $user = Auth::user();

        return [
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'document' => $user->document,
            ],
        ];
    }
}
