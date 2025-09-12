<?php

namespace App\Enums;

Enum TransferTypeEnum: string
{
    case DEBIT = 'debit';
    case CREDIT = 'credit';
    case PIX = 'pix';

    public function label(): string
    {
        return match ($this) {
            self::DEBIT => 'Débito',
            self::CREDIT => 'Crédito',
            self::PIX => 'Pix',
        };
    }

    public static function getValuesInString(): string
    {
        return implode(', ', array_map(fn($case) => $case->value, self::cases()));
    }
}
