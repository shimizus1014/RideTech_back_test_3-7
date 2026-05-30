@extends('layouts.app')

@section('title','Edit: '.$product->name)

@section('content')
  <h1 class="text-2xl font-bold mb-4">Edit: {{ $product->name }}</h1>

  @if (session('success'))
    <x-alert type="success" class="mb-4">{{ session('success') }}</x-alert>
  @endif

  <form action="{{ route('products.update', $product) }}"
        method="post"
        enctype="multipart/form-data"
        novalidate>

    @csrf
    @method('PATCH')

    @include('products._form')

  </form>

  <form action="{{ route('products.destroy', $product) }}"
        method="post"
        class="mt-6"
        onsubmit="return confirm('本当に削除しますか？')">

    @csrf
    @method('DELETE')

    <button class="px-4 py-2 bg-red-600 text-white rounded">
      削除
    </button>
  </form>
@endsection