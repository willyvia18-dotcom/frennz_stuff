@extends('layouts.app')

@section('title', __('ui.shop.title'))

@section('styles')
<style>
  .shop-layout{display:grid;grid-template-columns:240px 1fr;gap:36px;padding:32px 0 64px;align-items:start;}
  @media(max-width:860px){.shop-layout{grid-template-columns:1fr;}}
  .filter-group{margin-bottom:26px;}
  .filter-group h4{font-size:12px;text-transform:uppercase;letter-spacing:.04em;color:var(--stone);font-weight:600;margin-bottom:12px;}
  .filter-list{display:flex;flex-direction:column;gap:9px;}
  .filter-check{display:flex;align-items:center;gap:9px;font-size:13.5px;cursor:pointer;color:var(--ink-soft);}
  .filter-check input{accent-color:var(--ink);width:15px;height:15px;}
  .toolbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;flex-wrap:wrap;gap:12px;}
  .toolbar select{border:1.5px solid var(--line);border-radius:var(--r-sm);padding:10px 14px;font-family:inherit;font-size:13px;background:#fff;}
  .price-range input[type=range]{width:100%;accent-color:var(--ink);}
  .price-range__labels{display:flex;justify-content:space-between;font-size:12px;color:var(--stone);margin-top:6px;}
</style>
@endsection

@section('content')

<nav class="navbar">
  <div class="container navbar__row">
    <a href="{{ route('home') }}" class="wordmark">Frennz.Stuff</a>
    <div class="nav-links">
      <a href="{{ route('home') }}">{{ __('ui.nav.home') }}</a>
      <a href="{{ route('products.index') }}" aria-current="page">{{ __('ui.nav.shop') }}</a>
      <a href="{{ route('products.index') }}">{{ __('ui.nav.categories') }}</a>
      <a href="{{ route('home') }}#about">{{ __('ui.nav.about') }}</a>
      <a href="{{ route('home') }}#contact">{{ __('ui.nav.contact') }}</a>
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

<main class="container">
  <div class="crumbs"><a href="{{ route('home') }}">{{ __('ui.shop.crumb_home') }}</a> / <span>{{ __('ui.nav.shop') }}</span></div>
  <div class="shop-layout">
    <aside>
      <div class="filter-group">
        <h4>{{ __('ui.shop.filter_category') }}</h4>
        <div class="filter-list" id="filter-category"></div>
      </div>
      <div class="filter-group">
        <h4>{{ __('ui.shop.filter_size') }}</h4>
        <div class="filter-list" id="filter-size"></div>
      </div>
      <div class="filter-group">
        <h4>{{ __('ui.shop.filter_color') }}</h4>
        <div class="filter-list" id="filter-color"></div>
      </div>
      <div class="filter-group">
        <h4>{{ __('ui.shop.filter_max_price') }}</h4>
        <div class="price-range">
          <input type="range" id="price-range" min="0" max="500000" step="10000" value="500000">
          <div class="price-range__labels"><span id="price-min-label"></span><span id="price-label"></span></div>
        </div>
      </div>
      <button class="btn btn-ghost btn-sm btn-block" id="reset-filters">{{ __('ui.shop.reset_filters') }}</button>
    </aside>

    <div>
      <div class="section__head" style="margin-bottom:16px;">
        <h2 id="page-title" style="font-size:22px;">{{ __('ui.shop.all_products') }}</h2>
      </div>
      <div class="toolbar">
        <span class="text-muted" style="font-size:13px" id="showing-text"></span>
        <select id="sort-select">
          <option value="default">{{ __('ui.shop.sort_default') }}</option>
          <option value="price-asc">{{ __('ui.shop.sort_price_asc') }}</option>
          <option value="price-desc">{{ __('ui.shop.sort_price_desc') }}</option>
          <option value="bestseller">{{ __('ui.shop.sort_bestseller') }}</option>
        </select>
      </div>
      <div class="grid" id="product-grid"></div>
      <div class="empty-state" id="empty-msg" style="display:none;margin-top:22px;">
        <h3>{{ __('ui.shop.empty_title') }}</h3>
        <p>{{ __('ui.shop.empty_text') }}</p>
      </div>
    </div>
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
  const products = getProducts();
  const params = new URLSearchParams(location.search);
  const state = { q: params.get("q") || "", cats: params.get("cat") ? [params.get("cat")] : [], sizes: [], colors: [], maxPrice: 500000, sort: "default" };
  document.getElementById("quick-search").value = state.q;
  document.getElementById("price-min-label").textContent = formatRupiah(0);
  document.getElementById("price-label").textContent = formatRupiah(500000);

  const allSizes = [...new Set(products.flatMap(p => p.sizes))];
  const allColors = [...new Set(products.flatMap(p => p.colors.map(c => c.name)))];

  function checkboxList(container, items, key, labelFn) {
    container.innerHTML = items.map(item => `<label class="filter-check"><input type="checkbox" value="${item}" data-filter="${key}"><span>${labelFn ? labelFn(item) : item}</span></label>`).join("");
  }
  checkboxList(document.getElementById("filter-category"), getCategories().map(c => c.name), "cats", catLabelByName);
  checkboxList(document.getElementById("filter-size"), allSizes, "sizes");
  checkboxList(document.getElementById("filter-color"), allColors, "colors", colorName);
  document.querySelectorAll('input[data-filter="cats"]').forEach(cb => { if (state.cats.includes(cb.value)) cb.checked = true; });

  function productCard(p) {
    const price = p.salePrice ? `<span class="now">${formatRupiah(p.salePrice)}</span> <del>${formatRupiah(p.price)}</del>` : formatRupiah(p.price);
    return `<article class="card">
      ${p.tag === "NEW" ? `<span class="card__flag">${t('common.new_flag')}</span>` : ""}
      <a class="card__link" href="{{ route('products.show') }}?id=${p.id}" aria-label="${p.name}"></a>
      <div class="card__media">
        <img src="${p.images[0]}" alt="${p.name}" loading="lazy">
        <div class="card__quickadd"><button class="btn btn-primary btn-sm btn-block" style="position:relative;z-index:3;" onclick="event.preventDefault();quickAdd('${p.id}')">${t('common.add_to_cart')}</button></div>
      </div>
      <div class="card__body"><h3 class="card__title">${p.name}</h3><div class="card__price">${price}</div></div>
    </article>`;
  }
  function quickAdd(id) { const p = getProductById(id); addToCart(id, p.sizes[0], p.colors[0].name, 1); }

  function applyFilters() {
    let list = products.filter(p => {
      const matchQ = !state.q || p.name.toLowerCase().includes(state.q.toLowerCase()) || p.category.toLowerCase().includes(state.q.toLowerCase());
      const matchCat = !state.cats.length || state.cats.includes(p.category);
      const matchSize = !state.sizes.length || p.sizes.some(s => state.sizes.includes(s));
      const matchColor = !state.colors.length || p.colors.some(c => state.colors.includes(c.name));
      const matchPrice = (p.salePrice || p.price) <= state.maxPrice;
      return matchQ && matchCat && matchSize && matchColor && matchPrice;
    });
    if (state.sort === "price-asc") list.sort((a,b) => (a.salePrice||a.price) - (b.salePrice||b.price));
    if (state.sort === "price-desc") list.sort((a,b) => (b.salePrice||b.price) - (a.salePrice||a.price));
    if (state.sort === "bestseller") list.sort((a,b) => (b.bestseller===true) - (a.bestseller===true));

    const grid = document.getElementById("product-grid");
    withSkeleton(grid, Math.min(list.length || 4, 8), () => { grid.innerHTML = list.map(productCard).join(""); }, 220);

    document.getElementById("empty-msg").style.display = list.length ? "none" : "block";
    document.getElementById("showing-text").textContent = t('shop.showing', { n: list.length, total: products.length });
    document.getElementById("page-title").textContent = state.cats.length === 1 ? catLabelByName(state.cats[0]) : t('shop.all_products');
  }

  document.addEventListener("change", e => {
    if (e.target.dataset.filter) {
      const key = e.target.dataset.filter, val = e.target.value;
      if (e.target.checked) state[key].push(val); else state[key] = state[key].filter(v => v !== val);
      applyFilters();
    }
  });
  document.getElementById("quick-search").addEventListener("input", e => { state.q = e.target.value; applyFilters(); });
  document.getElementById("sort-select").addEventListener("change", e => { state.sort = e.target.value; applyFilters(); });
  document.getElementById("price-range").addEventListener("input", e => {
    state.maxPrice = +e.target.value;
    document.getElementById("price-label").textContent = formatRupiah(e.target.value);
    applyFilters();
  });
  document.getElementById("reset-filters").addEventListener("click", () => {
    state.q=""; state.cats=[]; state.sizes=[]; state.colors=[]; state.maxPrice=500000; state.sort="default";
    document.getElementById("quick-search").value = "";
    document.getElementById("sort-select").value = "default";
    document.getElementById("price-range").value = 500000;
    document.getElementById("price-label").textContent = formatRupiah(500000);
    document.querySelectorAll('input[data-filter]').forEach(cb => cb.checked = false);
    applyFilters();
  });

  applyFilters();
</script>
@endsection
