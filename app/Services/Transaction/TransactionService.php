<?php

namespace App\Services\Transaction;

use App\Enums\TransferTypeEnum;
use App\Models\Transfer;
use Illuminate\Support\Facades\Auth;

class TransactionService extends TransactionValidations
{
    public function transfer(float $amount, int $receiverId, TransferTypeEnum $type, ?string $description): Transfer
    {
        $this->validateTransfer(amount: $amount, receiverId: $receiverId);

        return Transfer::create([
            'sender_account_id' => Auth::User()->id,
            'receiver_account_id' => $receiverId,
            'amount' => $amount,
            'type' => $type->value,
            'description' => $description
        ]);
    }
}
