@extends('layouts.app')
@section('title','Quote')
@section('content')
  <h1 class="text-2xl font-bold mb-4">Quote</h1>
  <ul class="space-y-1">
    @foreach($items as $i)
      <li>¥{{ number_format($i['unit_price']) }} × {{ $i['qty'] }}</li>
    @endforeach
  </ul>
  <p class="mt-4 font-semibold">Total: ¥{{ number_format($total) }}</p>
@endsection