@extends('layouts.admin')

@section('title', __('ui.admin.manage_orders') . ' — ' . __('ui.admin.title'))

@section('styles')
<style>@media print{.admin-sidebar,.admin-topbar,.admin-panel__head,.no-print{display:none !important;}.admin-main{padding:0;}}</style>
@endsection

@section('content')

<div class="admin-shell">
  @include('partials.admin-sidebar', ['active' => 'orders', 'printHidden' => true])

  <main class="admin-main">
    <div class="admin-topbar"><h1>{{ __('ui.admin.manage_orders') }}</h1><span class="text-muted" style="font-size:12.5px" id="count-label"></span></div>
    <div class="admin-panel">
      <div class="admin-panel__body" style="padding:0;overflow-x:auto;">
        <table class="table admin-table">
          <thead><tr><th>{{ __('ui.admin.th_id') }}</th><th>{{ __('ui.admin.th_customer') }}</th><th>{{ __('ui.admin.th_date') }}</th><th>{{ __('ui.admin.th_item') }}</th><th>{{ __('ui.admin.th_total') }}</th><th>{{ __('ui.admin.th_status') }}</th><th class="no-print">{{ __('ui.admin.th_actions') }}</th></tr></thead>
          <tbody id="order-rows"></tbody>
        </table>
      </div>
    </div>
  </main>
</div>

<div class="modal-overlay" id="modal-overlay"><div class="modal" id="invoice-modal"></div></div>

@endsection

@section('scripts')
<script>
  window.SERVER_ORDERS = @json($orders ?? []);
  window.ORDER_STATUS_ROUTE = "{{ url('/admin/orders') }}";
  const statusOptions = ["pending","processing","shipped","completed","cancelled"];
  const statusClass = { pending:"processing", processing:"processing", shipped:"shipped", completed:"done", cancelled:"cancelled" };
  const statusLabel = { pending:t('admin.status_pending'), processing:t('admin.status_processing'), shipped:t('admin.status_shipped'), completed:t('admin.status_completed'), cancelled:t('admin.status_cancelled') };

  function render() {
    const orders = window.SERVER_ORDERS || [];
    document.getElementById("count-label").textContent = t('admin.orders_n', { n: orders.length });
    document.getElementById("order-rows").innerHTML = orders.map((o,i) => `
      <tr>
        <td>#${o.id}</td><td>${o.buyer.name}</td><td>${new Date(o.date).toLocaleDateString(lsTag())}</td>
        <td>${t('admin.pcs_n', { n: o.items.reduce((s,it)=>s+it.qty,0) })}</td><td>${formatRupiah(o.total)}</td>
        <td><select class="select-status no-print" data-status="${i}">${statusOptions.map(s=>`<option value="${s}" ${s===o.status?"selected":""}>${statusLabel[s]||s}</option>`).join("")}</select></td>
        <td class="no-print"><button class="icon-action" data-invoice="${i}">${t('admin.invoice')}</button></td>
      </tr>`).join("") || `<tr><td colspan="7" class="text-muted center">${t('admin.no_orders_in')}</td></tr>`;

    document.querySelectorAll("[data-status]").forEach(sel => sel.onchange = () => {
      const order = window.SERVER_ORDERS[+sel.dataset.status];
      apiCart(`${window.ORDER_STATUS_ROUTE}/${order.id}/status`, "PATCH", { status: sel.value }).then(res => {
        if (res.ok) { order.status = sel.value; render(); showToast(t('admin.status_updated')); }
        else { showToast(res.message || t('messages.order_fail')); render(); }
      });
    });
    document.querySelectorAll("[data-invoice]").forEach(b => b.onclick = () => showInvoice(window.SERVER_ORDERS[+b.dataset.invoice]));
  }

  function showInvoice(o) {
    document.getElementById("invoice-modal").innerHTML = `
      <h3>${t('admin.invoice')} #${o.id}</h3>
      <p class="text-muted" style="font-size:12px">${new Date(o.date).toLocaleDateString(lsTag(),{day:"numeric",month:"long",year:"numeric"})}</p>
      <div style="margin:14px 0;font-size:14px;"><strong>${o.buyer.name}</strong><br>${o.buyer.phone} · ${o.buyer.email}<br>${o.address.line}, ${o.address.city} ${o.address.postal}</div>
      <table class="table" style="margin-bottom:14px;">
        <thead><tr><th>${t('admin.th_item')}</th><th>${t('admin.th_qty')}</th><th>${t('cart.subtotal')}</th></tr></thead>
        <tbody>${o.items.map(it => `<tr><td>${it.name} (${it.size}, ${colorName(it.color)})</td><td>${it.qty}</td><td>${formatRupiah(it.price*it.qty)}</td></tr>`).join("")}</tbody>
      </table>
      <div class="summary-row"><span>${t('admin.shipping')}</span><span>${formatRupiah(o.shipping)}</span></div>
      ${o.discount ? `<div class="summary-row"><span>${t('admin.voucher')} ${o.voucher||""}</span><span>-${formatRupiah(o.discount)}</span></div>` : ""}
      <div class="summary-row total"><span>${t('admin.th_total')}</span><span>${formatRupiah(o.total)}</span></div>
      <div class="no-print" style="display:flex;gap:10px;margin-top:18px;">
        <button class="btn btn-ghost" id="close-invoice">${t('admin.close')}</button>
        <button class="btn btn-primary" onclick="window.print()">${t('admin.invoice_print')}</button>
      </div>`;
    document.getElementById("modal-overlay").classList.add("open");
    document.getElementById("close-invoice").onclick = () => document.getElementById("modal-overlay").classList.remove("open");
  }
  document.getElementById("modal-overlay").addEventListener("click", e => { if (e.target.id === "modal-overlay") e.target.classList.remove("open"); });

  render();
</script>
@endsection