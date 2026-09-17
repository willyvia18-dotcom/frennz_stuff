@extends('layouts.admin')

@section('title', __('ui.admin.manage_categories') . ' — ' . __('ui.admin.title'))

@section('content')

<div class="admin-shell">
  @include('partials.admin-sidebar', ['active' => 'categories'])

  <main class="admin-main">
    <div class="admin-topbar"><h1>{{ __('ui.admin.manage_categories') }}</h1></div>

    <div class="admin-panel">
      <div class="admin-panel__head"><h3>{{ __('ui.admin.add_category_heading') }}</h3></div>
      <div class="admin-panel__body">
        <form id="cat-form" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
          <div class="field" style="margin-bottom:0;flex:1;min-width:160px;"><label>{{ __('ui.admin.f_category_name') }}</label><input required name="name" placeholder="{{ __('ui.admin.f_category_name_ph') }}"></div>
          <div class="field" style="margin-bottom:0;flex:1;min-width:160px;"><label>{{ __('ui.admin.f_category_name_en') }}</label><input name="name_en" placeholder="e.g. Jackets"></div>
          <div class="field" style="margin-bottom:0;width:120px;"><label>{{ __('ui.admin.f_icon') }}</label><input name="icon" placeholder="🧥" maxlength="2"></div>
          <button type="submit" class="btn btn-primary">{{ __('ui.admin.add') }}</button>
        </form>
      </div>
    </div>

    <div class="admin-panel">
      <div class="admin-panel__head"><h3>{{ __('ui.admin.all_categories') }} (<span id="count">0</span>)</h3></div>
      <div class="admin-panel__body" style="padding:0;overflow-x:auto;">
        <table class="table admin-table">
          <thead><tr><th>{{ __('ui.admin.th_icon') }}</th><th>{{ __('ui.admin.th_name') }}</th><th>{{ __('ui.admin.th_products_count') }}</th><th>{{ __('ui.admin.th_actions') }}</th></tr></thead>
          <tbody id="cat-rows"></tbody>
        </table>
      </div>
    </div>
  </main>
</div>

@endsection

@section('scripts')
<script>
  let categories = @json($categories);

  function render() {
    document.getElementById("count").textContent = categories.length;
    document.getElementById("cat-rows").innerHTML = categories.map(c => `
      <tr>
        <td style="font-size:20px;">${c.icon || "📦"}</td>
        <td style="font-weight:600;">${catLabel(c)}</td>
        <td>${t('admin.products_n', { n: c.productCount })}</td>
        <td><button class="icon-action danger" data-delete="${c.id}">${t('admin.delete')}</button></td>
      </tr>`).join("") || `<tr><td colspan="4" class="text-muted center">${t('admin.no_categories')}</td></tr>`;

    document.querySelectorAll("[data-delete]").forEach(b => b.onclick = () => {
      if (!confirm(t('admin.confirm_delete_category'))) return;
      apiCart(`/admin/categories/${b.dataset.delete}`, "DELETE").then(res => {
        if (res.ok) { categories = res.categories; render(); showToast(t('messages.cat_deleted')); }
        else showToast(res.message || t('messages.cat_delete_fail'));
      });
    });
  }

  document.getElementById("cat-form").addEventListener("submit", e => {
    e.preventDefault();
    const fd = new FormData(e.target);
    apiCart("/admin/categories", "POST", { name: fd.get("name"), name_en: fd.get("name_en"), icon: fd.get("icon") }).then(res => {
      if (res.ok) {
        categories = res.categories;
        e.target.reset();
        render();
        showToast(t('messages.cat_added'));
      } else {
        showToast(res.message || t('messages.cat_add_fail'));
      }
    });
  });

  render();
</script>
@endsection
