<?php

namespace App\Services\Transaction;

use App\Enums\TransferTypeEnum;
use App\Models\Transfer;
use Illuminate\Support\Facades\Auth;

class TransactionService extends Validations
{
    public function createTransfer(float $amount, int $receiverId, TransferTypeEnum $type, ?string $description): Transfer
    {
        $this->validateTransfer(amount: $amount, receiverId: $receiverId);

        return Transfer::create([
            'sender_id' => Auth::User()->id,
            'receiver_id' => $receiverId,
            'amount' => $amount,
            'type' => $type->value,
            'description' => $description
        ]);
    }
}
