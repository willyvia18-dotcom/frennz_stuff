@extends('layouts.admin')

@section('title', __('ui.admin.sales_report') . ' — ' . __('ui.admin.title'))

@section('content')

<div class="admin-shell">
  @include('partials.admin-sidebar', ['active' => 'reports'])

  <main class="admin-main">
    <div class="admin-topbar"><h1>{{ __('ui.admin.sales_report') }}</h1></div>

    <div class="stat-grid">
      <div class="stat-card"><div class="stat-card__label">{{ __('ui.admin.total_revenue') }}</div><div class="stat-card__value" id="stat-revenue" style="font-size:20px">—</div></div>
      <div class="stat-card"><div class="stat-card__label">{{ __('ui.admin.avg_per_order') }}</div><div class="stat-card__value" id="stat-avg" style="font-size:20px">—</div></div>
      <div class="stat-card"><div class="stat-card__label">{{ __('ui.admin.orders_done') }}</div><div class="stat-card__value" id="stat-done">0</div></div>
      <div class="stat-card"><div class="stat-card__label">{{ __('ui.admin.orders_cancelled') }}</div><div class="stat-card__value" id="stat-cancel">0</div></div>
    </div>

    <div class="admin-panel">
      <div class="admin-panel__head"><h3>{{ __('ui.admin.monthly_revenue') }}</h3></div>
      <div class="admin-panel__body"><div class="bar-chart" id="month-chart"></div></div>
    </div>

    <div class="admin-panel">
      <div class="admin-panel__head"><h3>{{ __('ui.admin.top_products') }}</h3></div>
      <div class="admin-panel__body" style="padding:0;overflow-x:auto;">
        <table class="table admin-table">
          <thead><tr><th>{{ __('ui.admin.th_product') }}</th><th>{{ __('ui.admin.th_category') }}</th><th>{{ __('ui.admin.th_sold') }}</th><th>{{ __('ui.admin.th_revenue') }}</th></tr></thead>
          <tbody id="top-products"></tbody>
        </table>
      </div>
    </div>
  </main>
</div>

@endsection

@section('scripts')
<script>
  const revenue = {{ $revenue }};
  const ordersCount = {{ $ordersCount }};
  const done = {{ $done }};
  const cancelled = {{ $cancelled }};
  const monthTotals = @json($monthTotals);
  const topProducts = @json($topProducts);

  document.getElementById("stat-revenue").textContent = formatRupiah(revenue);
  document.getElementById("stat-avg").textContent = formatRupiah(ordersCount ? Math.round(revenue/ordersCount) : 0);
  document.getElementById("stat-done").textContent = done;
  document.getElementById("stat-cancel").textContent = cancelled;

  const monthNames = window.I18N.months;
  const maxMonth = Math.max(...monthTotals, 1);
  document.getElementById("month-chart").innerHTML = monthNames.map((m,i) => `<div class="bar" style="height:${Math.max(4,((monthTotals[i]||0)/maxMonth)*130)}px"><span>${m}</span></div>`).join("");

  document.getElementById("top-products").innerHTML = topProducts.map(p => `
    <tr><td style="font-weight:600;">${p.name}</td><td>${catLabelByName(p.category)}</td><td>${p.qty}</td><td>${formatRupiah(p.revenue)}</td></tr>
  `).join("") || `<tr><td colspan="4" class="text-muted center">${t('admin.no_sales_data')}</td></tr>`;
</script>
@endsection