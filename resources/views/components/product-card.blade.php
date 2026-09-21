@extends('layouts.app')

@props(['product'])

@php $price = fn ($v) => app()->getLocale() === 'en' ? 'IDR ' . number_format($v, 0, ',', '.') : 'Rp ' . number_format($v, 0, ',', '.'); @endphp

<article class="card">
  @if(($product['tag'] ?? null) === 'NEW')
    <span class="card__flag">{{ __('ui.common.new_flag') }}</span>
  @endif
  <a class="card__link" href="{{ route('products.show') }}?id={{ $product['id'] }}" aria-label="{{ $product['name'] }}"></a>
  <div class="card__media">
    <img src="{{ $product['images'][0] ?? '' }}" alt="{{ $product['name'] }}" loading="lazy">
    <div class="card__quickadd">
      <button class="btn btn-primary btn-sm btn-block" style="position:relative;z-index:3;" onclick="event.preventDefault();quickAdd('{{ $product['id'] }}')">{{ __('ui.common.add_to_cart') }}</button>
    </div>
  </div>
  <div class="card__body">
    @if(!empty($product['brand']))<div class="text-muted" style="font-size:11px;letter-spacing:.08em;text-transform:uppercase;margin-bottom:4px;">{{ $product['brand'] }}</div>@endif
    <h3 class="card__title">{{ $product['name'] }}</h3>
    <div class="card__price">
      @if(!empty($product['salePrice']))
        <span class="now">{{ $price($product['salePrice']) }}</span>
        <del>{{ $price($product['price']) }}</del>
      @else
        {{ $price($product['price']) }}
      @endif
    </div>
  </div>
</article>
