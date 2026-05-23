@props(['type' => 'info'])
@php $color = ['info'=>'blue','success'=>'green','warn'=>'yellow','danger'=>'red'][$type] ?? 'blue'; @endphp

<div {{ $attributes->merge(['class'=>"border-l-4 p-3 bg-{$color}-50 border-{$color}-400"]) }}>
  {{ $slot }}
</div>