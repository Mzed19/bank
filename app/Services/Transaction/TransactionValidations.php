<?php

namespace App\Services\Transaction;

use App\Helpers\TransactionHelper;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class TransactionValidations
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
        $userBalance = Transaction::getUserBalance(userId: Auth::User()->id);

        if ($amount > $userBalance) {
            $userBalanceInCurrencyFormat = TransactionHelper::toCurrency($userBalance);

            throw new UnprocessableEntityHttpException(
                "Saldo insuficiente para realizar a transferência. Seu saldo disponível é $userBalanceInCurrencyFormat"
            );
        }
    }
}
