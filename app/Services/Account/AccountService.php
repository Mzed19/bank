<?php

namespace App\Services\Account;

use App\Helpers\TransactionHelper;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class AccountService
{
    public function me(): array
    {
        $loggedAccount = Auth::user();
        $balance = Transaction::getAccountBalance($loggedAccount->id);

        return [
            'accountId' => $loggedAccount->id,
            'document' => $loggedAccount->document,
            'balance' => $balance,
            'balanceFormatted' => TransactionHelper::toCurrency($balance)
        ];
    }
}
