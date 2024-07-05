<div id="app" @class([
    'pageWrapper',
    'hide' => $isHide,
])>

{{--  <button class="partners_btn" data-bs-toggle="modal" data-bs-target="#boostModal"><span>Boosts</span></button>--}}
{{--  <button class="partners_btn" data-bs-toggle="modal" data-bs-target="#partnersModal"><span>FREE FC</span></button>--}}

  @if($autobot)
    <div class="autobot hide">
      <img src="{{Vite::asset('resources/img/autobot.svg')}}" alt="Автобот">
      <img class="autobot_eyes" src="{{Vite::asset('resources/img/autobot_eyes.svg')}}" alt="Автобот">
    </div>

    <div class="autobot_panel">
      <span>Автобот</span>
      <div class="mining_info">
        <span>Добыто сегодня</span>
        <div><span id="autobot_sum">{{$autobotTodaySum}}</span> <span>/30000</span> <b>FC</b></div>
      </div>
      @if($autobotTodaySum < 30000)
        <div class="switch_bot">
          <div class="tumbler"></div>
        </div>
      @endif
    </div>
  @else
    <button class="autobot_btn" data-bs-toggle="modal" data-bs-target="#avtobotModal"><span>Автобот</span></button>
  @endif

  <button class="startMining" data-production="{{$production}}">
    <img src="{{$coin}}" alt="FingerMining">
    <span>держи и не отпускай</span>
  </button>
  <div id="bg"></div>
  <div id="bg-active"></div>
</div>