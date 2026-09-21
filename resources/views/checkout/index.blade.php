@extends('layouts.app')

@section('title', __('ui.checkout.title'))

@section('content')

<nav class="navbar">
  <div class="container navbar__row">
    <a href="{{ route('home') }}" class="wordmark">Frennz.Stuff</a>
    <div class="nav-links"><a href="{{ route('products.index') }}">{{ __('ui.nav.shop') }}</a><a href="{{ route('cart.index') }}">{{ __('ui.nav.cart') }}</a></div>
    <div class="nav-actions">
      @include('partials.lang-switch')
      <a class="nav-icon-btn" href="{{ route('cart.index') }}" aria-label="{{ __('ui.nav.cart') }}">
        <svg class="icon" viewBox="0 0 24 24"><path d="M6 8h12l-1 12H7L6 8z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg>
        <span class="nav-count" data-cart-count style="display:none">0</span>
      </a>
      <a class="nav-icon-btn" href="{{ route('profile.index') }}" aria-label="{{ __('ui.nav.account') }}">
        <svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="8" r="3.6"/><path d="M4.5 20c1.4-3.6 4.4-5.5 7.5-5.5s6.1 1.9 7.5 5.5"/></svg>
      </a>
    </div>
  </div>
</nav>

<main class="container">
  <div class="crumbs"><a href="{{ route('home') }}">{{ __('ui.shop.crumb_home') }}</a> / <a href="{{ route('cart.index') }}">{{ __('ui.nav.cart') }}</a> / <span>Checkout</span></div>
  <div class="section__head" style="margin-top:8px;"><h2>Checkout</h2></div>
  <div id="checkout-content"></div>
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
  const rawCart = getCart();
  // Buang item yang produknya sudah tidak ada (mis. dihapus admin),
  // bukan berdasarkan format ID — ID fallback 'f01' tetap valid.
  const cart = rawCart.filter(item => getProductById(item.productId));
  if (cart.length !== rawCart.length) {
    if (window.IS_AUTHENTICATED) { window.SERVER_CART_ITEMS = cart; } else { saveCart(cart); }
    showToast(t('checkout.removed_old'));
  }
  const contentEl = document.getElementById("checkout-content");
  const ADDRESS_ROUTES = window.ADDRESS_ROUTES || {};
  let addresses = (window.SERVER_ADDRESSES || []).slice();
  let selectedId = addresses.length ? ((addresses.find(a => a.isDefault) || addresses[0]).id) : null;
  let editingId = null;

  function apiAddress(url, method, body) {
    return fetch(url, {
      method,
      headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": CSRF_TOKEN, "Accept": "application/json" },
      body: body ? JSON.stringify(body) : null,
    })
      .then(async (r) => {
        let d = null;
        try { d = await r.json(); } catch (e) {}
        if (!r.ok) {
          const firstError = d && d.errors ? Object.values(d.errors)[0]?.[0] : null;
          return { ok: false, message: firstError || (d && d.message) || t('messages.server_error', { status: r.status }) };
        }
        return d || { ok: false, message: t('messages.bad_response') };
      })
      .catch(() => ({ ok: false, message: t('messages.conn_fail') }));
  }

  if (!cart.length) {
    contentEl.innerHTML = `<div class="empty-state"><h3>${t('checkout.empty_title')}</h3><a href="{{ route('products.index') }}" class="btn btn-primary" style="margin-top:16px">${t('cart.shop_now')}</a></div>`;
  } else {
    const voucher = currentVoucherDiscount();
    const shipCostBase = cartTotal() >= 300000 ? 0 : 20000;
    const profile = window.BUYER_PROFILE || {};

    contentEl.innerHTML = `
      <form class="checkout-layout" id="checkout-form">
        <div>
          <div class="checkout-section">
            <h3><span class="step-num">1</span>${t('checkout.step1')}</h3>
            <div class="form-grid">
              <div class="field"><label>${t('checkout.f_name')}</label><input required name="name" value="${profile.name || ''}"></div>
              <div class="field"><label>${t('checkout.f_phone')}</label><input required name="phone" type="tel" placeholder="${t('checkout.f_phone_ph')}" value="${profile.phone || ''}"></div>
              <div class="field full"><label>${t('checkout.f_email')}</label><input required name="email" type="email" value="${profile.email || ''}"></div>
            </div>
          </div>

          <div class="checkout-section">
            <h3><span class="step-num">2</span>${t('checkout.step2')}</h3>
            <div id="address-list"></div>
            <div class="form-grid" style="margin-top:14px;">
              <div class="field full"><label>${t('checkout.f_address')}</label><input required name="address" placeholder="${t('checkout.f_address_ph')}"></div>
              <div class="field"><label>${t('checkout.f_city')}</label><input required name="city"></div>
              <div class="field"><label>${t('checkout.f_postal')}</label><input required name="postal"></div>
            </div>
            <div id="address-extra" style="display:none;margin-top:12px;">
              <div class="form-grid">
                <div class="field full"><label>${t('checkout.f_label')}</label><input name="label" id="addr-label" placeholder="${t('checkout.f_label_ph')}"></div>
              </div>
              <label class="radio-card" style="margin-top:8px;"><input type="checkbox" id="addr-default"> ${t('checkout.save_default')}</label>
            </div>
            <div id="address-actions" style="display:flex;gap:10px;margin-top:12px;flex-wrap:wrap;"></div>
          </div>

          <div class="checkout-section">
            <h3><span class="step-num">3</span>${t('checkout.step3')}</h3>
            <label class="radio-card"><input type="radio" name="courier" value="Reguler" checked> ${t('checkout.courier_reg')} — ${shipCostBase === 0 ? t('common.free') : formatRupiah(shipCostBase)}</label>
            <label class="radio-card"><input type="radio" name="courier" value="Express"> ${t('checkout.courier_expr')} — ${formatRupiah(45000)}</label>
          </div>

          <div class="checkout-section">
            <h3><span class="step-num">4</span>${t('checkout.step4')}</h3>
            <label class="radio-card"><input type="radio" name="payment" value="Transfer Bank" checked> ${t('checkout.pay_transfer')}</label>
            <label class="radio-card"><input type="radio" name="payment" value="E-Wallet"> ${t('checkout.pay_ewallet')}</label>
            <label class="radio-card"><input type="radio" name="payment" value="COD"> ${t('checkout.pay_cod')}</label>
          </div>
        </div>

        <div class="summary-box">
          <h3>${t('cart.summary')}</h3>
          ${cart.map(item => {
            const p = getProductById(item.productId);
            if (!p) return "";
            return `<div class="summary-row"><span>${p.name} (${item.size}) ×${item.qty}</span><span>${formatRupiah((p.salePrice||p.price)*item.qty)}</span></div>`;
          }).join("")}
          <div class="summary-row"><span>${t('checkout.shipping')}</span><span id="ship-label"></span></div>
          ${voucher.code ? `<div class="summary-row"><span>${t('admin.voucher')} ${voucher.code}</span><span>-${formatRupiah(voucher.discount)}</span></div>` : ""}
          <div class="summary-row total"><span>${t('cart.total')}</span><span id="total-label"></span></div>
          <button type="submit" class="btn btn-primary btn-block" style="margin-top:16px">${t('checkout.create')}</button>
        </div>
      </form>`;

    const form = document.getElementById("checkout-form");
    // form.elements.namedItem: properti form.name menutupi input name="name",
    // jadi akses input harus lewat elements.
    const field = (n) => form.elements.namedItem(n);

    function addressCard(a) {
      return `<label class="radio-card" style="align-items:flex-start;">
        <input type="radio" name="addr" value="${a.id}" ${a.id === selectedId ? "checked" : ""}>
        <span style="flex:1;">
          <strong>${a.label ? a.label + " — " : ""}${a.name}</strong>${a.isDefault ? ` <span class="badge">${t('checkout.default_badge')}</span>` : ""}<br>
          <span class="text-muted" style="font-size:12.5px;">${a.phone} · ${a.address}, ${a.city} ${a.postal}</span>
        </span>
        <span style="display:flex;gap:6px;flex-wrap:wrap;">
          <button type="button" class="btn btn-ghost btn-sm" data-edit="${a.id}">${t('checkout.edit_address')}</button>
          ${a.isDefault ? "" : `<button type="button" class="btn btn-ghost btn-sm" data-default="${a.id}">${t('checkout.set_default')}</button>`}
          <button type="button" class="btn btn-ghost btn-sm" data-del="${a.id}">${t('admin.delete')}</button>
        </span>
      </label>`;
    }

    function renderAddressList() {
      const list = document.getElementById("address-list");
      list.innerHTML = addresses.map(addressCard).join("") + `
        <label class="radio-card" style="margin-top:8px;">
          <input type="radio" name="addr" value="new" ${selectedId === null ? "checked" : ""}>
          <span>${t('checkout.use_new_address')}</span>
        </label>
        ${addresses.length ? "" : `<p class="text-muted" style="font-size:12.5px;margin-top:6px;">${t('checkout.no_saved_address')}</p>`}`;

      list.querySelectorAll('input[name="addr"]').forEach(r => r.onchange = () => {
        selectedId = r.value === "new" ? null : Number(r.value);
        editingId = null;
        renderAddressList();
      });
      list.querySelectorAll("[data-edit]").forEach(b => b.onclick = e => {
        e.preventDefault();
        editingId = Number(b.dataset.edit);
        selectedId = Number(b.dataset.edit);
        renderAddressList();
      });
      list.querySelectorAll("[data-del]").forEach(b => b.onclick = e => {
        e.preventDefault();
        deleteAddress(Number(b.dataset.del));
      });
      list.querySelectorAll("[data-default]").forEach(b => b.onclick = e => {
        e.preventDefault();
        setDefaultAddress(Number(b.dataset.default));
      });

      syncForm();
    }

    function syncForm() {
      const a = addresses.find(x => x.id === selectedId) || null;
      const locked = !!a && editingId === null;

      ["name", "phone", "address", "city", "postal"].forEach(n => { field(n).readOnly = locked; });

      if (a) {
        field("name").value = a.name;
        field("phone").value = a.phone;
        field("address").value = a.address;
        field("city").value = a.city;
        field("postal").value = a.postal;
      } else if (editingId === null && selectedId === null) {
        field("name").value = field("name").value || (profile.name || "");
        field("phone").value = field("phone").value || (profile.phone || "");
        field("address").value = "";
        field("city").value = "";
        field("postal").value = "";
      }

      const editable = !locked;
      document.getElementById("address-extra").style.display = editable ? "block" : "none";

      const actions = document.getElementById("address-actions");
      if (locked) {
        actions.innerHTML = `<button type="button" class="btn btn-ghost btn-sm" id="addr-edit">${t('checkout.edit_address')}</button>`;
        document.getElementById("addr-edit").onclick = () => { editingId = a.id; renderAddressList(); };
      } else {
        actions.innerHTML = `
          <button type="button" class="btn btn-primary btn-sm" id="addr-save">${t('checkout.save_address')}</button>
          ${editingId !== null ? `<button type="button" class="btn btn-ghost btn-sm" id="addr-cancel">${t('checkout.cancel')}</button>` : ""}`;
        document.getElementById("addr-save").onclick = saveAddress;
        const cancel = document.getElementById("addr-cancel");
        if (cancel) cancel.onclick = () => { editingId = null; renderAddressList(); };
      }
    }

    function saveAddress() {
      const payload = {
        label: document.getElementById("addr-label").value || null,
        recipient_name: field("name").value,
        recipient_phone: field("phone").value,
        address_line: field("address").value,
        city: field("city").value,
        postal: field("postal").value,
        is_default: document.getElementById("addr-default").checked,
      };
      if (!payload.recipient_name || !payload.recipient_phone || !payload.address_line || !payload.city || !payload.postal) {
        showToast(t('checkout.address_save_fail'));
        return;
      }
      const url = editingId ? `${ADDRESS_ROUTES.base}/${editingId}` : ADDRESS_ROUTES.store;
      const method = editingId ? "PUT" : "POST";
      apiAddress(url, method, payload).then(res => {
        if (res.ok) {
          addresses = res.addresses;
          selectedId = res.address.id;
          editingId = null;
          renderAddressList();
          showToast(t('checkout.address_saved'));
        } else {
          showToast(res.message || t('checkout.address_save_fail'));
        }
      });
    }

    function deleteAddress(id) {
      apiAddress(`${ADDRESS_ROUTES.base}/${id}`, "DELETE").then(res => {
        if (res.ok) {
          addresses = res.addresses;
          if (selectedId === id) selectedId = addresses.length ? ((addresses.find(a => a.isDefault) || addresses[0]).id) : null;
          if (editingId === id) editingId = null;
          renderAddressList();
          showToast(t('messages.address_deleted'));
        } else {
          showToast(res.message || t('messages.address_fail'));
        }
      });
    }

    function setDefaultAddress(id) {
      apiAddress(`${ADDRESS_ROUTES.base}/${id}/default`, "PATCH").then(res => {
        if (res.ok) {
          addresses = res.addresses;
          renderAddressList();
          showToast(t('messages.address_default_set'));
        } else {
          showToast(res.message || t('messages.address_fail'));
        }
      });
    }

    const courierCost = { Reguler: shipCostBase, Express: 45000 };
    function refreshTotals(courier) {
      const cost = courierCost[courier];
      document.getElementById("ship-label").textContent = cost === 0 ? t('common.free') : formatRupiah(cost);
      const discount = voucher.discount;
      document.getElementById("total-label").textContent = formatRupiah(Math.max(0, cartTotal() + cost - discount));
    }
    refreshTotals("Reguler");
    contentEl.querySelectorAll('input[name="courier"]').forEach(r => r.addEventListener("change", e => refreshTotals(e.target.value)));

    renderAddressList();

    form.addEventListener("submit", e => {
      e.preventDefault();
      if (selectedId === null && editingId !== null) { showToast(t('checkout.address_save_fail')); return; }
      const fd = new FormData(e.target);
      const discount = voucher.discount;
      const submitBtn = e.target.querySelector('button[type="submit"]');
      submitBtn.disabled = true;
      submitBtn.textContent = t('checkout.processing');

      const payload = {
        name: fd.get("name"),
        phone: fd.get("phone"),
        email: fd.get("email"),
        address: fd.get("address"),
        city: fd.get("city"),
        postal: fd.get("postal"),
        courier: fd.get("courier"),
        payment: fd.get("payment"),
        address_id: selectedId,
        voucher_code: voucher.code,
        discount: discount,
        items: cart.map(item => ({
          product_id: parseInt(item.productId, 10),
          size: item.size,
          color: item.color,
          qty: item.qty,
        })),
      };

      apiCart("{{ route('checkout.store') }}", "POST", payload).then(res => {
        if (res.ok) {
          sessionStorage.removeItem("frennz_voucher");
          clearCart();
          location.href = "{{ route('order.success') }}?id=" + res.orderId;
        } else {
          showToast(res.message || t('messages.order_fail'));
          submitBtn.disabled = false;
          submitBtn.textContent = t('checkout.create');
        }
      });
    });
  }
</script>
@endsection