@extends('layouts.admin')

@section('title', __('ui.admin.dashboard') . ' — ' . __('ui.admin.title'))

@section('content')

<div class="admin-shell">
  @include('partials.admin-sidebar', ['active' => 'dashboard'])

  <main class="admin-main">
    <div class="admin-topbar"><h1>{{ __('ui.admin.dashboard') }}</h1><span class="text-muted" style="font-size:12.5px" id="today"></span></div>

    <div class="stat-grid">
      <div class="stat-card"><div class="stat-card__label">{{ __('ui.admin.revenue') }}</div><div class="stat-card__value" id="stat-revenue" style="font-size:22px">—</div></div>
      <div class="stat-card"><div class="stat-card__label">{{ __('ui.admin.total_orders') }}</div><div class="stat-card__value" id="stat-orders">0</div></div>
      <div class="stat-card"><div class="stat-card__label">{{ __('ui.admin.total_products') }}</div><div class="stat-card__value" id="stat-products">0</div></div>
      <div class="stat-card"><div class="stat-card__label">{{ __('ui.admin.total_customers') }}</div><div class="stat-card__value" id="stat-customers">0</div></div>
    </div>

    <div class="admin-panel">
      <div class="admin-panel__head"><h3>{{ __('ui.admin.sales_per_category') }}</h3></div>
      <div class="admin-panel__body"><div class="bar-chart" id="bar-chart"></div></div>
    </div>

    <div class="admin-panel">
      <div class="admin-panel__head"><h3>{{ __('ui.admin.recent_orders') }}</h3><a href="{{ route('admin.orders') }}" class="text-muted" style="font-size:12px;">{{ __('ui.admin.view_all') }}</a></div>
      <div class="admin-panel__body" style="padding:0;overflow-x:auto;">
        <table class="table admin-table">
          <thead><tr><th>{{ __('ui.admin.th_id') }}</th><th>{{ __('ui.admin.th_customer') }}</th><th>{{ __('ui.admin.th_date') }}</th><th>{{ __('ui.admin.th_total') }}</th><th>{{ __('ui.admin.th_status') }}</th></tr></thead>
          <tbody id="recent-orders"></tbody>
        </table>
      </div>
    </div>
  </main>
</div>

@endsection

@section('scripts')
<script>
  document.getElementById("today").textContent = new Date().toLocaleDateString(lsTag(),{weekday:"long",day:"numeric",month:"long",year:"numeric"});
const revenue = {{ $revenue }};
const orderCount = {{ $orderCount }};
const productCount = {{ $productCount }};
const customerCount = {{ $customerCount }};
const categorySales = @json($categorySales);
const recentOrders = @json($recentOrders);

document.getElementById("stat-revenue").textContent = formatRupiah(revenue);
document.getElementById("stat-orders").textContent = orderCount;
document.getElementById("stat-products").textContent = productCount;
document.getElementById("stat-customers").textContent = customerCount;

const maxVal = Math.max(...categorySales.map(c => c.value), 1);
document.getElementById("bar-chart").innerHTML = categorySales.map(c => `<div class="bar" style="height:${Math.max(4,(c.value/maxVal)*130)}px"><span>${catLabelByName(c.name)}</span></div>`).join("");

const statusClass = { pending:"processing", processing:"processing", shipped:"shipped", completed:"done", cancelled:"cancelled" };
const statusLabel = { pending:t('admin.status_pending'), processing:t('admin.status_processing'), shipped:t('admin.status_shipped'), completed:t('admin.status_completed'), cancelled:t('admin.status_cancelled') };
document.getElementById("recent-orders").innerHTML = recentOrders.map(o => `
  <tr><td>#${o.id}</td><td>${o.buyer_name}</td><td>${o.created_at}</td><td>${formatRupiah(o.total)}</td><td><span class="status-pill ${statusClass[o.status]||"processing"}">${statusLabel[o.status]||o.status}</span></td></tr>
`).join("") || `<tr><td colspan="5" class="text-muted center">${t('admin.no_orders')}</td></tr>`;
</script>
@endsection
