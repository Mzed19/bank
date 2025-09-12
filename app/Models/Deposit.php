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
        'receiver_id',
        'amount',
        'description',
    ];

     public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
