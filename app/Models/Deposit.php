<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Observers\TransactionObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([TransactionObserver::class])]
class Deposit extends Model
{
    use HasFactory;
    protected $fillable = [
        'receiver_account_id',
        'amount',
        'description',
    ];

     public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'receiver_account_id');
    }
}
