@extends('layouts.admin')

@section('title', __('ui.admin.manage_customers') . ' — ' . __('ui.admin.title'))

@section('content')

<div class="admin-shell">
  @include('partials.admin-sidebar', ['active' => 'customers'])

  <main class="admin-main">
    <div class="admin-topbar"><h1>{{ __('ui.admin.manage_customers') }}</h1><span class="text-muted" style="font-size:12.5px" id="count-label"></span></div>
    <div class="admin-panel">
      <div class="admin-panel__body" style="padding:0;overflow-x:auto;">
        <table class="table admin-table">
          <thead><tr><th>{{ __('ui.admin.th_name') }}</th><th>{{ __('ui.auth.email') }}</th><th>{{ __('ui.admin.th_phone') }}</th><th>{{ __('ui.admin.th_orders') }}</th><th>{{ __('ui.admin.th_spend') }}</th></tr></thead>
          <tbody id="customer-rows"></tbody>
        </table>
      </div>
    </div>
    <p class="text-muted" style="font-size:12.5px;">{{ __('ui.admin.customers_note') }}</p>
  </main>
</div>

@endsection

@section('scripts')
<script>
  const customers = @json($customers ?? []);
  document.getElementById("count-label").textContent = t('admin.customers_n', { n: customers.length });
  document.getElementById("customer-rows").innerHTML = customers.map(c => `
    <tr><td style="font-weight:600;">${c.name}</td><td>${c.email}</td><td>${c.phone}</td><td>${c.orders}</td><td>${formatRupiah(c.spend)}</td></tr>
  `).join("") || `<tr><td colspan="5" class="text-muted center">${t('admin.no_customers')}</td></tr>`;
</script>
@endsection