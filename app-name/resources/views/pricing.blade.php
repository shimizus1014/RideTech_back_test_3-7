@extends('layouts.app')
@section('title','Pricing')
@section('content')
  <h1 class="text-2xl font-bold mb-4">Pricing</h1>
  <ul class="space-y-1">
    @foreach($plans as $p)
      <li>{{ $p['name'] }} — ¥{{ number_format($p['price']) }}</li>
    @endforeach
  </ul>
@endsection