@extends('layouts.app')

@section('title','Orders')

@section('content')
  <h1 class="text-2xl font-bold mb-4">Orders</h1>

  @can('create', \App\Models\Order::class)
    <div class="mb-4 text-right">
      <a class="px-4 py-2 bg-blue-600 text-white rounded"
         href="{{ route('orders.create') }}">
        新規作成
      </a>
    </div>
  @endcan

  @if ($orders->count())
    <table class="w-full bg-white border">
      <thead>
        <tr class="bg-gray-50">
          <th class="p-2 text-left">#</th>
          <th class="p-2">User</th>
          <th class="p-2">Date</th>
          <th class="p-2 text-right">Total</th>
          <th class="p-2"></th>
        </tr>
      </thead>

      <tbody>
        @foreach ($orders as $o)
          <tr class="border-t">
            <td class="p-2">{{ $o->id }}</td>
            <td class="p-2">{{ $o->user->name ?? 'N/A' }}</td>
            <td class="p-2">{{ $o->order_date?->format('Y-m-d') }}</td>

            @php $total = $o->computed_total ?? $o->total_amount ?? 0; @endphp

            <td class="p-2 text-right">
              ¥{{ number_format($total) }}
            </td>

            <td class="p-2 text-right">
              <a class="text-blue-600" href="{{ route('orders.show', $o) }}">
                詳細
              </a>

              @can('update', $o)
                <a class="ml-2 text-green-600" href="{{ route('orders.edit', $o) }}">
                  編集
                </a>
              @endcan

              @can('delete', $o)
                <form method="post"
                      action="{{ route('orders.destroy', $o) }}"
                      class="inline"
                      onsubmit="return confirm('本当に削除しますか？')">
                  @csrf
                  @method('DELETE')
                  <button class="ml-2 text-red-600">削除</button>
                </form>
              @endcan
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="mt-4">
      {{ $orders->links() }}
    </div>

  @else
    <p>データがありません。</p>
  @endif
@endsection