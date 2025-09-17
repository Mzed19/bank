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
            throw new UnprocessableEntityHttpException('Auto transfers are not allowed.');
        }
    }

    private function blockUnavailableAmount(float $amount): void
    {
        $accountBalance = Transaction::getAccountBalance(accountId: Auth::User()->id);

        if ($amount > $accountBalance) {
            $accountBalanceInCurrencyFormat = TransactionHelper::toCurrency($accountBalance);

            throw new UnprocessableEntityHttpException(
                "Insuficient balance. Your balance is $accountBalanceInCurrencyFormat"
            );
        }
    }
}
