<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest; 

class OrderController extends Controller
{
    // index/show は 3-5 のまま（公開）

    public function create()
    {
        $this->authorize('create', Order::class);
        $order = new Order(['order_date' => now()->toDateString()]);
        $order->setRelation('items', collect());
        $products = Product::query()->orderBy('name')->get(['id','name','price']);
        return view('orders.create', compact('order','products'));
    }

    public function store(StoreOrderRequest $request)
    {

        $validated = $request->validated();

        $order = null;
        DB::transaction(function () use ($request, $validated, &$order) {
            $order = Order::create([
                'user_id'      => $request->user()->id,
                'order_date'   => $validated['order_date'],
                'total_amount' => 0,
            ]);

            $total = 0;
            $prices = Product::whereIn(
                'id',
                collect($validated['items'])->pluck('product_id')
            )->pluck('price', 'id');
            foreach ($validated['items'] as $it) {
                        $unitPrice = (int) $prices[$it['product_id']]; // ← DB価格
                            $order->items()->create([
                                'product_id' => $it['product_id'],
                                'qty'        => $it['qty'],
                                'unit_price' => $unitPrice,
                            ]);
                            $total += $it['qty'] * $unitPrice;
                        }
                        $order->update(['total_amount' => $total]);
        });
            

        return redirect()->route('orders.show', $order)
            ->with('success','注文を作成しました。');
    }


    public function edit(Order $order)
    {
        $this->authorize('update', $order);
        $order->load(['items.product']);
        $products = Product::query()->orderBy('name')->get(['id','name','price']);
        return view('orders.edit', compact('order','products'));
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {

        $validated = $request->validated();

        DB::transaction(function () use ($order, $validated) {
            $order->update(['order_date' => $validated['order_date']]);

            // シンプルに全削除→再作成（運用によっては差分更新でもOK）
            $order->items()->delete();

            $total = 0;
            $prices = Product::whereIn(
                'id',
                collect($validated['items'])->pluck('product_id')
            )->pluck('price', 'id');
            foreach ($validated['items'] as $it) {
                $unitPrice = (int) $prices[$it['product_id']];
                $order->items()->create([
                    'product_id' => $it['product_id'],
                    'qty'        => $it['qty'],
                    'unit_price' => $unitPrice,
                ]);
                $total += $it['qty'] * $unitPrice;
            }
            $order->update(['total_amount' => $total]);
        });

        return redirect()->route('orders.show', $order)
            ->with('success','注文を更新しました。');
    }

    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);
        $order->delete();

        return redirect()->route('orders.index')
            ->with('success','注文を削除しました。');
    }
    public function index()
    {
        $orders = Order::query()
            ->with(['user', 'items'])
            ->orderByDesc('order_date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('orders.index', compact('orders'));
    }
    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('orders.show', compact('order'));
    }
}