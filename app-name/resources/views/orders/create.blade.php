@extends('layouts.app')
@section('title','Create Order')

@section('content')
  <a href="{{ route('orders.index') }}">← Back</a>
  <h1 class="text-2xl font-bold mt-2 mb-4">Create Order</h1>

  <form action="{{ route('orders.store') }}" method="post">
    @include('orders._form')
  </form>
@endsection