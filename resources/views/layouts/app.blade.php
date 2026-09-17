<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', __('ui.home.title'))</title>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
@yield('styles')
</head>
<body>

@yield('content')

<script>
  window.LOCALE = @json(app()->getLocale());
  window.I18N = @json(__('ui'));
  window.SERVER_PRODUCTS = @json($serverProducts ?? []);
  window.SERVER_CATEGORIES = @json($serverCategories ?? []);
  window.IS_AUTHENTICATED = @json($isAuthenticated ?? false);
  window.SERVER_CART_ITEMS = @json($serverCartItems ?? []);
  window.SERVER_WISHLIST = @json($serverWishlist ?? []);
  window.SERVER_ADDRESSES = @json($serverAddresses ?? []);
  window.BUYER_PROFILE = @json($buyerProfile ?? null);
  window.ADDRESS_ROUTES = {
    index: "{{ route('addresses.index') }}",
    store: "{{ route('addresses.store') }}",
    base: "{{ url('/addresses') }}"
  };
</script>
<script src="{{ asset('js/data.js') }}"></script>
<script src="{{ asset('js/store.js') }}"></script>
@yield('scripts')
</body>
</html>
