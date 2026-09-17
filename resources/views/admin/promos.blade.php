@extends('layouts.admin')

@section('title', __('ui.admin.manage_promos') . ' — ' . __('ui.admin.title'))

@section('content')

<div class="admin-shell">
  @include('partials.admin-sidebar', ['active' => 'promos'])

  <main class="admin-main">
    <div class="admin-topbar"><h1>{{ __('ui.admin.manage_promos') }}</h1><button class="btn btn-primary btn-sm" id="add-btn">{{ __('ui.admin.add_voucher') }}</button></div>

    <div class="admin-panel">
      <div class="admin-panel__body" style="padding:0;overflow-x:auto;">
        <table class="table admin-table">
          <thead><tr><th>{{ __('ui.admin.th_code') }}</th><th>{{ __('ui.admin.th_type') }}</th><th>{{ __('ui.admin.th_value') }}</th><th>{{ __('ui.admin.th_desc') }}</th><th>{{ __('ui.admin.th_status') }}</th><th>{{ __('ui.admin.th_actions') }}</th></tr></thead>
          <tbody id="promo-rows"></tbody>
        </table>
      </div>
    </div>
  </main>
</div>

<div class="modal-overlay" id="modal-overlay">
  <div class="modal">
    <button class="modal-close" id="modal-close" aria-label="{{ __('ui.admin.close') }}">✕</button>
    <h3>{{ __('ui.admin.modal_add_voucher') }}</h3>
    <form id="promo-form">
      <div class="form-grid">
        <div class="field full"><label>{{ __('ui.admin.f_code') }}</label><input required name="code" placeholder="{{ __('ui.admin.f_code_ph') }}" style="text-transform:uppercase;"></div>
        <div class="field"><label>{{ __('ui.admin.f_type') }}</label><select name="type"><option value="percent">{{ __('ui.admin.type_percent') }}</option><option value="fixed">{{ __('ui.admin.type_fixed') }}</option></select></div>
        <div class="field"><label>{{ __('ui.admin.f_value') }}</label><input required type="number" name="value" min="0"></div>
        <div class="field full"><label>{{ __('ui.admin.f_voucher_desc') }}</label><input name="desc" placeholder="{{ __('ui.admin.f_voucher_desc_ph') }}"></div>
        <div class="field full"><label>{{ __('ui.admin.f_voucher_desc_en') }}</label><input name="descEn"></div>
      </div>
      <button type="submit" class="btn btn-primary btn-block">{{ __('ui.admin.save_voucher') }}</button>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
  let vouchers = @json($vouchers ?? []);

  function render() {
    document.getElementById("promo-rows").innerHTML = vouchers.map((p,i) => `
      <tr>
        <td style="font-weight:600;">${p.code}</td>
        <td>${p.type === "percent" ? t('admin.type_s_percent') : t('admin.type_s_fixed')}</td>
        <td>${p.type === "percent" ? p.value + "%" : formatRupiah(p.value)}</td>
        <td>${(p.desc || t('admin.dash'))}</td>
        <td><button class="toggle-pill ${p.active ? "on" : "off"}" data-toggle="${p.id}">${p.active ? t('admin.active') : t('admin.inactive')}</button></td>
        <td><button class="icon-action danger" data-delete="${p.id}">${t('admin.delete')}</button></td>
      </tr>`).join("") || `<tr><td colspan="6" class="text-muted center">${t('admin.no_vouchers')}</td></tr>`;

    document.querySelectorAll("[data-toggle]").forEach(b => b.onclick = () => {
      apiCart(`/admin/promos/${b.dataset.toggle}/toggle`, "PATCH").then(res => {
        if (res.ok) { vouchers = res.vouchers; render(); } else showToast(res.message || t('messages.voucher_add_fail'));
      });
    });
    document.querySelectorAll("[data-delete]").forEach(b => b.onclick = () => {
      if (!confirm(t('admin.confirm_delete_voucher'))) return;
      apiCart(`/admin/promos/${b.dataset.delete}`, "DELETE").then(res => {
        if (res.ok) { vouchers = res.vouchers; render(); showToast(t('messages.voucher_deleted')); }
        else showToast(res.message || t('messages.voucher_add_fail'));
      });
    });
  }
  const overlay = document.getElementById("modal-overlay");
  document.getElementById("add-btn").onclick = () => overlay.classList.add("open");
  document.getElementById("modal-close").onclick = () => overlay.classList.remove("open");
  overlay.addEventListener("click", e => { if (e.target === overlay) overlay.classList.remove("open"); });

  document.getElementById("promo-form").addEventListener("submit", e => {
    e.preventDefault();
    const fd = new FormData(e.target);
    apiCart("/admin/promos", "POST", {
      code: fd.get("code"),
      type: fd.get("type"),
      value: Number(fd.get("value")),
      desc: fd.get("desc"),
      descEn: fd.get("descEn"),
    }).then(res => {
      if (res.ok) {
        vouchers = res.vouchers;
        overlay.classList.remove("open");
        e.target.reset();
        render();
        showToast(t('messages.voucher_added'));
      } else {
        showToast(res.message || t('messages.voucher_add_fail'));
      }
    });
  });

  render();
</script>
@endsection