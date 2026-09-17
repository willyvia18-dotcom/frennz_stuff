/* ============================================================
   FRENNZ.STUFF — store.js
   Logic bersama seluruh halaman: keranjang, wishlist, voucher,
   riwayat pesanan, toast, navbar transparan->solid saat scroll,
   util skeleton loading, dan helper i18n (ID/EN).
   Sebagian state di localStorage browser.
   ============================================================ */

const LS_KEYS = {
  cart: "frennz_cart",
  wishlist: "frennz_wishlist",
  orders: "frennz_orders",
  promos: "frennz_promos",
  customers: "frennz_customers"
};

function readLS(key, fallback) {
  try { const raw = localStorage.getItem(key); return raw ? JSON.parse(raw) : fallback; }
  catch (e) { return fallback; }
}
function writeLS(key, value) { localStorage.setItem(key, JSON.stringify(value)); }

/* ---------- I18N HELPERS ---------- */
function t(key, params) {
  const parts = key.split(".");
  let val = (window.I18N || {});
  for (const p of parts) { val = val?.[p]; }
  if (typeof val !== "string") return key;
  if (params) Object.entries(params).forEach(([k, v]) => { val = val.replaceAll(":" + k, v); });
  return val;
}
function locField(obj, field) {
  if (window.LOCALE === "en" && obj[field + "En"]) return obj[field + "En"];
  return obj[field];
}
function descOf(p) { return locField(p, "desc"); }
function textOf(r) { return locField(r, "text"); }
function roleOf(r) { return locField(r, "role"); }
function colorName(n) {
  return (window.LOCALE === "en" && window.I18N?.colors?.[n]) || n;
}
function catLabel(c) {
  const name = typeof c === "string" ? c : c.name;
  if (window.LOCALE !== "en") return name;
  const found = getCategories().find(x => x.name === name);
  return (found && (found.en || found.nameEn)) || name;
}
function catLabelByName(name) { return catLabel(name); }
function lsTag() { return window.LOCALE === "en" ? "en-GB" : "id-ID"; }

/* ---------- FORMAT HARGA (locale-aware) ---------- */
function formatRupiah(n) {
  n = Number(n);
  return window.LOCALE === "en"
    ? "IDR " + n.toLocaleString("en-US")
    : "Rp " + n.toLocaleString("id-ID");
}

/* ---------- CART ---------- */
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content;

function apiCart(url, method, body) {
  return fetch(url, {
    method,
    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": CSRF_TOKEN, "Accept": "application/json" },
    body: body ? JSON.stringify(body) : null,
  })
    .then(async (r) => {
      let data;
      try { data = await r.json(); } catch (e) { data = null; }
      if (!r.ok) {
        return { ok: false, message: (data && data.message) || t('messages.server_error', { status: r.status }) };
      }
      return data || { ok: false, message: t('messages.bad_response') };
    })
    .catch(() => ({ ok: false, message: t('messages.conn_fail') }));
}

function getCart() {
  if (window.IS_AUTHENTICATED) return window.SERVER_CART_ITEMS || [];
  return readLS(LS_KEYS.cart, []);
}

function saveCart(cart) { writeLS(LS_KEYS.cart, cart); renderCounts(); if (typeof onCartChange === "function") onCartChange(); }

function addToCart(productId, size, color, qty) {
  if (window.IS_AUTHENTICATED) {
    return apiCart("/cart", "POST", { product_id: productId, size, color, qty }).then(res => {
      if (res.ok) {
        window.SERVER_CART_ITEMS = res.cart;
        renderCounts();
        showToast(t('messages.cart_add'));
        if (typeof onCartChange === "function") onCartChange();
        return true;
      }
      showToast(res.message || t('messages.cart_add_fail'));
      return false;
    });
  }
  const cart = getCart();
  const existing = cart.find(i => i.productId === productId && i.size === size && i.color === color);
  if (existing) existing.qty += qty; else cart.push({ productId, size, color, qty });
  saveCart(cart);
  showToast(t('messages.cart_add'));
  return Promise.resolve(true);
}

function removeFromCart(index) {
  if (window.IS_AUTHENTICATED) {
    const item = getCart()[index];
    if (!item) return;
    apiCart("/cart/" + item.cartItemId, "DELETE").then(res => {
      window.SERVER_CART_ITEMS = res.cart; renderCounts();
      if (typeof onCartChange === "function") onCartChange();
    });
    return;
  }
  const cart = getCart(); cart.splice(index, 1); saveCart(cart);
}

function updateCartQty(index, qty) {
  qty = Math.max(1, qty);
  if (window.IS_AUTHENTICATED) {
    const item = getCart()[index];
    if (!item) return;
    apiCart("/cart/" + item.cartItemId, "PATCH", { qty }).then(res => {
      if (res.ok) { window.SERVER_CART_ITEMS = res.cart; renderCounts(); if (typeof onCartChange === "function") onCartChange(); }
      else showToast(res.message || t('messages.stock_low'));
    });
    return;
  }
  const cart = getCart(); if (!cart[index]) return; cart[index].qty = qty; saveCart(cart);
}

function cartCount() { return getCart().reduce((s,i) => s + i.qty, 0); }
function cartTotal() {
  return getCart().reduce((sum, i) => { const p = getProductById(i.productId); return p ? sum + (p.salePrice || p.price) * i.qty : sum; }, 0);
}
function clearCart() {
  if (window.IS_AUTHENTICATED) { window.SERVER_CART_ITEMS = []; return; }
  saveCart([]);
}

/* ---------- WISHLIST ---------- */
function getWishlist() {
  if (window.IS_AUTHENTICATED) return window.SERVER_WISHLIST || [];
  return readLS(LS_KEYS.wishlist, []);
}
function toggleWishlist(productId) {
  if (window.IS_AUTHENTICATED) {
    return apiCart("/wishlist/toggle", "POST", { product_id: productId }).then(res => {
      if (res.ok) {
        window.SERVER_WISHLIST = res.wishlist;
        renderCounts();
        showToast(res.added ? t('messages.wish_add') : t('messages.wish_remove'));
        return res.added;
      }
      showToast(res.message || t('messages.wish_fail'));
      return null;
    });
  }
  let list = getWishlist();
  const has = list.includes(productId);
  if (has) list = list.filter(id => id !== productId);
  else { list.push(productId); showToast(t('messages.wish_add')); }
  writeLS(LS_KEYS.wishlist, list);
  renderCounts();
  return Promise.resolve(!has);
}
function isWishlisted(productId) { return getWishlist().includes(productId); }
function toggleWishlistBtn(btn, id) {
  toggleWishlist(id).then(state => {
    if (state === null) return;
    const svg = btn.querySelector("svg");
    if (svg) svg.setAttribute("fill", state ? "#111110" : "none");
  });
}

/* ---------- PROMO / VOUCHER ---------- */
const DEFAULT_PROMOS = [
  { code: "FRENNZ10", type: "percent", value: 10, active: true, desc: "Diskon 10% semua produk", descEn: "10% off all products" },
  { code: "NEWMEMBER", type: "fixed", value: 25000, active: true, desc: "Potongan Rp25.000 untuk pembeli baru", descEn: "Rp25,000 off for new buyers" }
];
function getPromos() { return readLS(LS_KEYS.promos, DEFAULT_PROMOS); }
function savePromos(list) { writeLS(LS_KEYS.promos, list); }
function applyVoucher(code) {
  const promo = getPromos().find(p => p.code.toLowerCase() === code.trim().toLowerCase() && p.active);
  if (!promo) return null;
  const subtotal = cartTotal();
  const discount = promo.type === "percent" ? Math.round(subtotal * promo.value / 100) : promo.value;
  return { code: promo.code, discount: Math.min(discount, subtotal) };
}

/* ---------- ORDERS ---------- */
function getOrders() { return readLS(LS_KEYS.orders, []); }
function placeOrder(order) {
  const orders = getOrders();
  order.id = "FZ-" + Date.now().toString().slice(-8);
  order.date = new Date().toISOString();
  order.status = "Diproses";
  orders.unshift(order);
  writeLS(LS_KEYS.orders, orders);
  clearCart();
  return order.id;
}

/* ---------- TOAST ---------- */
function showToast(msg) {
  let el = document.querySelector(".toast");
  if (!el) { el = document.createElement("div"); el.className = "toast"; document.body.appendChild(el); }
  el.textContent = msg;
  el.classList.add("show");
  clearTimeout(window.__toastTimer);
  window.__toastTimer = setTimeout(() => el.classList.remove("show"), 2200);
}

/* ---------- header counts ---------- */
function renderCounts() {
  document.querySelectorAll("[data-cart-count]").forEach(el => {
    const n = cartCount(); el.textContent = n; el.style.display = n > 0 ? "flex" : "none";
  });
  document.querySelectorAll("[data-wishlist-count]").forEach(el => {
    const n = getWishlist().length; el.textContent = n; el.style.display = n > 0 ? "flex" : "none";
  });
}

/* ---------- navbar scroll + mobile menu ---------- */
function initNavbar() {
  const nav = document.querySelector(".navbar");
  if (!nav) return;
  const onScroll = () => { nav.classList.toggle("solid", window.scrollY > 40); };
  onScroll();
  window.addEventListener("scroll", onScroll, { passive: true });
  const burger = document.querySelector(".burger");
  const links = document.querySelector(".nav-links");
  if (burger && links) {
    burger.addEventListener("click", () => links.classList.toggle("open"));
    links.querySelectorAll("a").forEach(a => a.addEventListener("click", () => links.classList.remove("open")));
  }
}

/* ---------- skeleton loading helper ----------
   Menampilkan kartu skeleton sebentar sebelum render produk asli,
   supaya transisi terasa halus alih-alih konten muncul instan. */
function skeletonCards(count) {
  return Array.from({ length: count }).map(() => `
    <div class="card skel-card">
      <div class="skeleton skel-media"></div>
      <div class="skeleton skel-line" style="width:60%"></div>
      <div class="skeleton skel-line" style="width:40%"></div>
    </div>`).join("");
}
function withSkeleton(gridEl, count, renderFn, delay = 380) {
  gridEl.innerHTML = skeletonCards(count);
  setTimeout(renderFn, delay);
}

document.addEventListener("DOMContentLoaded", () => {
  document.body.classList.add("page-enter");
  renderCounts();
  initNavbar();
});
