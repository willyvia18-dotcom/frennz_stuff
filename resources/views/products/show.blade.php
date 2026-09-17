@extends('layouts.app')

@section('title', __('ui.product.title'))

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
  <div class="crumbs"><a href="{{ route('home') }}">{{ __('ui.shop.crumb_home') }}</a> / <a href="{{ route('products.index') }}">{{ __('ui.nav.shop') }}</a> / <span id="crumb-name"></span></div>
  <div class="pdp" id="pdp"></div>
  <div class="pdp-below" id="pdp-below"></div>

  <section class="section section--cloud" style="padding-top:48px;">
    <div class="container">
      <div class="section__head"><h2 style="font-size:22px;">{{ __('ui.product.related') }}</h2></div>
      <div class="grid" id="related-grid"></div>
    </div>
  </section>
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
  const params = new URLSearchParams(location.search);
  const product = getProductById(params.get("id"));
  const pdpEl = document.getElementById("pdp");
  document.getElementById("quick-search").addEventListener("keydown", e => {
    if (e.key === "Enter" && e.target.value.trim()) location.href = "{{ route('products.index') }}?q=" + encodeURIComponent(e.target.value.trim());
  });

  if (!product) {
    pdpEl.innerHTML = `<div class="empty-state" style="grid-column:1/-1"><h3>${t('product.not_found')}</h3><a href="{{ route('products.index') }}" class="btn btn-primary" style="margin-top:14px">${t('product.back_to_shop')}</a></div>`;
  } else {
    document.title = product.name + " — Frennz.Stuff";
    document.getElementById("crumb-name").textContent = product.name;

    let state = { image: 0, size: null, color: product.colors[0].name, qty: 1 };
    function stockFor(size) { return product.stock[size] ?? 0; }

    function render() {
      const price = product.salePrice
        ? `<span class="now">${formatRupiah(product.salePrice)}</span><del>${formatRupiah(product.price)}</del>`
        : formatRupiah(product.price);
      const stock = state.size ? stockFor(state.size) : null;
      const stockLabel = state.size
        ? (stock === 0 ? `<span class="stock-dot out"></span>${t('product.stock_out')}` : stock <= 3 ? `<span class="stock-dot low"></span>${t('product.stock_low', { n: stock })}` : `<span class="stock-dot in"></span>${t('product.stock_in', { n: stock })}`)
        : t('product.stock_pick');

      pdpEl.innerHTML = `
        <div class="pdp__gallery">
          <div class="pdp__thumbs">
            ${product.images.map((img,i) => `<button class="${i===state.image?"active":""}" data-thumb="${i}"><img src="${img}" alt=""></button>`).join("")}
          </div>
          <div class="pdp__main" id="zoom-box"><img src="${product.images[state.image]}" alt="${product.name}"></div>
        </div>
        <div>
          <h1 class="pdp__title">${product.name}</h1>
          <div class="pdp__stars">
            ${"★".repeat(Math.round(product.rating))}${"☆".repeat(5-Math.round(product.rating))}
            <span class="text-muted">${product.rating} ${t('product.reviews', { n: product.reviews })}</span>
          </div>
          <div class="pdp__price">${price}</div>

          <div class="pdp__field">
            <div class="pdp__field-label"><span>${t('product.color')}</span><span>${colorName(state.color)}</span></div>
            <div class="swatch-row">
              ${product.colors.map(c => `<button class="color-dot ${c.name===state.color?"active":""}" style="background:${c.hex};border-color:${c.name===state.color?"var(--ink)":"var(--line)"}" data-color="${c.name}" title="${colorName(c.name)}" aria-label="${colorName(c.name)}"></button>`).join("")}
            </div>
          </div>

          <div class="pdp__field">
            <div class="pdp__field-label"><span>${t('product.size')}</span></div>
            <div class="swatch-row">
              ${product.sizes.map(s => `<button class="swatch ${s===state.size?"active":""}" ${stockFor(s)===0?"disabled":""} data-size="${s}">${s}</button>`).join("")}
            </div>
          </div>

          <div class="pdp__field">
            <div class="pdp__field-label"><span>${t('product.quantity')}</span></div>
            <div class="qty-pill">
              <button data-qty="dec" aria-label="${t('product.decrease')}">−</button><span>${state.qty}</span><button data-qty="inc" aria-label="${t('product.increase')}">+</button>
            </div>
          </div>

          <div class="pdp__actions">
            <button class="btn btn-outline" id="add-cart-btn" ${!state.size || stock===0 ? "disabled":""}>${t('common.add_to_cart')}</button>
            <button class="btn btn-outline" id="wishlist-btn">${isWishlisted(product.id) ? t('common.wishlist_remove') : t('common.wishlist_add')}</button>
            <button class="btn btn-primary" id="buy-now-btn" ${!state.size || stock===0 ? "disabled":""}>${t('common.buy_now')}</button>
          </div>

          <div class="pdp__meta">
            <div>${stockLabel}</div>
            <div>${t('product.delivery_note')}</div>
            <div>${t('product.exchange_note')}</div>
          </div>
        </div>`;

      pdpEl.querySelectorAll("[data-thumb]").forEach(b => b.onclick = () => { state.image = +b.dataset.thumb; render(); });
      pdpEl.querySelectorAll("[data-color]").forEach(b => b.onclick = () => { state.color = b.dataset.color; render(); });
      pdpEl.querySelectorAll("[data-size]").forEach(b => b.onclick = () => { state.size = b.dataset.size; state.qty = 1; render(); });
      pdpEl.querySelector('[data-qty="inc"]').onclick = () => { state.qty = Math.min(state.qty+1, stock||99); render(); };
      pdpEl.querySelector('[data-qty="dec"]').onclick = () => { state.qty = Math.max(1, state.qty-1); render(); };
      const addBtn = pdpEl.querySelector("#add-cart-btn");
      const buyBtn = pdpEl.querySelector("#buy-now-btn");
      if (addBtn) addBtn.onclick = () => addToCart(product.id, state.size, state.color, state.qty);
      if (buyBtn) buyBtn.onclick = () => {
        addToCart(product.id, state.size, state.color, state.qty).then(ok => {
          if (ok !== false) location.href = "{{ route('checkout.index') }}";
        });
      };

const wishBtn = pdpEl.querySelector("#wishlist-btn");
if (wishBtn) wishBtn.onclick = () => toggleWishlist(product.id).then(state => {
  if (state === null) return;
  wishBtn.textContent = state ? t('common.wishlist_remove') : t('common.wishlist_add');
});

const zoomBox = document.getElementById("zoom-box");      zoomBox.addEventListener("mouseenter", () => zoomBox.classList.add("zoomed"));
      zoomBox.addEventListener("mouseleave", () => zoomBox.classList.remove("zoomed"));
    }
    render();

    document.getElementById("pdp-below").innerHTML = `
      <div class="pdp-block">
        <h3>${t('product.desc_title')}</h3>
        <p>${descOf(product)}</p>
      </div>
      <div class="pdp-block">
        <h3>${t('product.size_guide_title')}</h3>
        <table class="size-guide-table">
          <thead><tr><th>${t('product.sg_size')}</th><th>${t('product.sg_chest')}</th><th>${t('product.sg_length')}</th></tr></thead>
          <tbody>
            <tr><td>S</td><td>50</td><td>68</td></tr>
            <tr><td>M</td><td>53</td><td>70</td></tr>
            <tr><td>L</td><td>56</td><td>72</td></tr>
            <tr><td>XL</td><td>59</td><td>74</td></tr>
          </tbody>
        </table>
      </div>
      <div class="pdp-block">
        <h3>${t('product.customer_reviews', { n: product.reviews })}</h3>
        ${TESTIMONIALS.slice(0,3).map(r => `
          <div class="review-item">
            <div class="review-item__head"><strong>${r.name}</strong><span class="text-muted">${"★".repeat(r.rating)}${"☆".repeat(5-r.rating)}</span></div>
            <p>${textOf(r)}</p>
          </div>`).join("")}
      </div>`;

    const related = getProducts().filter(p => p.category === product.category && p.id !== product.id).slice(0,4);
    function productCard(p) {
      const price = p.salePrice ? `<span class="now">${formatRupiah(p.salePrice)}</span> <del>${formatRupiah(p.price)}</del>` : formatRupiah(p.price);
      return `<article class="card">
        ${p.tag === "NEW" ? `<span class="card__flag">${t('common.new_flag')}</span>` : ""}
        <a class="card__link" href="{{ route('products.show') }}?id=${p.id}"></a>
        <div class="card__media"><img src="${p.images[0]}" alt="${p.name}" loading="lazy"></div>
        <div class="card__body"><h3 class="card__title">${p.name}</h3><div class="card__price">${price}</div></div>
      </article>`;
    }
    document.getElementById("related-grid").innerHTML = related.map(productCard).join("") || `<p class="text-muted">${t('product.no_related')}</p>`;
  }
</script>
@endsection
