<?php

namespace App\Services\Transaction;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class Validations
{
    public function validateTransfer(float $amount, int $receiverId): void
    {
        $this->blockAutoTransfer(receiverId: $receiverId);
        $this->blockUnavailableAmount(amount: $amount);
    }

    private function blockAutoTransfer(int $receiverId): void
    {
        if (Auth::User()->id === $receiverId) {
            throw new UnprocessableEntityHttpException('Transferências para o próprio usuário não são permitidas.');
        }
    }

    private function blockUnavailableAmount(float $amount): void
    {
        if ($amount > Transaction::getUserBalance(userId: Auth::User()->id)) {
            throw new UnprocessableEntityHttpException('Saldo insuficiente para realizar a transferência.');
        }
    }
}
