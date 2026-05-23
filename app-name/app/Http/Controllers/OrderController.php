<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        $this->authorize('create', Order::class);

        $validated = $request->validate([
            'order_date' => ['required','date','before_or_equal:today'],
            'items' => ['required','array','min:1'],
            'items.*.product_id' => ['required','integer','exists:products,id'],
            'items.*.unit_price' => ['required','integer','min:0'],
            'items.*.qty'        => ['required','integer','min:1'],
        ]);

        $order = null;
        DB::transaction(function () use ($request, $validated, &$order) {
            $order = Order::create([
                'user_id'      => $request->user()->id,
                'order_date'   => $validated['order_date'],
                'total_amount' => 0,
            ]);

            $total = 0;
            foreach ($validated['items'] as $it) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $it['product_id'],
                    'qty'        => $it['qty'],
                    'unit_price' => $it['unit_price'],
                ]);
                $total += $it['qty'] * $it['unit_price'];
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

    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validated = $request->validate([
            'order_date' => ['required','date','before_or_equal:today'],
            'items' => ['required','array','min:1'],
            'items.*.product_id' => ['required','integer','exists:products,id'],
            'items.*.unit_price' => ['required','integer','min:0'],
            'items.*.qty'        => ['required','integer','min:1'],
        ]);

        DB::transaction(function () use ($order, $validated) {
            $order->update(['order_date' => $validated['order_date']]);

            // シンプルに全削除→再作成（運用によっては差分更新でもOK）
            $order->items()->delete();

            $total = 0;
            foreach ($validated['items'] as $it) {
                $order->items()->create([
                    'product_id' => $it['product_id'],
                    'qty'        => $it['qty'],
                    'unit_price' => $it['unit_price'],
                ]);
                $total += $it['qty'] * $it['unit_price'];
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
        $orders = Order::latest()->paginate(10);

        return view('orders.index', compact('orders'));
    }
    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }
}