<?php

namespace App\Helpers;

class LeyscoHelpers
{
    public static function formatCurrency(float $amount): string
    {
        return 'KES ' . number_format($amount, 2) . ' /=';
    }

    public static function generateOrderNumber(): string
    {
        return 'ORD-' . now()->format('Y-m') . '-' .
               str_pad(random_int(1, 999), 3, '0', STR_PAD_LEFT);
    }

    public static function calculateTax(float $amount, float $rate): float
    {
        return round($amount * ($rate / 100), 2);
    }
}
