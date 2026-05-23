@extends('layouts.app')
@section('title', "Order #{$order->id}")

@section('content')
  <a href="{{ route('orders.index') }}">← Back</a>
  <h1 class="text-2xl font-bold mt-2">Order #{{ $order->id }}</h1>
  <p class="text-gray-600">{{ $order->order_date->format('Y-m-d') }} / {{ $order->user->name }}</p>

  <table class="w-full bg-white border mt-4">
    <thead>
      <tr class="bg-gray-50">
        <th class="p-2 text-left">Product</th>
        <th class="p-2 text-right">Unit</th>
        <th class="p-2 text-right">Qty</th>
        <th class="p-2 text-right">Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @php $total = 0; @endphp
      @foreach ($order->items as $it)
        @php $subtotal = $it->subtotal; $total += $subtotal; @endphp
        <tr class="border-t">
          <td class="p-2">{{ $it->product->name }}</td>
          <td class="p-2 text-right">¥{{ number_format($it->unit_price) }}</td>
          <td class="p-2 text-right">{{ $it->qty }}</td>
          <td class="p-2 text-right">¥{{ number_format($subtotal) }}</td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <th class="p-2 text-right" colspan="3">Total</th>
        <th class="p-2 text-right">¥{{ number_format($total) }}</th>
      </tr>
    </tfoot>
  </table>
  @can('update', $order)
  <a class="inline-block px-3 py-2 bg-blue-600 text-white rounded" href="{{ route('orders.edit', $order) }}">編集</a>
@endcan
@can('delete', $order)
  <form method="post" action="{{ route('orders.destroy', $order) }}" class="inline">
    @csrf @method('DELETE')
    <button class="px-3 py-2 bg-red-600 text-white rounded">削除</button>
  </form>
@endcan
@endsection