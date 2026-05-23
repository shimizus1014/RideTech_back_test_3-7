@extends('layouts.app')
@section('title',"Page: $slug")
@section('content')
  <h1 class="text-2xl font-bold mb-4">Page: {{ $slug }}</h1>
  <p>ダミーの静的ページです。</p>
@endsection