<div id="friends" @class([
    'pageWrapper',
    'hide' => $isHide,
])>
  <div class="switch">
    <div class="active"><span>Предложения</span></div>
    <div><span>Друзья</span></div>
  </div>

  <div class="switch_content">
    {{-- Партнеры --}}
    <div class="active">
      <h2>Предложения от партнеров</h2>

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


    {{-- Рефералы --}}
    <div>
      <h2>Ваши друзья</h2>

      <div class="form">
        <h3>Ссылка для приглашения</h3>
        <div id="referral_link" class="input">
          <input type="text" value="https://t.me/fingermining_bot?start={{Auth::user()->id}}" disabled>
        </div>
      </div>

      @if (count($referralsTree) > 0)
      <h3>Статистика</h3>
      <div class="accrualBalance">
        <div class="icon">
          <img src="{{ Vite::asset('resources/img/coin_min.png') }}">
        </div>
        <span>{{ $weekBalanceAccrual }}</span>
        <div class="infoIcon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
            <path fill-rule="evenodd" d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z" clip-rule="evenodd" />
          </svg>
        </div>

        <div class="info">
          <p class="l1"> 1 линия - 20 %</p>
          <p class="l2">2 линия - 10 %</p>
          <p class="l3">3 линия - 5 %</p>
          <p class="l4">4 линия - 3 %</p>
          <p class="l5">5 линия - 1 %</p>
          <p class="l6">6 линия - 1 %</p>
        </div>
      </div>

      <div class="referralTree">
        @for ($i = 0; $i < count($referralsTree); $i++)
          <div class="line">
            <div class="name">
              <span class="username">{{ $referralsTree[$i]->name }}</span>
              <span class="balance">{{ $referralsTree[$i]->weekBalance }} FC</span>
              <span class="balance">{{ $referralsTree[$i]->weekBalanceAccrual }} FC</span>
            </div>

            @if(count(get_object_vars($referralsTree[$i])) - 5 > 0)
            <div class="arrow">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"> <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /> </svg>
            </div>
            <div class="sub">
              @for ($j = 0; $j < count(get_object_vars($referralsTree[$i])) - 5; $j++)
                <div class="line">
                  <div class="name">
                    <span class="username">{{ $referralsTree[$i]->$j->name }}</span>
                    <span class="balance">{{ $referralsTree[$i]->$j->weekBalance }} FC</span>
                    <span class="balance">{{ $referralsTree[$i]->$j->weekBalanceAccrual }} FC</span>
                  </div>

                  @if(count(get_object_vars($referralsTree[$i]->$j)) - 5 > 0)
                  <div class="arrow">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"> <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /> </svg>
                  </div>
                  <div class="sub">
                    @for ($k = 0; $k < count(get_object_vars($referralsTree[$i]->$j)) - 5; $k++)

                      <div class="line">
                        <div class="name">
                          <span class="username">{{ $referralsTree[$i]->$j->$k->name }}</span>
                          <span class="balance">{{ $referralsTree[$i]->$j->$k->weekBalance }} FC</span>
                          <span class="balance">{{ $referralsTree[$i]->$j->$k->weekBalanceAccrual }} FC</span>
                        </div>

                        @if(count(get_object_vars($referralsTree[$i]->$j->$k)) - 5 > 0)
                        <div class="arrow">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"> <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /> </svg>
                        </div>
                        <div class="sub">
                          @for ($n = 0; $n < count(get_object_vars($referralsTree[$i]->$j->$k)) - 5; $n++)
                            <div class="line">
                              <div class="name">
                                <span class="username">{{ $referralsTree[$i]->$j->$k->$n->name }}</span>
                                <span class="balance">{{ $referralsTree[$i]->$j->$k->$n->weekBalance }} FC</span>
                                <span class="balance">{{ $referralsTree[$i]->$j->$k->$n->weekBalanceAccrual }} FC</span>
                              </div>

                              @if(count(get_object_vars($referralsTree[$i]->$j->$k->$n)) - 5 > 0)
                              <div class="arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"> <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /> </svg>
                              </div>
                              <div class="sub">
                                @for ($m = 0; $m < count(get_object_vars($referralsTree[$i]->$j->$k->$n)) - 5; $m++)
                                  <div class="line">
                                    <div class="name">
                                      <span class="username">{{ $referralsTree[$i]->$j->$k->$n->$m->name }}</span>
                                      <span class="balance">{{ $referralsTree[$i]->$j->$k->$n->$m->weekBalance }} FC</span>
                                      <span class="balance">{{ $referralsTree[$i]->$j->$k->$n->$m->weekBalanceAccrual }} FC</span>
                                    </div>

                                    @if(count(get_object_vars($referralsTree[$i]->$j->$k->$n->$m)) - 5 > 0)
                                    <div class="arrow">
                                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"> <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /> </svg>
                                    </div>
                                    <div class="sub">
                                      @for ($l = 0; $l < count(get_object_vars($referralsTree[$i]->$j->$k->$n->$m)) - 5; $l++)
                                        <div class="line">
                                          <div class="name">
                                            <span class="username">{{ $referralsTree[$i]->$j->$k->$n->$m->$l->name }}</span>
                                            <span class="balance">{{ $referralsTree[$i]->$j->$k->$n->$m->$l->weekBalance }} FC</span>
                                            <span class="balance">{{ $referralsTree[$i]->$j->$k->$n->$m->$l->weekBalanceAccrual }} FC</span>
                                          </div>
                                        </div>
                                      @endfor
                                    </div>
                                    @endif

                                  </div>
                                @endfor
                              </div>
                              @endif

                            </div>
                          @endfor
                        </div>
                        @endif

                      </div>
                    @endfor
                  </div>
                  @endif

                </div>
              @endfor
            </div>
            @endif

          </div>
        @endfor
      </div>
      @endif

    </div>
  </div>




</div>