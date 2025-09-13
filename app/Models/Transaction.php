<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'account_id',
        'amount',
        'type',
        'imported_id'
    ];

    public static function getAccountBalance(int $accountId): float
    {
        return self::where('account_id', $accountId)->sum('amount');
    }
}
