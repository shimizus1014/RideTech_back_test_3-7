@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">

                {{-- ログインユーザー表示（安全版） --}}
                @auth
                    <div class="mb-4">
                        ようこそ、{{ Auth::user()->name }} さん
                    </div>
                @endauth

                <div>
                    You're logged in!
                </div>
                <div>
    <h1>Dashboard</h1>

    <div class="mt-6 flex gap-4">

    <a href="{{ route('products.index') }}"
        class="inline-flex items-center px-5 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 hover:shadow-md transition">
        商品一覧へ
    </a>

    <a href="{{ route('orders.index') }}"
        class="inline-flex items-center px-5 py-3 bg-green-600 text-white font-semibold rounded-lg shadow hover:bg-green-700 hover:shadow-md transition">
        注文一覧へ
    </a>

    </div>
    </div>    
            </div>
        </div>

    </div>
</div>
@endsection