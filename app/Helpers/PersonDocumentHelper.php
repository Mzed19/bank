<?php

namespace App\Helpers;

class PersonDocumentHelper
{
    public static function cpf(bool $formatted = true): string
    {
        $n = [];
        for ($i = 0; $i < 9; $i++) {
            $n[$i] = random_int(0, 9);
        }

        $sum = 0;
        for ($i = 0, $j = 10; $i < 9; $i++, $j--) {
            $sum += $n[$i] * $j;
        }
        $rem = $sum % 11;
        $d1 = ($rem < 2) ? 0 : 11 - $rem;
        $n[9] = $d1;

        $sum = 0;
        for ($i = 0, $j = 11; $i < 10; $i++, $j--) {
            $sum += $n[$i] * $j;
        }
        $rem = $sum % 11;
        $d2 = ($rem < 2) ? 0 : 11 - $rem;
        $n[10] = $d2;

        $cpf = implode('', $n);

        return $formatted ? self::formatCpf($cpf) : $cpf;
    }

    public static function cnpj(bool $formatted = true): string
    {
        $n = [];
        for ($i = 0; $i < 12; $i++) {
            $n[$i] = random_int(0, 9);
        }

        $weights1 = [5,4,3,2,9,8,7,6,5,4,3,2];
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += $n[$i] * $weights1[$i];
        }
        $rem = $sum % 11;
        $d1 = ($rem < 2) ? 0 : 11 - $rem;
        $n[12] = $d1;

        $weights2 = [6,5,4,3,2,9,8,7,6,5,4,3,2];
        $sum = 0;
        for ($i = 0; $i < 13; $i++) {
            $sum += $n[$i] * $weights2[$i];
        }
        $rem = $sum % 11;
        $d2 = ($rem < 2) ? 0 : 11 - $rem;
        $n[13] = $d2;

        $cnpj = implode('', $n);

        return $formatted ? self::formatCnpj($cnpj) : $cnpj;
    }

    private static function formatCpf(string $cpf): string
    {
        return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
    }

    private static function formatCnpj(string $cnpj): string
    {
        return substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3) . '.' . substr($cnpj, 5, 3) . '/' . substr($cnpj, 8, 4) . '-' . substr($cnpj, 12, 2);
    }

    public static function generateRandomValidDocument(): string
    {
        $randomBoolean = (mt_rand(0, 1) === 0);
        return $randomBoolean ? self::cpf(false) : self::cnpj(false);
    }
}
