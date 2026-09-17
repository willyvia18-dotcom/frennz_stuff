@extends('layouts.admin')

@section('title', __('ui.admin.manage_products') . ' — ' . __('ui.admin.title'))

@section('content')

<div class="admin-shell">
  @include('partials.admin-sidebar', ['active' => 'products'])

  <main class="admin-main">
    <div class="admin-topbar"><h1>{{ __('ui.admin.manage_products') }}</h1><button class="btn btn-primary btn-sm" id="add-btn">{{ __('ui.admin.add_product') }}</button></div>

    <div class="admin-panel">
      <div class="admin-panel__head">
        <h3>{{ __('ui.admin.all_products') }} (<span id="count">0</span>)</h3>
        <input type="text" id="search" placeholder="{{ __('ui.admin.search_products') }}" style="border:1.5px solid var(--line);border-radius:var(--r-sm);padding:8px 12px;font-family:inherit;font-size:13px;">
      </div>
      <div class="admin-panel__body" style="padding:0;overflow-x:auto;">
        <table class="table admin-table">
          <thead><tr><th>{{ __('ui.admin.th_product') }}</th><th>{{ __('ui.admin.th_category') }}</th><th>{{ __('ui.admin.th_price') }}</th><th>{{ __('ui.admin.th_discount') }}</th><th>{{ __('ui.admin.th_stock') }}</th><th>{{ __('ui.admin.th_actions') }}</th></tr></thead>
          <tbody id="product-rows"></tbody>
        </table>
      </div>
    </div>
  </main>
</div>

<div class="modal-overlay" id="modal-overlay">
  <div class="modal">
    <button class="modal-close" id="modal-close" aria-label="{{ __('ui.admin.close') }}">✕</button>
    <h3 id="modal-title">{{ __('ui.admin.modal_add_product') }}</h3>
    <form id="product-form">
      <input type="hidden" name="id">
      <div class="form-grid">
        <div class="field full"><label>{{ __('ui.admin.f_name') }}</label><input required name="name"></div>
        <div class="field"><label>{{ __('ui.admin.th_category') }}</label><select name="category" required></select></div>
        <div class="field"><label>{{ __('ui.admin.f_rating') }}</label><input type="number" name="rating" min="1" max="5" step="0.1" value="4.5"></div>
        <div class="field"><label>{{ __('ui.admin.f_price') }}</label><input required type="number" name="price" min="0"></div>
        <div class="field"><label>{{ __('ui.admin.f_sale_price') }}</label><input type="number" name="salePrice" min="0"></div>
        <div class="field full"><label>{{ __('ui.admin.f_sizes') }}</label><input required name="sizes" placeholder="S,M,L,XL"></div>
        <div class="field full"><label>{{ __('ui.admin.f_stock') }}</label><input required name="stockList" placeholder="5,10,8,3"></div>
        <div class="field full"><label>{{ __('ui.admin.f_colors') }}</label><input required name="colors" placeholder="Hitam:#111110,Putih:#ffffff"></div>
        <div class="field full"><label>{{ __('ui.admin.f_images') }}</label><input required name="images" placeholder="https://..."></div>
        <div class="field full"><label>{{ __('ui.admin.f_desc') }}</label><textarea name="desc" rows="3"></textarea></div>
        <div class="field full"><label>{{ __('ui.admin.f_desc_en') }}</label><textarea name="descEn" rows="3"></textarea></div>
        <div class="field"><label>{{ __('ui.admin.f_tag') }}</label><select name="tag"><option value="">{{ __('ui.admin.tag_none') }}</option><option value="NEW">New</option><option value="SALE">Sale</option></select></div>
        <div class="field"><label>{{ __('ui.admin.f_bestseller') }}</label><select name="bestseller"><option value="">{{ __('ui.admin.no') }}</option><option value="true">{{ __('ui.admin.yes') }}</option></select></div>
      </div>
      <button type="submit" class="btn btn-primary btn-block">{{ __('ui.admin.save_product') }}</button>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
  window.SERVER_PRODUCTS = @json($products ?? []);
  window.SERVER_CATEGORIES = @json($categories ?? []);
  let search = "";
  const catSelect = document.querySelector('select[name="category"]');
  function refreshCatOptions() { catSelect.innerHTML = getCategories().map(c => `<option value="${c.name}">${catLabelByName(c.name)}</option>`).join(""); }
  refreshCatOptions();

  function renderRows() {
    const products = getProducts().filter(p => p.name.toLowerCase().includes(search.toLowerCase()));
    document.getElementById("count").textContent = getProducts().length;
    document.getElementById("product-rows").innerHTML = products.map(p => {
      const totalStock = Object.values(p.stock).reduce((a,b)=>a+b,0);
      return `<tr>
        <td><div class="line-item"><img src="${p.images[0]}" alt=""><span style="font-weight:600">${p.name}</span></div></td>
        <td>${catLabelByName(p.category)}</td>
        <td>${formatRupiah(p.price)}</td>
        <td>${p.salePrice ? formatRupiah(p.salePrice) : t('admin.dash')}</td>
        <td>${totalStock === 0 ? t('admin.out_of_stock') : totalStock}</td>
        <td><button class="icon-action" data-edit="${p.id}">${t('admin.edit')}</button><button class="icon-action danger" data-delete="${p.id}">${t('admin.delete')}</button></td>
      </tr>`;
    }).join("") || `<tr><td colspan="6" class="text-muted center">${t('admin.no_products')}</td></tr>`;

    document.querySelectorAll("[data-edit]").forEach(b => b.onclick = () => openModal(getProductById(b.dataset.edit)));
    document.querySelectorAll("[data-delete]").forEach(b => b.onclick = () => {
  if (!confirm(t('admin.confirm_delete_product'))) return;
  apiCart(`/admin/products/${b.dataset.delete}`, "DELETE").then(res => {
    if (res.ok) { window.SERVER_PRODUCTS = window.SERVER_PRODUCTS.filter(p => String(p.id) !== String(b.dataset.delete)); renderRows(); showToast(t('messages.product_deleted')); }
    else showToast(res.message || t('messages.product_delete_fail'));
  });
});
  }
  document.getElementById("search").addEventListener("input", e => { search = e.target.value; renderRows(); });

  const overlay = document.getElementById("modal-overlay");
  const form = document.getElementById("product-form");
  function openModal(product) {
    refreshCatOptions();
    document.getElementById("modal-title").textContent = product ? t('admin.modal_edit_product') : t('admin.modal_add_product');
    form.reset();
    form.id.value = product ? product.id : "";
    if (product) {
      form.name.value = product.name; form.category.value = product.category; form.rating.value = product.rating;
      form.price.value = product.price; form.salePrice.value = product.salePrice || "";
      form.sizes.value = product.sizes.join(",");
      form.stockList.value = product.sizes.map(s => product.stock[s] ?? 0).join(",");
      form.colors.value = product.colors.map(c => `${c.name}:${c.hex}`).join(",");
      form.images.value = product.images.join(",");
      form.desc.value = product.desc; form.descEn.value = product.descEn || ""; form.tag.value = product.tag || "";
      form.bestseller.value = product.bestseller ? "true" : "";
    }
    overlay.classList.add("open");
  }
  document.getElementById("add-btn").onclick = () => openModal(null);
  document.getElementById("modal-close").onclick = () => overlay.classList.remove("open");
  overlay.addEventListener("click", e => { if (e.target === overlay) overlay.classList.remove("open"); });

form.addEventListener("submit", e => {
  e.preventDefault();
  const fd = new FormData(form);
  const id = fd.get("id");
  const payload = {
    name: fd.get("name"),
    category: fd.get("category"),
    rating: fd.get("rating"),
    price: fd.get("price"),
    sale_price: fd.get("salePrice") || null,
    sizes: fd.get("sizes"),
    stock_list: fd.get("stockList"),
    colors: fd.get("colors"),
    images: fd.get("images"),
    desc: fd.get("desc"),
    desc_en: fd.get("descEn"),
    tag: fd.get("tag"),
    bestseller: fd.get("bestseller") === "true",
  };
  const url = id ? `/admin/products/${id}` : `/admin/products`;
  const method = id ? "PUT" : "POST";
  apiCart(url, method, payload).then(res => {
    if (res.ok) {
      window.SERVER_PRODUCTS = res.products;
      overlay.classList.remove("open");
      renderRows();
      showToast(t('messages.product_saved'));
    } else {
      showToast(res.message || t('messages.product_save_fail'));
    }
  });
});

  renderRows();
</script>
@endsection
