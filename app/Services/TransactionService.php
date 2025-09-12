<?php

namespace App\Services;

use App\Models\Transfer;
use Tymon\JWTAuth\Facades\JWTAuth;

class TransactionService {
    public function createTransfer(float $amount, int $userId, ?string $description): Transfer
    {
        dd(JWTAuth::parseToken()->authenticate());

        return Transfer::create([
            'user_id' => $userId,
            'amount' => $amount,
            'description' => $description
        ]);
    }
}
