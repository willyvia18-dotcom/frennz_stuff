@extends('layouts.app')

@section('title', __('ui.wishlist.title'))

@section('content')

<nav class="navbar">
  <div class="container navbar__row">
    <a href="{{ route('home') }}" class="wordmark">Frennz.Stuff</a>
    <div class="nav-links">
      <a href="{{ route('home') }}">{{ __('ui.nav.home') }}</a>
      <a href="{{ route('products.index') }}">{{ __('ui.nav.shop') }}</a>
      <a href="{{ route('products.index') }}">{{ __('ui.nav.categories') }}</a>
      <a href="{{ route('home') }}#about">{{ __('ui.nav.about') }}</a>
      <a href="{{ route('home') }}#contact">{{ __('ui.nav.contact') }}</a>
    </div>
    <div class="nav-actions">
      @include('partials.lang-switch')
      <a class="nav-icon-btn" href="{{ route('wishlist.index') }}" aria-label="{{ __('ui.nav.wishlist') }}">
        <svg class="icon" viewBox="0 0 24 24"><path d="M12 21s-7.5-4.6-10-9.1C.5 8.6 2 5 5.4 5c2 0 3.4 1.1 4.1 2.3C10.2 6.1 11.6 5 13.6 5 17 5 18.5 8.6 17 11.9 14.5 16.4 12 21 12 21z"/></svg>
        <span class="nav-count" data-wishlist-count style="display:none">0</span>
      </a>
      <a class="nav-icon-btn" href="{{ route('cart.index') }}" aria-label="{{ __('ui.nav.cart') }}">
        <svg class="icon" viewBox="0 0 24 24"><path d="M6 8h12l-1 12H7L6 8z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg>
        <span class="nav-count" data-cart-count style="display:none">0</span>
      </a>
      <a class="nav-icon-btn" href="{{ Auth::check() ? route('profile.index') : route('login') }}" aria-label="{{ __('ui.nav.account') }}">
        <svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="8" r="3.6"/><path d="M4.5 20c1.4-3.6 4.4-5.5 7.5-5.5s6.1 1.9 7.5 5.5"/></svg>
      </a>
    </div>
  </div>
</nav>

<main class="container">
  <div class="crumbs"><a href="{{ route('home') }}">{{ __('ui.shop.crumb_home') }}</a> / <span>{{ __('ui.nav.wishlist') }}</span></div>
  <div class="section__head" style="margin-top:8px;"><h2>{{ __('ui.nav.wishlist') }}</h2></div>
  <div class="grid" id="wishlist-grid"></div>
  <div class="empty-state" id="wl-empty" style="display:none;margin-top:20px;">
    <h3>{{ __('ui.wishlist.empty_title') }}</h3>
    <p>{{ __('ui.wishlist.empty_text') }}</p>
    <a href="{{ route('products.index') }}" class="btn btn-primary" style="margin-top:16px">{{ __('ui.wishlist.explore') }}</a>
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
  function render() {
    const items = getWishlist().map(getProductById).filter(Boolean);
    document.getElementById("wl-empty").style.display = items.length ? "none" : "block";
    document.getElementById("wishlist-grid").innerHTML = items.map(p => {
      const price = p.salePrice ? `<span class="now">${formatRupiah(p.salePrice)}</span> <del>${formatRupiah(p.price)}</del>` : formatRupiah(p.price);
      return `<article class="card">
        <a class="card__link" href="{{ route('products.show') }}?id=${p.id}"></a>
<div class="card__media">
  <img src="${p.images[0]}" alt="${p.name}" loading="lazy">
  <div class="card__quickadd">
    <button class="btn btn-primary btn-sm btn-block" style="position:relative;z-index:3;" onclick="event.preventDefault();quickAdd('${p.id}')">${t('common.add_to_cart')}</button>
  </div>
</div>     
        <div class="card__body">
          <h3 class="card__title">${p.name}</h3>
          <div class="card__price">${price}</div>
          <button class="btn btn-ghost btn-sm btn-block" style="margin-top:10px;position:relative;z-index:3;" data-remove="${p.id}">${t('wishlist.remove')}</button>
        </div>
      </article>`;
    }).join("");
document.querySelectorAll("[data-remove]").forEach(b => b.onclick = e => { e.preventDefault(); toggleWishlist(b.dataset.remove).then(() => render()); });
  }
  render();
</script>
@endsection
