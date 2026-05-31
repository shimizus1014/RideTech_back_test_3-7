@extends('layouts.app')
@section('title','Create Product')

@section('content')
  <h1 class="text-2xl font-bold mb-4">Create Product</h1>

  @if (session('success'))
    <x-alert type="success" class="mb-4">{{ session('success') }}</x-alert>
  @endif

  <form action="{{ route('products.store') }}"
        method="post"
        enctype="multipart/form-data"
        novalidate>
    @include('products._form')
  </form>
@endsection