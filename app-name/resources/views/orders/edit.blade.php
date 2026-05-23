@extends('layouts.app')
@section('title',"Edit Order #{$order->id}")

@section('content')
  <a href="{{ route('orders.show', $order) }}">← Back</a>
  <h1 class="text-2xl font-bold mt-2 mb-4">Edit Order #{{ $order->id }}</h1>

  <form action="{{ route('orders.update', $order) }}" method="post">
    @method('PATCH')
    @include('orders._form')
  </form>

  <form action="{{ route('orders.destroy', $order) }}" method="post" class="mt-6"
        onsubmit="return confirm('本当に削除しますか？')">
    @csrf
    @method('DELETE')
    <button class="px-4 py-2 bg-red-600 text-white rounded">削除</button>
  </form>
@endsection