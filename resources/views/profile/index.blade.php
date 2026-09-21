@extends('layouts.app')

@section('title', __('ui.profile.title'))

@section('styles')
<style>
  .pf-layout{display:grid;grid-template-columns:260px 1fr;gap:28px;padding:28px 0 64px;align-items:start;}
  @media(max-width:820px){.pf-layout{grid-template-columns:1fr;}}
  .pf-card{border:1px solid var(--line);border-radius:var(--r-lg);background:#fff;}
  .pf-side{padding:22px;position:sticky;top:20px;}
  .pf-avatar{width:56px;height:56px;border-radius:50%;background:var(--ink);color:#fff;display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:600;}
  .pf-menu{display:flex;flex-direction:column;gap:4px;margin-top:18px;}
  .pf-menu button{display:flex;align-items:center;gap:10px;width:100%;border:none;background:none;font-family:inherit;font-size:14px;text-align:left;padding:11px 12px;border-radius:var(--r-sm);cursor:pointer;color:var(--ink-soft);}
  .pf-menu button:hover{background:var(--cloud);}
  .pf-menu button.active{background:var(--ink);color:#fff;font-weight:600;}
  .pf-panel{display:none;padding:26px;}
  .pf-panel.active{display:block;}
  .pf-panel h2{font-size:20px;margin-bottom:4px;}
  .pf-stat-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin:20px 0 6px;}
  @media(max-width:560px){.pf-stat-grid{grid-template-columns:1fr;}}
  .pf-stat{border:1px solid var(--line);border-radius:var(--r-md);padding:14px 16px;}
  .pf-stat__label{font-size:11.5px;text-transform:uppercase;letter-spacing:.04em;color:var(--stone);font-weight:600;}
  .pf-stat__value{font-size:19px;font-weight:700;margin-top:6px;}
  .pf-order{border:1px solid var(--line);border-radius:var(--r-md);padding:16px;margin-top:12px;}
  .pf-order__head{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap;}
  .pf-order__meta{font-size:12.5px;color:var(--stone);margin-top:3px;}
  .pf-order__items{font-size:13.5px;color:var(--ink-soft);margin-top:10px;}
  .pf-order__foot{display:flex;justify-content:space-between;align-items:center;margin-top:12px;gap:12px;flex-wrap:wrap;}
  .pf-order__total{font-weight:700;}
  .pf-addr{border:1px solid var(--line);border-radius:var(--r-md);padding:14px 16px;margin-top:12px;}
  .pf-badge{display:inline-block;font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.03em;padding:2px 8px;border-radius:999px;background:var(--accent-tint);color:var(--accent);margin-left:6px;}
  .pf-order__detail{display:none;margin-top:12px;padding-top:12px;border-top:1px dashed var(--line);font-size:13px;color:var(--ink-soft);}
  .pf-order.open .pf-order__detail{display:block;}
  .pf-order__detail-row{display:flex;justify-content:space-between;gap:12px;padding:3px 0;}
  .pf-order__item{display:flex;justify-content:space-between;gap:12px;padding:4px 0;font-size:13.5px;}
  .pf-link-btn{border:none;background:none;color:var(--ink);font:inherit;font-size:13px;font-weight:600;cursor:pointer;text-decoration:underline;padding:0;}
</style>
@endsection

@section('content')

<nav class="navbar">
  <div class="container navbar__row">
    <a href="{{ route('home') }}" class="wordmark">Frennz.Stuff</a>
    <div class="nav-links">
      <a href="{{ route('home') }}">{{ __('ui.nav.home') }}</a>
      <a href="{{ route('products.index') }}">{{ __('ui.nav.shop') }}</a>
    </div>
    <div class="nav-actions">
      @include('partials.lang-switch')
      <a class="nav-icon-btn" href="{{ route('wishlist.index') }}" aria-label="{{ __('ui.nav.wishlist') }}">
        <svg class="icon" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        <span class="nav-count" data-wishlist-count style="display:none">0</span>
      </a>
      <a class="nav-icon-btn" href="{{ route('cart.index') }}" aria-label="{{ __('ui.nav.cart') }}">
        <svg class="icon" viewBox="0 0 24 24"><path d="M6 8h12l-1 12H7L6 8z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg>
        <span class="nav-count" data-cart-count style="display:none">0</span>
      </a>
      <a class="nav-icon-btn" href="{{ route('profile.index') }}" aria-label="{{ __('ui.nav.account') }}" aria-current="page">
        <svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="8" r="3.6"/><path d="M4.5 20c1.4-3.6 4.4-5.5 7.5-5.5s6.1 1.9 7.5 5.5"/></svg>
      </a>
    </div>
  </div>
</nav>

<main class="container">
  <div class="crumbs"><a href="{{ route('home') }}">{{ __('ui.shop.crumb_home') }}</a> / <span>{{ __('ui.profile.heading') }}</span></div>

  <div class="pf-layout">
    <aside class="pf-card pf-side">
      <div style="display:flex;align-items:center;gap:14px;">
        <div class="pf-avatar">{{ $user['initial'] }}</div>
        <div style="min-width:0;">
          <div style="font-weight:700;font-size:15px;overflow:hidden;text-overflow:ellipsis;">{{ $user['name'] }}</div>
          <div class="text-muted" style="font-size:12.5px;overflow:hidden;text-overflow:ellipsis;">{{ $user['email'] }}</div>
        </div>
      </div>
      <div class="pf-menu">
        <button type="button" class="active" data-tab="orders">
          <svg class="icon" viewBox="0 0 24 24"><path d="M6 2h12l1 5H5l1-5z"/><path d="M5 7h14v13H5z"/><path d="M9 11h6"/></svg>
          {{ __('ui.profile.tab_orders') }}
        </button>
        <button type="button" data-tab="profile">
          <svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="8" r="3.6"/><path d="M4.5 20c1.4-3.6 4.4-5.5 7.5-5.5s6.1 1.9 7.5 5.5"/></svg>
          {{ __('ui.profile.tab_profile') }}
        </button>
        <button type="button" data-tab="addresses">
          <svg class="icon" viewBox="0 0 24 24"><path d="M12 21s-7-5.6-7-11a7 7 0 0 1 14 0c0 5.4-7 11-7 11z"/><circle cx="12" cy="10" r="2.6"/></svg>
          {{ __('ui.profile.tab_addresses') }}
        </button>
        <button type="button" data-tab="settings">
          <svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19 12a7 7 0 0 0-.1-1.3l2-1.5-2-3.4-2.3 1a7 7 0 0 0-2.2-1.3L14 3h-4l-.4 2.5a7 7 0 0 0-2.2 1.3l-2.3-1-2 3.4 2 1.5A7 7 0 0 0 5 12c0 .4 0 .9.1 1.3l-2 1.5 2 3.4 2.3-1a7 7 0 0 0 2.2 1.3L10 21h4l.4-2.5a7 7 0 0 0 2.2-1.3l2.3 1 2-3.4-2-1.5c.1-.4.1-.9.1-1.3z"/></svg>
          {{ __('ui.profile.tab_settings') }}
        </button>
      </div>
    </aside>

    <section class="pf-card">
      <div class="pf-panel active" id="panel-orders">
        <h2>{{ __('ui.profile.greeting', ['name' => $user['name']]) }}</h2>
        <p class="text-muted" style="font-size:13.5px;">{{ __('ui.profile.sub') }}</p>

        <div class="pf-stat-grid">
          <div class="pf-stat"><div class="pf-stat__label">{{ __('ui.profile.stat_orders') }}</div><div class="pf-stat__value">{{ $stats['orders'] }}</div></div>
          <div class="pf-stat"><div class="pf-stat__label">{{ __('ui.profile.stat_active') }}</div><div class="pf-stat__value">{{ $stats['active'] }}</div></div>
          <div class="pf-stat"><div class="pf-stat__label">{{ __('ui.profile.stat_spend') }}</div><div class="pf-stat__value" id="stat-spend">—</div></div>
        </div>

        <div id="orders-list" style="margin-top:18px;"></div>
      </div>

      <div class="pf-panel" id="panel-profile">
        <h2>{{ __('ui.profile.tab_profile') }}</h2>
        <form id="profile-form" style="margin-top:18px;max-width:460px;">
          <div class="field">
            <label>{{ __('ui.profile.f_name') }}</label>
            <input required name="name" value="{{ $user['name'] }}">
          </div>
          <div class="field">
            <label>{{ __('ui.profile.f_email') }}</label>
            <input required type="email" name="email" value="{{ $user['email'] }}">
          </div>
          <div class="field">
            <label>{{ __('ui.profile.f_phone') }}</label>
            <input name="phone" type="tel" value="{{ $user['phone'] }}" placeholder="{{ __('ui.checkout.f_phone_ph') }}">
          </div>
          <div class="field">
            <label>{{ __('ui.profile.f_password') }}</label>
            <input type="password" name="password" autocomplete="new-password">
          </div>
          <div class="field">
            <label>{{ __('ui.profile.f_password_conf') }}</label>
            <input type="password" name="password_confirmation" autocomplete="new-password">
            <small class="text-muted" style="font-size:12px;">{{ __('ui.profile.f_password_hint') }}</small>
          </div>
          <button type="submit" class="btn btn-primary btn-block">{{ __('ui.profile.save_profile') }}</button>
        </form>
      </div>

      <div class="pf-panel" id="panel-addresses">
        <h2>{{ __('ui.profile.tab_addresses') }}</h2>
        <div id="addresses-list"></div>
      </div>

      <div class="pf-panel" id="panel-settings">
        <h2>{{ __('ui.profile.tab_settings') }}</h2>
        <div style="display:flex;flex-direction:column;gap:12px;margin-top:18px;max-width:460px;">
          <form method="POST" action="{{ route('switch.account') }}">
            @csrf
            <button type="submit" class="btn btn-ghost btn-block" style="justify-content:flex-start;">
              {{ __('ui.profile.switch_account') }} — <span class="text-muted" style="font-weight:400;margin-left:6px;">{{ __('ui.profile.switch_account_desc') }}</span>
            </button>
          </form>
          <form id="logout-form" method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline btn-block" style="justify-content:flex-start;color:var(--danger);border-color:var(--danger);">
              {{ __('ui.profile.logout') }}
            </button>
          </form>
        </div>
      </div>
    </section>
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
  const orders = @json($orders ?? []);
  const addresses = @json($addresses ?? []);
  const totalSpend = {{ (float) ($stats['spend'] ?? 0) }};
  const logoutConfirmText = @json(__('ui.profile.logout_confirm'));
  document.getElementById("stat-spend").textContent = formatRupiah(totalSpend);
  const statusClass = { pending:"processing", processing:"processing", shipped:"shipped", completed:"done", cancelled:"cancelled" };
  const statusLabel = { pending:t('admin.status_pending'), processing:t('admin.status_processing'), shipped:t('admin.status_shipped'), completed:t('admin.status_completed'), cancelled:t('admin.status_cancelled') };

  function activateTab(name) {
    document.querySelectorAll(".pf-menu button").forEach(b => b.classList.toggle("active", b.dataset.tab === name));
    document.querySelectorAll(".pf-panel").forEach(p => p.classList.toggle("active", p.id === "panel-" + name));
  }
  document.querySelectorAll(".pf-menu button").forEach(btn => btn.onclick = () => activateTab(btn.dataset.tab));
  try {
    const tabParam = new URLSearchParams(location.search).get("tab");
    if (tabParam && document.getElementById("panel-" + tabParam)) activateTab(tabParam);
  } catch (e) {}

  function esc(s) {
    return String(s ?? "").replace(/[&<>"']/g, c => ({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;"}[c]));
  }
  function formatOrderDate(iso) {
    if (!iso) return "—";
    const d = new Date(iso);
    if (isNaN(d.getTime())) return "—";
    return d.toLocaleDateString(lsTag(), { day:"numeric", month:"long", year:"numeric" });
  }

  // --- Riwayat pesanan (dari database, bukan localStorage) ---
  const ordersEl = document.getElementById("orders-list");
  if (!Array.isArray(orders) || !orders.length) {
    ordersEl.innerHTML = `<div class="empty-state"><h3>${t('profile.no_orders')}</h3><p>${t('profile.no_orders_text')}</p><a href="{{ route('products.index') }}" class="btn btn-primary" style="margin-top:14px">${t('cart.shop_now')}</a></div>`;
  } else {
    ordersEl.innerHTML = orders.map((o, idx) => {
      const items = Array.isArray(o.items) ? o.items : [];
      const itemCount = items.reduce((s, it) => s + (Number(it.qty) || 0), 0);
      const itemsHtml = items.length
        ? items.map(it => `<div class="pf-order__item"><span>${esc(it.name)}${it.size ? ` (${esc(it.size)})` : ""} ×${Number(it.qty) || 0}</span><span>${formatRupiah((Number(it.price) || 0) * (Number(it.qty) || 0))}</span></div>`).join("")
        : "";
      const addr = o.address || {};
      const addrLine = [addr.line, addr.city, addr.postal].filter(Boolean).join(", ");
      return `
      <div class="pf-order" data-order="${idx}">
        <div class="pf-order__head">
          <div>
            <div style="font-weight:700;">${t('profile.order_number', { id: esc(o.id) })}</div>
            <div class="pf-order__meta">${formatOrderDate(o.date)} · ${esc(o.courier || "—")} · ${esc(o.payment || "—")}</div>
          </div>
          <span class="status-pill ${statusClass[o.status] || "processing"}">${esc(statusLabel[o.status] || o.status || "—")}</span>
        </div>
        <div class="pf-order__items">${items.map(it => `${esc(it.name)}${it.size ? ` (${esc(it.size)})` : ""} ×${Number(it.qty) || 0}`).join("<br>")}</div>
        <div class="pf-order__foot">
          <span class="text-muted" style="font-size:12.5px;">${t('profile.order_items_n', { n: itemCount })}</span>
          <span style="display:flex;gap:12px;align-items:center;">
            <button type="button" class="pf-link-btn" data-toggle-detail>${t('profile.view_detail')}</button>
            <span class="pf-order__total">${formatRupiah(Number(o.total) || 0)}</span>
          </span>
        </div>
        <div class="pf-order__detail">
          ${itemsHtml}
          <div class="pf-order__detail-row"><span>${t('checkout.shipping')}</span><span>${formatRupiah(Number(o.shipping) || 0)}</span></div>
          ${Number(o.discount) > 0 ? `<div class="pf-order__detail-row"><span>Diskon${o.voucher ? ` (${esc(o.voucher)})` : ""}</span><span>-${formatRupiah(Number(o.discount))}</span></div>` : ""}
          <div class="pf-order__detail-row" style="font-weight:700;color:var(--ink);"><span>Total</span><span>${formatRupiah(Number(o.total) || 0)}</span></div>
          ${addrLine ? `<div class="pf-order__meta" style="margin-top:8px;">${esc(addrLine)}</div>` : ""}
        </div>
      </div>`;
    }).join("");
    ordersEl.querySelectorAll("[data-toggle-detail]").forEach(btn => {
      btn.onclick = () => btn.closest(".pf-order").classList.toggle("open");
    });
  }

  const addrEl = document.getElementById("addresses-list");
  addrEl.innerHTML = addresses.length ? addresses.map(a => `
    <div class="pf-addr">
      <div style="font-weight:600;">${a.label ? a.label + " — " : ""}${a.name}${a.isDefault ? `<span class="pf-badge">${t('checkout.default_badge')}</span>` : ""}</div>
      <div class="text-muted" style="font-size:13px;margin-top:4px;">${a.phone} · ${a.address}, ${a.city} ${a.postal}</div>
    </div>`).join("")
    : `<p class="text-muted" style="font-size:13.5px;margin-top:12px;">${t('checkout.no_saved_address')}</p>`;

  document.getElementById("profile-form").addEventListener("submit", e => {
    e.preventDefault();
    const fd = new FormData(e.target);
    const btn = e.target.querySelector('button[type="submit"]');
    btn.disabled = true;
    apiCart("{{ route('profile.update') }}", "PUT", {
      name: fd.get("name"),
      email: fd.get("email"),
      phone: fd.get("phone"),
      password: fd.get("password") || null,
      password_confirmation: fd.get("password_confirmation") || null,
    }).then(res => {
      btn.disabled = false;
      showToast(res.ok ? t('profile.updated') : (res.message || t('profile.update_fail')));
    });
  });

  // --- Logout: konfirmasi, bersihkan sisa sesi client, lalu POST ke /logout ---
  // Server selalu redirect ke beranda (route home) dalam keadaan guest.
  document.getElementById("logout-form").addEventListener("submit", e => {
    if (!confirm(logoutConfirmText)) {
      e.preventDefault();
      return;
    }
    try {
      sessionStorage.removeItem("frennz_voucher");
      localStorage.removeItem("frennz_orders");
    } catch (err) {}
    // biarkan form terkirim normal (POST + CSRF) -> redirect ke beranda
  });
</script>
@endsection