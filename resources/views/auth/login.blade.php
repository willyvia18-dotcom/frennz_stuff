@extends('layouts.app')

@section('title', __('ui.auth.login_title'))

@section('styles')
<style>.auth-card{max-width:420px;margin:64px auto;border:1px solid var(--line);border-radius:var(--r-lg);padding:36px;}</style>
@endsection

@section('content')

<nav class="navbar">
  <div class="container navbar__row">
    <a href="{{ route('home') }}" class="wordmark">Frennz.Stuff</a>
    <div class="nav-links"><a href="{{ route('products.index') }}">{{ __('ui.nav.shop') }}</a></div>
    <div class="nav-actions">
      @include('partials.lang-switch')
      <a class="nav-icon-btn" href="{{ route('cart.index') }}" aria-label="{{ __('ui.nav.cart') }}">
        <svg class="icon" viewBox="0 0 24 24"><path d="M6 8h12l-1 12H7L6 8z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg>
        <span class="nav-count" data-cart-count style="display:none">0</span>
      </a>
    </div>
  </div>
</nav>

<main class="container">
  <div class="auth-card">
    @if (session('success'))
      <p class="text-muted" style="color:green;margin-bottom:12px;">{{ session('success') }}</p>
    @endif

    <h2>{{ __('ui.auth.login_heading') }}</h2>
    <p class="text-muted" style="margin-top:8px;font-size:14px;">{{ __('ui.auth.login_sub') }}</p>

    <form method="POST" action="{{ route('login.attempt') }}" style="margin-top:20px;">
      @csrf
      <div class="field">
        <label>{{ __('ui.auth.email') }}</label>
        <input required type="email" name="email" value="{{ old('email') }}">
        @error('email') <small style="color:red;">{{ $message }}</small> @enderror
      </div>
      <div class="field">
        <label>{{ __('ui.auth.password') }}</label>
        <input required type="password" name="password">
      </div>
      <button type="submit" class="btn btn-primary btn-block">{{ __('ui.auth.login_btn') }}</button>
    </form>

    <p class="text-muted" style="margin-top:16px;font-size:13.5px;text-align:center;">
      {{ __('ui.auth.no_account') }} <a href="{{ route('register') }}" style="text-decoration:underline;color:var(--ink);">{{ __('ui.auth.register_link') }}</a>
    </p>
  </div>
</main>

@endsection
