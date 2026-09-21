@extends('layouts.app')

@section('title', __('ui.order.title'))

@section('content')

<nav class="navbar">
  <div class="container navbar__row">
    <a href="{{ route('home') }}" class="wordmark">Frennz.Stuff</a>
    <div class="nav-links"><a href="{{ route('products.index') }}">{{ __('ui.nav.shop') }}</a></div>
    <div class="nav-actions">
      @include('partials.lang-switch')
      <a class="nav-icon-btn" href="{{ route('cart.index') }}" aria-label="{{ __('ui.nav.cart') }}">
        <svg class="icon" viewBox="0 0 24 24"><path d="M6 8h12l-1 12H7L6 8z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg>
        <span class="nav-count" data-cart-count style="display:none">0</span>
      </a>
      @auth
      <a class="nav-icon-btn" href="{{ route('profile.index') }}" aria-label="{{ __('ui.nav.account') }}">
        <svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="8" r="3.6"/><path d="M4.5 20c1.4-3.6 4.4-5.5 7.5-5.5s6.1 1.9 7.5 5.5"/></svg>
      </a>
      @endauth
    </div>
  </div>
</nav>

<main class="container" style="padding:80px 0;max-width:520px;text-align:center;">
  <div style="width:56px;height:56px;border-radius:50%;background:var(--accent-tint);color:var(--accent);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
    <svg class="icon" style="width:26px;height:26px;stroke-width:2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
  </div>
  <h2>{{ __('ui.order.heading') }}</h2>
  <p class="text-muted" style="margin-top:10px;" id="order-id-text"></p>
  <p class="text-muted" style="margin-top:6px;font-size:13.5px;">{{ __('ui.order.note') }}</p>
  <div style="display:flex;gap:10px;justify-content:center;margin-top:26px;flex-wrap:wrap;">
    <a href="{{ route('products.index') }}" class="btn btn-primary">{{ __('ui.order.continue') }}</a>
    @auth
    <a href="{{ route('profile.index', ['tab' => 'orders']) }}" class="btn btn-outline">{{ __('ui.profile.tab_orders') }}</a>
    @endauth
    <a href="{{ route('home') }}" class="btn btn-ghost">{{ __('ui.nav.home') }}</a>
  </div>
</main>

<footer class="site-footer">
  <div class="container footer-bottom">
    <span>{{ __('ui.footer.copyright', ['year' => date('Y')]) }}</span>
    <span>{{ __('ui.footer.note') }}</span>
  </div>
</footer>

@endsection

@section('scripts')
<script>
  const id = new URLSearchParams(location.search).get("id");
  document.getElementById("order-id-text").textContent = id ? t('order.number', { id: id }) : "";
</script>
@endsection
