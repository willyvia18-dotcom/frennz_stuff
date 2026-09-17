<aside class="admin-sidebar{{ ($printHidden ?? false) ? ' no-print' : '' }}">
  <div class="admin-sidebar__logo">Frennz.Stuff</div>
  <nav class="admin-nav">
    <a href="{{ route('admin.dashboard') }}" @if(($active ?? '') === 'dashboard') aria-current="page" @endif>{{ __('ui.admin.dashboard') }}</a>
    <a href="{{ route('admin.products') }}" @if(($active ?? '') === 'products') aria-current="page" @endif>{{ __('ui.admin.products') }}</a>
    <a href="{{ route('admin.categories') }}" @if(($active ?? '') === 'categories') aria-current="page" @endif>{{ __('ui.admin.categories') }}</a>
    <a href="{{ route('admin.orders') }}" @if(($active ?? '') === 'orders') aria-current="page" @endif>{{ __('ui.admin.orders') }}</a>
    <a href="{{ route('admin.customers') }}" @if(($active ?? '') === 'customers') aria-current="page" @endif>{{ __('ui.admin.customers') }}</a>
    <a href="{{ route('admin.promos') }}" @if(($active ?? '') === 'promos') aria-current="page" @endif>{{ __('ui.admin.promos') }}</a>
    <a href="{{ route('admin.reports') }}" @if(($active ?? '') === 'reports') aria-current="page" @endif>{{ __('ui.admin.reports') }}</a>
    <div class="divider">{{ __('ui.admin.others') }}</div>
    <a href="{{ route('home') }}">{{ __('ui.admin.back') }}</a>
  </nav>
  <div class="no-print" style="padding:16px;">@include('partials.lang-switch')</div>
</aside>
