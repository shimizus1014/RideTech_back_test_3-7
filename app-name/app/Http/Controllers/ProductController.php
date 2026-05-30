<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(9); // ←ここ重要
    
        return view('products.index', compact('products'));
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function create()
    {
        $product = new Product();
        return view('products.create', compact('product'));
    }

    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image_path'] =
            $request->file('image')->store('products', 'public');
        }

    $product = Product::create($validated);

        return redirect()
            ->route('products.show', $product)
            ->with('success', '商品を作成しました。');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }
    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();
    
        // ① 画像アップロードがある場合
        if ($request->hasFile('image')) {
    
            // ② 旧画像削除（存在していれば）
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
    
            // ③ 新画像保存
            $validated['image_path'] =
                $request->file('image')->store('products', 'public');
        }
    
        // ④ DB更新
        $product->update($validated);
    
        return redirect()
            ->route('products.show', $product)
            ->with('success', '商品を更新しました。');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', '商品を削除しました。');
    }
}