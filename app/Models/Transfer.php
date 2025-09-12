<?php

namespace App\Models;

use App\Observers\TransactionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([TransactionObserver::class])]
class Transfer extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'amount',
        'type',
        'description',
    ];
}
