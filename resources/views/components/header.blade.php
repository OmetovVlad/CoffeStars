<div id="header">
  @include('components.logo')

  <div class="wallet" data-bs-toggle="modal" data-bs-target="#swapModal">
    <div id="balance" class="balance">
      <span>{{$balance}}</span>
      <b>FC</b>
    </div>
    <div class="gfc">
      <span>{{$balanceGfc}}</span>
      <b>GFC</b>
    </div>
{{--    <div class="icon icon-wallet"></div>--}}
  </div>
</div>
