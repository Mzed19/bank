<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'type'
    ];

    public function getUserBalance(int $userId): float
    {
        return self::where('user_id', $userId)->sum('amount');
    }
}
