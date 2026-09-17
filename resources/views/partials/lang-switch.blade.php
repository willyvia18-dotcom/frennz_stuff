<div class="lang-switch" style="display:flex;gap:6px;margin-right:8px;font-size:13px;">
  <a href="{{ route('lang.switch', 'id') }}" style="font-weight:{{ app()->getLocale()==='id' ? '700' : '400' }};">ID</a>
  <span>/</span>
  <a href="{{ route('lang.switch', 'en') }}" style="font-weight:{{ app()->getLocale()==='en' ? '700' : '400' }};">EN</a>
</div>
