<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function pricing()
    {
        $plans = [
            ['name' => 'Starter', 'price' => 0],
            ['name' => 'Pro',     'price' => 1200],
        ];
        return view('pricing', compact('plans'));
    }
}