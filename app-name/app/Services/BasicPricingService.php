<?php
namespace App\Services;

final class BasicPricingService implements PricingServiceInterface {
    public function total(array $items): int {
        $sum = 0; foreach ($items as $i) { $sum += $i['unit_price'] * $i['qty']; }
        $tax = (int) round($sum * 0.10);
        return $sum + $tax;
    }
}