@extends('layouts.app')

@section('title','Products')

@section('content')
  <h1 class="text-2xl font-bold mb-4">Products</h1>
  <div class="mb-4 text-right">
    <a href="{{ route('products.create') }}"
       class="px-4 py-2 bg-blue-600 text-white rounded">
        新規作成
    </a>
</div>

  @if ($products->count())
    <div class="grid md:grid-cols-3 gap-4">
      @foreach($products as $p)
        <a href="{{ route('products.show', $p) }}" class="block p-4 border bg-white hover:bg-gray-50">
          <div class="font-semibold truncate">{{ $p->name }}</div>

          <div class="text-sm text-gray-500 mt-1">
            {{ \Illuminate\Support\Str::limit($p->description, 60) }}
          </div>

          <div class="mt-2">{{ $p->price_formatted }}</div>

          @if($p->published_at)
            <div class="text-xs text-gray-500 mt-1">
              公開: {{ $p->published_at->format('Y-m-d') }}
            </div>
          @endif
        </a>
      @endforeach
    </div>

    <div class="mt-6">
      {{ $products->links() }}
    </div>
  @else
    <p>データがありません。</p>
  @endif
@endsection