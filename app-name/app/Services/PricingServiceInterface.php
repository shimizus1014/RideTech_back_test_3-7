<?php
namespace App\Services;

interface PricingServiceInterface {
    /** @param array<array{unit_price:int,qty:int}> $items */
    public function total(array $items): int;
}