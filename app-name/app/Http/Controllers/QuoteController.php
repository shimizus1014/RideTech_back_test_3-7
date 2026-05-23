<?php
namespace App\Http\Controllers;

use App\Services\PricingServiceInterface;

class QuoteController extends Controller
{
    public function __construct(private PricingServiceInterface $pricing) {}

    public function __invoke()
    {
        $items = [
            ['unit_price'=>1200,'qty'=>2],
            ['unit_price'=>100, 'qty'=>3],
        ];
        $total = $this->pricing->total($items);
        return view('quote', compact('items','total'));
    }
}