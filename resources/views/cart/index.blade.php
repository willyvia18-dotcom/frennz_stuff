@extends('layouts.app')

@section('title', __('ui.cart.title'))

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
  <div class="crumbs"><a href="{{ route('home') }}">{{ __('ui.shop.crumb_home') }}</a> / <span>{{ __('ui.nav.cart') }}</span></div>
  <div class="section__head" style="margin-top:8px;"><h2>{{ __('ui.cart.heading') }}</h2><span class="text-muted" id="item-count" style="font-size:13.5px;"></span></div>
  <div id="cart-content"></div>
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
    const cart = getCart();
    document.getElementById("item-count").textContent = t('common.item_n', { n: cartCount() });

    if (!cart.length) {
      document.getElementById("cart-content").innerHTML = `
        <div class="empty-state">
          <h3>${t('cart.empty_title')}</h3>
          <p>${t('cart.empty_text')}</p>
          <a href="{{ route('products.index') }}" class="btn btn-primary" style="margin-top:16px">${t('cart.shop_now')}</a>
        </div>`;
      return;
    }

    const rows = cart.map((item, i) => {
      const p = getProductById(item.productId);
      if (!p) return "";
      const price = p.salePrice || p.price;
      return `<tr>
        <td>
          <div class="line-item">
            <img src="${p.images[0]}" alt="${p.name}">
            <div>
              <div style="font-weight:600">${p.name}</div>
              <div class="line-item__meta">${t('cart.line_meta', { size: item.size, color: colorName(item.color) })}</div>
              <button class="remove-btn" data-remove="${i}">${t('cart.remove')}</button>
            </div>
          </div>
        </td>
        <td>
          <div class="qty-pill"><button data-dec="${i}" aria-label="${t('product.decrease')}">−</button><span>${item.qty}</span><button data-inc="${i}" aria-label="${t('product.increase')}">+</button></div>
        </td>
        <td>${formatRupiah(price)}</td>
        <td style="font-weight:600">${formatRupiah(price*item.qty)}</td>
      </tr>`;
    }).join("");

    document.getElementById("cart-content").innerHTML = `
      <div class="cart-layout">
        <div style="overflow-x:auto">
          <table class="table">
            <thead><tr><th>${t('cart.th_product')}</th><th>${t('cart.th_qty')}</th><th>${t('cart.th_price')}</th><th>${t('cart.th_total')}</th></tr></thead>
            <tbody>${rows}</tbody>
          </table>
        </div>
        <div class="summary-box">
          <h3>${t('cart.summary')}</h3>
          <div class="summary-row"><span>${t('cart.subtotal')}</span><span>${formatRupiah(cartTotal())}</span></div>
          <div class="summary-row"><span>${t('cart.shipping_est')}</span><span>${cartTotal() >= 300000 ? t('common.free') : formatRupiah(20000)}</span></div>
          <div class="voucher-row">
            <input type="text" id="voucher-input" placeholder="${t('cart.voucher_placeholder')}">
            <button class="btn btn-ghost btn-sm" id="voucher-btn">${t('cart.voucher_apply')}</button>
          </div>
          <div id="voucher-feedback" class="text-muted" style="font-size:12.5px;margin-top:-8px;margin-bottom:10px;"></div>
          <div class="summary-row total"><span>${t('cart.total')}</span><span id="total-label"></span></div>
          <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-block" style="margin-top:16px">${t('cart.checkout')}</a>
          <a href="{{ route('products.index') }}" class="btn btn-ghost btn-block" style="margin-top:10px">${t('cart.continue')}</a>
        </div>
      </div>`;

    updateTotal();
    document.querySelectorAll("[data-remove]").forEach(b => b.onclick = () => removeFromCart(+b.dataset.remove));
    document.querySelectorAll("[data-inc]").forEach(b => b.onclick = () => { const i=+b.dataset.inc; updateCartQty(i, getCart()[i].qty+1); });
    document.querySelectorAll("[data-dec]").forEach(b => b.onclick = () => { const i=+b.dataset.dec; updateCartQty(i, getCart()[i].qty-1); });
    document.getElementById("voucher-btn").onclick = () => {
      const code = document.getElementById("voucher-input").value;
      const result = applyVoucher(code);
      const fb = document.getElementById("voucher-feedback");
      if (result) {
        sessionStorage.setItem("frennz_voucher", JSON.stringify(result));
        fb.textContent = t('cart.voucher_applied', { code: result.code, amount: formatRupiah(result.discount) });
        fb.style.color = "var(--accent)";
      } else {
        sessionStorage.removeItem("frennz_voucher");
        fb.textContent = t('cart.voucher_invalid');
        fb.style.color = "var(--danger)";
      }
      updateTotal();
    };
  }

  function updateTotal() {
    const shipCost = cartTotal() >= 300000 ? 0 : 20000;
    let voucher = null;
    try { voucher = JSON.parse(sessionStorage.getItem("frennz_voucher")); } catch(e){}
    const discount = voucher ? voucher.discount : 0;
    const total = Math.max(0, cartTotal() + shipCost - discount);
    const label = document.getElementById("total-label");
    if (label) label.textContent = formatRupiah(total);
  }

  window.onCartChange = render;
  render();
</script>
@endsection
