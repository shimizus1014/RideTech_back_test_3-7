@extends('layouts.app')
@section('title', $product->name)
@section('content')
  <a href="{{ route('products.index') }}">← Back</a>
  <h1 class="text-2xl font-bold mt-2">{{ $product->name }}</h1>
  <div class="text-lg mt-1">{{ $product->price_formatted }}</div>
  @if($product->published_at)
    <div class="text-sm text-gray-500">公開: {{ $product->published_at->format('Y-m-d') }}</div>
  @endif
  @if($product->description)
    <p class="mt-4 whitespace-pre-line">{{ $product->description }}</p>
  @endif
  <div class="mt-6 flex gap-3">
    <a href="{{ route('products.edit', $product) }}" class="inline-block px-3 py-2 bg-blue-600 text-white rounded">編集</a>
    <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('削除しますか？');">
      @csrf
      @method('DELETE')
      <button type="submit" class="px-3 py-2 bg-red-600 text-white rounded">削除</button>
    </form>
  </div>
@endsection