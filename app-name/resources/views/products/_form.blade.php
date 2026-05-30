@csrf
{{-- 新規: POST /products ／ 編集: PATCH /products/{product} --}}
<div class="mb-4">
  <label class="block mb-1">商品名 <span class="text-red-600">*</span></label>
  <input type="text" name="name" class="border p-2 w-full"
         value="{{ old('name', $product->name) }}">
  @error('name') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
</div>

<div class="mb-4">
  <label class="block mb-1">価格（税抜・円） <span class="text-red-600">*</span></label>
  <input type="number" name="price" class="border p-2 w-full" min="0" step="1"
         value="{{ old('price', $product->price) }}">
  @error('price') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
</div>

<div class="mb-4">
  <label class="block mb-1">説明</label>
  <textarea name="description" class="border p-2 w-full" rows="4">{{ old('description', $product->description) }}</textarea>
  @error('description') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
</div>

<div class="mb-4">
  <label class="block mb-1">商品画像</label>

  <input type="file" name="image" class="border p-2 w-full">

  @error('image')
    <div class="text-red-600 text-sm">{{ $message }}</div>
  @enderror

  @if($product->image_path)
    <div class="mt-2">
      <img src="{{ Storage::url($product->image_path) }}"
           class="w-32 h-32 object-cover border">
    </div>
  @endif
</div>

<div class="mb-6">
  <label class="block mb-1">公開日（任意）</label>
  <input type="date" name="published_at" class="border p-2"
         value="{{ old('published_at', optional($product->published_at)->format('Y-m-d')) }}">
  @error('published_at') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
</div>

<button class="px-4 py-2 bg-blue-600 text-white rounded">保存</button>
<a href="{{ route('products.index') }}" class="ml-3 text-gray-600">キャンセル</a>