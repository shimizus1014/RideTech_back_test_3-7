<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            // 既存ユーザー・商品を使う場合（3-3で投入済みとする）
    $users    = \App\Models\User::pluck('id')->all();
    $products = \App\Models\Product::pluck('id')->all();

    \App\Models\Order::factory(40)
        ->create([
            // ランダムな既存ユーザーを割り当て
        ])
        ->each(function (\App\Models\Order $order) use ($products) {
            // 2〜5明細
            $n = rand(2, 5);
            $total = 0;

            for ($i=0; $i<$n; $i++) {
                $pid  = $products[array_rand($products)];
                $qty  = rand(1, 4);
                $unit = rand(100, 5000);

                $order->items()->create([
                    'product_id' => $pid,
                    'qty'        => $qty,
                    'unit_price' => $unit,
                ]);

                $total += $qty * $unit;
            }

            // 参考：保持派ならここで total_amount に保存
            $order->update(['total_amount' => $total]);
        });
    }
}
