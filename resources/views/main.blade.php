@extends('layouts.main')

@section('content')
  @auth
    @include('components.pages.app', ['isHide' => false])
    @include('components.pages.profile', ['isHide' => true])
    @include('components.pages.withdraw', ['isHide' => true])
    @include('components.pages.shop', ['isHide' => true])
    @include('components.pages.friends', ['isHide' => true])
    @include('components.pages.comingSoon', ['isHide' => true])
    @include('components.pages.isNotMobile', ['isHide' => true])
  @endauth

  @guest
    @include('components.pages.isNotMobile', ['isHide' => false])
  @endguest
@endsection

@section('modals')
  @auth
    @include('components.notification')

    <div class="modal fade" id="swapModal">
      <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
          <div class="modal-body">
            <h2 class="mt-3 text-secondary-emphasis">SWAP</h2>

            <div class="loader-wrapper">
              <div class="loader"></div>
            </div>

            <div id="swap-not">
              <span>SWAP доступен от 100 000 FC</span>
            </div>

            <div id="swap-form" class="form hide">

              <div id="swap-fc-all">
                Перевести все <span id="swap-fc-balance"> FC</span>
              </div>

              <p class="input-description">Вы отправляете</p>
              <div class="input input--currency">
                <input id="swap-fc" name="sum" type="number" placeholder="от 100000">
                <span>FC</span>
              </div>

              <p class="input-description">Комиссия ~5%</p>
              <div class="input input--currency">
                <input id="swap-commission" name="sum" type="number" placeholder="GFC" disabled>
                <span>GFC</span>
              </div>

              <p class="input-description">Вы получаете</p>
              <div class="input input--currency">
                <input id="swap-gfc" name="sum" type="number" placeholder="GFC" disabled>
                <span>GFC</span>
              </div>

              <button id="swap-btn" class="skew" disabled><span>Обменять</span></button>

            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="skew button-border text-secondary-emphasis" data-bs-dismiss="modal"><span>Закрыть</span></button>
          </div>

        </div>
      </div>
    </div>

    <div class="modal fade" id="boostModal">
      <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
          <div class="modal-body">
            {{--            <h5 class="mt-3 text-secondary-emphasis">Магазин ускорений</h5>--}}
            <h2 class="mt-3">Магазин ускорений</h2>

            <div class="boosts_list">

              <div class="boost">
                <div class="boost_data">
                  <div class="image">
                    <img src="{{ Vite::asset('resources/img/coin_0.png') }}">
                  </div>
                  <div class="text">
                    <h3>Стандарт</h3>
                    <span>Добыча: 1 FC</span>
                  </div>
                </div>
              </div>

              @for ($i = 0; $i < count($boostsList); $i++)
                <div class="boost" data-boostid="{{$boostsList[$i]['id']}}">
                  <div class="boost_data">
                    <div class="image">
                      <img src="{{$boostsList[$i]['image']}}">
                    </div>
                    <div class="text">
                      <h3>{{$boostsList[$i]['name']}}</h3>
                      <span>Добыча: {{$boostsList[$i]['production']}} FC</span>
                    </div>
                    <a href="/api/pay/create?user_id={{Auth::user()->id}}&boost_id={{$i}}" class="button bg-success">
                      <span><b>{{number_format($boostsList[$i]['price'], 0, '', ' ')}}</b> RUB</span>
                    </a>
                  </div>
                </div>
              @endfor

            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="skew button-border text-secondary-emphasis" data-bs-dismiss="modal"><span>Закрыть</span></button>
          </div>

        </div>
      </div>
    </div>

    <div class="modal fade" id="partnersModal">
      <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
          <div class="modal-body">
            <h2 class="mt-3">Предложения от партнеров</h2>
            <span class="subtitle">* Целевое действие (ЦД) - платежная операция по пластиковой карте от 1000 рублей. Не засчитывается оплата мобильной связи/банковские переводы.</span>

            <div class="partners_list">
              @for ($i = 0; $i < count($partnersList); $i++)
                <div onclick="window.Telegram.WebApp.openLink('{{$partnersList[$i]['link']}}&aff_sub1={{Auth::user()->id}}')" class="partner {{$partnersList[$i]['class']}}" data-partner-id="{{$partnersList[$i]['id']}}">
                  <div class="partner_banner">
                    <div class="text">
                      <span>За оформление заявки</span>
                      <h3>+{{number_format($partnersList[$i]['reward1'], 0, '', ' ')}} FC</h3>
                    </div>
                    <div class="reward">
                      <span>За выполнение ЦД</span>
                      <b>+{{number_format($partnersList[$i]['reward2'], 0, '', ' ')}} FC</b>
                    </div>
                  </div>
                  <div class="partner_data">
                    <div class="logo">
                      {!! $partnersList[$i]['logo'] !!}
                    </div>
                    <div class="text">
                      <h3>{{$partnersList[$i]['name']}}</h3>
                      <span>{{$partnersList[$i]['desc']}}</span>
                    </div>
                  </div>
                </div>
              @endfor
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="skew button-border text-secondary-emphasis" data-bs-dismiss="modal"><span>Закрыть</span></button>
          </div>

        </div>
      </div>
    </div>

    <div class="modal fade" id="avtobotModal">
      <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
          <div class="modal-body">
            <div class="photo">
              <img src="{{Vite::asset('resources/img/autobot.svg')}}" alt="Автобот">
            </div>
            <h2 class="mt-3">Автобот</h2>
            <span class="subtitle">Добывает самостоятельно до 30&nbsp;000&nbsp;FC в день!</span>
          </div>
          <div class="modal-footer">
            @foreach($autobotList as $autobot)
              <a href="/api/pay/create?user_id={{Auth::user()->id}}&autobot_id={{$autobot['id']}}" class="button skew bg-success">
                <span><b>{{number_format($autobot['price'], 0, '', ' ')}}</b> RUB за {{$autobot['days']}} дней</span>
              </a>
            @endforeach
            <button type="button" class="skew button-border text-secondary-emphasis" data-bs-dismiss="modal"><span>Закрыть</span></button>
          </div>
        </div>
      </div>
    </div>

    @foreach($products as $product)
      @if (Auth::user()->balance >= $product->price && $product->lock == 0)
        <form action="{{ route('order.store') }}" method="post">
          @csrf
          @endif
          <div class="modal fade" id="productModal_{{$product->id}}">
            <div class="modal-dialog modal-fullscreen">
              <div class="modal-content">
                <div class="modal-body">
                  <div class="photo">
                    <img src="{{$product->image}}" alt="{{$product->name}}">
                  </div>
                  <h5 class="mt-3 text-secondary-emphasis">{{$product->name}}</h5>

                  @if (Auth::user()->balance >= $product->price && $product->lock == 0)
                    <div class="form">
                      <h3>Данные получателя</h3>
                      <div>
                        <input type="hidden" name="id" value="{{$product->id}}">

                        @if($product->name !== 'Оплата мобильной связи РФ (10р)')
                          <div class="input">
                            <input name="name1" type="text" placeholder="Имя" value="{{Auth::user()->first_name}}">
                          </div>
                          <div class="input">
                            <input name="name3" type="text" placeholder="Отчество" value="{{Auth::user()->sur_name}}">
                          </div>
                          <div class="input">
                            <input name="name2" type="text" placeholder="Фамилия" value="{{Auth::user()->last_name}}">
                          </div>

                          <div class="input">
                            <input name="address" type="text" placeholder="Адрес доставки" value="{{Auth::user()->address}}">
                          </div>
                        @endif
                        <div class="input">
                          <input name="phone" type="text" placeholder="Номер телефона для уведомлений" value="{{Auth::user()->phone}}">
                        </div>
                      </div>
                    </div>
                  @endif

                </div>
                <div class="modal-footer">
                  @if ( Auth::user()->balance >= $product->price && $product->lock == 0 )
                    <button type="submit" class="skew bg-success"><span>{{number_format($product->price, 0, '', ' ')}} FC</span></button>
                  @elseif( $product->lock == 1 )
                    <button type="button" class="skew bg-transparent text-warning"><span>Этот товар можно заказать только 1 раз</span></button>
                  @else
                    <button type="button" class="skew bg-transparent text-warning"><span>Не достаточно FC</span></button>
                  @endif

                  <button type="button" class="skew button-border text-secondary-emphasis" data-bs-dismiss="modal"><span>Закрыть</span></button>
                </div>

              </div>
            </div>
          </div>
          @if (Auth::user()->balance >= $product->price)
        </form>
      @endif
    @endforeach
  @endauth
@endsection

@section('navbar')
  @include('components.nav')
@endsection
