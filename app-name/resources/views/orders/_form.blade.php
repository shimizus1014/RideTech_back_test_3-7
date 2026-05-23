@csrf

<div class="mb-4">
  <label class="block mb-1">注文日 <span class="text-red-600">*</span></label>
  <input type="date" name="order_date" class="border p-2"
         value="{{ old('order_date', optional($order->order_date)->format('Y-m-d')) }}">
  @error('order_date') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
</div>

<div class="mb-2 flex items-center justify-between">
  <h2 class="font-semibold">明細</h2>
  @error('items') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
</div>

@php
  $items = old('items');
  if (!is_array($items)) {
    $items = ($order->relationLoaded('items') ? $order->items->map(function($it){
      return ['product_id'=>$it->product_id,'unit_price'=>$it->unit_price,'qty'=>$it->qty];
    })->toArray() : []);
  }
  if (count($items) === 0) { $items = [[ 'product_id'=>'', 'unit_price'=>'', 'qty'=>1 ]]; }
@endphp

<table class="w-full bg-white border mb-4" id="items-table">
  <thead>
    <tr class="bg-gray-50">
      <th class="p-2 text-left">商品</th>
      <th class="p-2 text-right">単価</th>
      <th class="p-2 text-right">数量</th>
      <th class="p-2"></th>
    </tr>
  </thead>
  <tbody>
    @foreach ($items as $i => $it)
      <tr class="border-t">
        <td class="p-2">
          <select name="items[{{ $i }}][product_id]" class="border p-2 w-full product-select">
            <option value="">選択してください</option>
            @foreach ($products as $p)
              <option value="{{ $p->id }}" data-price="{{ $p->price }}" {{ (string)$it['product_id'] === (string)$p->id ? 'selected' : '' }}>
                {{ $p->name }} (¥{{ number_format($p->price) }})
              </option>
            @endforeach
          </select>
          @error("items.$i.product_id") <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </td>
        <td class="p-2">
          <input type="number" min="0" step="1" name="items[{{ $i }}][unit_price]" class="border p-2 w-full unit-price" value="{{ old("items.$i.unit_price", $it['unit_price']) }}">
          @error("items.$i.unit_price") <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </td>
        <td class="p-2">
          <input type="number" min="1" step="1" name="items[{{ $i }}][qty]" class="border p-2 w-full" value="{{ old("items.$i.qty", $it['qty']) }}">
          @error("items.$i.qty") <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </td>
        <td class="p-2 text-right">
          <button type="button" class="px-3 py-2 bg-gray-200 rounded remove-row">削除</button>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>

<div class="mb-6">
  <button type="button" id="add-row" class="px-4 py-2 bg-gray-600 text-white rounded">行を追加</button>
</div>

<button class="px-4 py-2 bg-blue-600 text-white rounded">保存</button>
<a href="{{ route('orders.index') }}" class="ml-3 text-gray-600">キャンセル</a>

<script>
  (function(){
    const table = document.getElementById('items-table');
    const addBtn = document.getElementById('add-row');
    function bindRowEvents(tr){
      const select = tr.querySelector('select.product-select');
      const priceInput = tr.querySelector('input.unit-price');
      if (select && priceInput) {
        select.addEventListener('change', function(){
          const opt = this.options[this.selectedIndex];
          const price = opt && opt.getAttribute('data-price');
          if (price && !priceInput.value) priceInput.value = price;
        });
      }
      const remove = tr.querySelector('button.remove-row');
      if (remove) {
        remove.addEventListener('click', function(){
          const rows = table.tBodies[0].rows;
          if (rows.length > 1) tr.remove();
        });
      }
    }
    // bind existing
    Array.from(table.tBodies[0].rows).forEach(bindRowEvents);
    addBtn.addEventListener('click', function(){
      const idx = table.tBodies[0].rows.length;
      const tr = document.createElement('tr');
      tr.className = 'border-t';
      tr.innerHTML = `
        <td class="p-2">
          <select name="items[${idx}][product_id]" class="border p-2 w-full product-select">
            <option value="">選択してください</option>
            ${Array.from(document.querySelectorAll('#items-table select.product-select option'))
              .filter((o, i, arr) => i === 0 || o.value) // skip first blank multiple times
              .map(o => `<option value="${o.value}" data-price="${o.getAttribute('data-price')||''}">${o.textContent}</option>`)
              .join('')}
          </select>
        </td>
        <td class="p-2">
          <input type="number" min="0" step="1" name="items[${idx}][unit_price]" class="border p-2 w-full unit-price" value="">
        </td>
        <td class="p-2">
          <input type="number" min="1" step="1" name="items[${idx}][qty]" class="border p-2 w-full" value="1">
        </td>
        <td class="p-2 text-right">
          <button type="button" class="px-3 py-2 bg-gray-200 rounded remove-row">削除</button>
        </td>`;
      table.tBodies[0].appendChild(tr);
      bindRowEvents(tr);
    });
  })();
</script>