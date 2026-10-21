<?php

namespace App\Services;

class CurrencyConverter
{
    public function convert(float $amount, string $from, string $to): float
    {
        // If package available use it, otherwise return same amount
        try {
            if (class_exists('\AmrShawky\LaravelCurrency\Currency')) {
                $curr = new \AmrShawky\LaravelCurrency\Currency();
                $result = $curr->convert($amount, $from, $to);
                return (float) $result;
            }
        } catch (\Throwable $e) {
            // ignore
        }

        return $amount;
    }
}
