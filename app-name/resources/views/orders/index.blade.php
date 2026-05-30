@extends('layouts.app')

@section('title','Orders')

@section('content')
  <h1 class="text-2xl font-bold mb-4">Orders</h1>

  <form method="GET" action="{{ route('orders.index') }}"
      class="mb-6 p-4 bg-white border rounded">

    <div class="grid grid-cols-5 gap-4">
      

        <div>
            <label class="block text-sm">ユーザー名</label>
            <input type="text"
                   name="user"
                   value="{{ request('user') }}"
                   class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block text-sm">開始日</label>
            <input type="date"
                   name="from"
                   value="{{ request('from') }}"
                   class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block text-sm">終了日</label>
            <input type="date"
                   name="to"
                   value="{{ request('to') }}"
                   class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block text-sm">最小金額</label>
            <input type="number"
                   name="min_total"
                   value="{{ request('min_total') }}"
                   class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block text-sm">最大金額</label>
            <input type="number"
                   name="max_total"
                   value="{{ request('max_total') }}"
                   class="w-full border rounded p-2">
        </div>
        

    </div>

    <div class="mt-4">
        <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded">
            検索
        </button>

        <a href="{{ route('orders.index') }}"
           class="ml-2 px-4 py-2 bg-gray-500 text-white rounded">
            クリア
        </a>
    </div>

</form>

  @can('create', \App\Models\Order::class)
    <div class="mb-4 text-right">
      <a class="px-4 py-2 bg-blue-600 text-white rounded"
         href="{{ route('orders.create') }}">
        新規作成
      </a>
    </div>
  @endcan
  <a href="{{ route('orders.index', ['filter' => 'active']) }}"
   class="{{ $filter === 'active' ? 'bg-blue-600 text-white' : '' }}">
    通常
</a>

<a href="{{ route('orders.index', ['filter' => 'trashed']) }}"
   class="{{ $filter === 'trashed' ? 'bg-blue-600 text-white' : '' }}">
    ゴミ箱
</a>

<a href="{{ route('orders.index', ['filter' => 'all']) }}"
   class="{{ $filter === 'all' ? 'bg-blue-600 text-white' : '' }}">
    全件
</a>
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

    @if (!$o->trashed())

        @can('update', $o)
            <a class="ml-2 text-green-600"
               href="{{ route('orders.edit', $o) }}">
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
                <button class="ml-2 text-red-600">
                    削除
                </button>
            </form>
        @endcan

    @else

        @can('restore', $o)
            <form method="post"
                  action="{{ route('orders.restore', $o) }}"
                  class="inline">
                @csrf
                <button class="ml-2 text-blue-600">
                    復元
                </button>
            </form>
        @endcan

        @can('forceDelete', $o)
            <form method="post"
                  action="{{ route('orders.forceDelete', $o) }}"
                  class="inline"
                  onsubmit="return confirm('完全削除しますか？')">
                @csrf
                @method('DELETE')
                <button class="ml-2 text-red-700">
                    完全削除
                </button>
            </form>
        @endcan

    @endif
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