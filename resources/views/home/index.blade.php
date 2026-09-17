@extends('layouts.app')

@section('title', __('ui.home.title'))

@section('content')

<nav class="navbar">
  <div class="container navbar__row">
    <a href="{{ route('home') }}" class="wordmark">Frennz.Stuff</a>
    <div class="nav-links">
      <a href="{{ route('home') }}" aria-current="page">{{ __('ui.nav.home') }}</a>
      <a href="{{ route('products.index') }}">{{ __('ui.nav.shop') }}</a>
      <a href="{{ route('products.index') }}">{{ __('ui.nav.categories') }}</a>
      <a href="#about">{{ __('ui.nav.about') }}</a>
      <a href="#contact">{{ __('ui.nav.contact') }}</a>
    </div>
    <div class="nav-actions">
      <div class="nav-search">
        <svg class="icon" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.6" y2="16.6"/></svg>
        <input type="text" id="quick-search" placeholder="{{ __('ui.nav.search_placeholder') }}">
      </div>
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
      <button class="nav-icon-btn burger" aria-label="{{ __('ui.nav.open_menu') }}">
        <svg class="icon" viewBox="0 0 24 24"><line x1="4" y1="7" x2="20" y2="7"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="17" x2="20" y2="17"/></svg>
      </button>
    </div>
  </div>
</nav>

<main>
  <section class="hero-banner">
    <div class="container hero-banner__grid">
      <div class="reveal">
        <h1>{{ __('ui.home.hero_title') }}</h1>
        <p>{{ __('ui.home.hero_sub') }}</p>
        <div class="hero-banner__cta">
          <a href="{{ route('products.index') }}" class="btn btn-primary">{{ __('ui.home.cta_shop') }}</a>
          <a href="{{ route('products.index') }}" class="btn btn-ghost">{{ __('ui.home.cta_all') }}</a>
        </div>
      </div>
      <div class="hero-banner__media reveal">
        <img src="https://picsum.photos/seed/frennz-banner/1000/700" alt="{{ __('ui.home.hero_img_alt') }}">
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="section__head">
        <h2>{{ __('ui.home.new_arrival') }}</h2>
        <a href="{{ route('products.index') }}" class="section__link">{{ __('ui.home.see_all') }}
          <svg class="icon" style="width:14px;height:14px" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>
      <div class="grid" id="new-arrivals"></div>
    </div>
  </section>

  <section class="section section--cloud">
    <div class="container">
      <div class="section__head">
        <h2>{{ __('ui.home.best_seller') }}</h2>
        <a href="{{ route('products.index') }}" class="section__link">{{ __('ui.home.see_all') }}
          <svg class="icon" style="width:14px;height:14px" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>
      <div class="grid" id="best-sellers"></div>
    </div>
  </section>

  <section class="section" id="about" style="padding-bottom:0;">
    <div class="container" style="text-align:center;max-width:640px;">
      <h2>{{ __('ui.home.about_title') }}</h2>
      <p class="text-muted" style="margin-top:14px;font-size:15px;">{{ __('ui.home.about_text') }}</p>
      <a href="{{ route('products.index') }}" class="btn btn-primary" style="margin-top:24px;">{{ __('ui.home.explore') }}</a>
    </div>
  </section>
</main>

<footer class="site-footer" id="contact">
  <div class="container">
    <div class="footer-grid">
      <div>
        <h4 style="font-size:17px;">Frennz.Stuff</h4>
        <p class="text-muted" style="margin-top:10px;font-size:13.5px;max-width:32ch">{{ __('ui.footer.blurb') }}</p>
      </div>
      <div>
        <h4>{{ __('ui.footer.shop_heading') }}</h4>
        <ul><li><a href="{{ route('products.index') }}">{{ __('ui.footer.all_products') }}</a></li><li><a href="{{ route('cart.index') }}">{{ __('ui.footer.cart') }}</a></li><li><a href="{{ route('wishlist.index') }}">{{ __('ui.footer.wishlist') }}</a></li></ul>
      </div>
      <div>
        <h4>{{ __('ui.footer.help_heading') }}</h4>
        <ul><li><a href="#">{{ __('ui.footer.size_guide') }}</a></li><li><a href="#">{{ __('ui.footer.shipping_returns') }}</a></li><li><a href="mailto:hello@frennz.stuff">hello@frennz.stuff</a></li></ul>
      </div>
      <div>
        <h4>{{ __('ui.footer.company_heading') }}</h4>
        <ul><li><a href="#about">{{ __('ui.footer.about_us') }}</a></li><li><a href="#">Instagram</a></li><li><a href="{{ route('admin.dashboard') }}">{{ __('ui.footer.admin_panel') }}</a></li></ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>{{ __('ui.footer.copyright', ['year' => date('Y')]) }}</span>
      <span>{{ __('ui.footer.note') }}</span>
    </div>
  </div>
</footer>

@endsection

@section('scripts')
<script>
  function productCard(p) {
    const price = p.salePrice ? `<span class="now">${formatRupiah(p.salePrice)}</span> <del>${formatRupiah(p.price)}</del>` : formatRupiah(p.price);
    return `<article class="card">
      ${p.tag === "NEW" ? `<span class="card__flag">${t('common.new_flag')}</span>` : ""}
      <a class="card__link" href="{{ route('products.show') }}?id=${p.id}" aria-label="${p.name}"></a>
<div class="card__media">
  <img src="${p.images[0]}" alt="${p.name}" loading="lazy">
  <div class="card__quickadd">
    <button class="btn btn-primary btn-sm btn-block" style="position:relative;z-index:3;" onclick="event.preventDefault();quickAdd('${p.id}')">${t('common.add_to_cart')}</button>
  </div>
</div>
      <div class="card__body">
        <h3 class="card__title">${p.name}</h3>
        <div class="card__price">${price}</div>
      </div>
    </article>`;
  }
  function quickAdd(id) {
    const p = getProductById(id);
    addToCart(id, p.sizes[0], p.colors[0].name, 1);
  }

  const products = getProducts();
  const newGrid = document.getElementById("new-arrivals");
  const bestGrid = document.getElementById("best-sellers");
  withSkeleton(newGrid, 4, () => { newGrid.innerHTML = products.slice(0,4).map(productCard).join(""); });
  withSkeleton(bestGrid, 4, () => { bestGrid.innerHTML = products.filter(p=>p.bestseller).slice(0,4).map(productCard).join(""); }, 380);

  document.getElementById("quick-search").addEventListener("keydown", e => {
    if (e.key === "Enter" && e.target.value.trim()) location.href = "{{ route('products.index') }}?q=" + encodeURIComponent(e.target.value.trim());
  });
</script>
@endsection
