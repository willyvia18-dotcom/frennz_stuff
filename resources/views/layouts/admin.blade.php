<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', __('ui.admin.title'))</title>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@yield('styles')
</head>
<body>

@yield('content')

<script>
  window.LOCALE = @json(app()->getLocale());
  window.I18N = @json(__('ui'));
</script>
<script src="{{ asset('js/data.js') }}"></script>
<script src="{{ asset('js/store.js') }}"></script>
@yield('scripts')
</body>
</html>
