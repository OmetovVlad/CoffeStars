<div id="isNotMobile" @class([
    'hide' => $isHide,
])>
  <div class="content">
    @include('components.logo')
    <h2>Сканируй QR и начни зарабатывать</h2>
    <div class="qr">
      <img src="{{ Vite::asset('resources/img/qr-code.svg') }}">
    </div>
    <p>Приложение доступно только на мобильных устройствах</p>
  </div>
</div>