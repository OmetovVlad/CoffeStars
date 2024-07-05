@extends('layouts.main')

@section('content')
  @auth
    <div id="comingSoon" class="pageWrapper">
      <div class="content">
        @if($error)
          <h2 class="text-warning">Какая-то проблема...</h2>
          <p>Обратитесь к администрации проекта или попробуйте позже.</p>
        @else
          <h2 class="text-success-emphasis">Заказ оформлен!</h2>
          <p>Когда товар прибудет в пункт выдачи, вы получите смс сообщение!</p>
        @endif

        <a href="/" class="button skew"><span>На главную</span></a>
      </div>
    </div>
  @endauth

  @guest
    @include('components.pages.isNotMobile', ['isHide' => false])
  @endguest
@endsection