<?php

namespace App\Helpers;

class TransactionHelper
{
    public static function toCurrency(float $value): string
    {
        return 'R$ ' . number_format($value, 2, ',', '.');
    }
}
