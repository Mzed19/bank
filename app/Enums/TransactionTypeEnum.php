<?php

namespace App\Enums;

Enum TransactionTypeEnum: string
{
    case DEPOSIT = 'deposit';
    case TRANSFER = 'transfer';

    public static function getValuesInString(): string
    {
        return implode(', ', array_map(fn($case) => $case->value, self::cases()));
    }
}
