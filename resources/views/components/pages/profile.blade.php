<div id="profile" @class([
    'pageWrapper',
    'hide' => $isHide,
])>

  @if(in_array(Auth::user()->id, explode(',', env('ADMIN_LIST')) ))
    <a href="{{route('admin.product.index')}}" class="button skew bg-success"><span>Админка</span></a>
  @endif

  <h2>ID #{{Auth::user()->id}}</h2>

  <div class="form">
    <div>
      <div class="input">
        <input id="name1" name="name1" type="text" placeholder="Имя" value="{{Auth::user()->first_name}}">
      </div>
      <div class="input">
        <input id="name3" name="name3" type="text" placeholder="Отчество" value="{{Auth::user()->sur_name}}">
      </div>
      <div class="input">
        <input id="name2" name="name2" type="text" placeholder="Фамилия" value="{{Auth::user()->last_name}}">
      </div>

      <div class="input">
        <input id="address" name="address" type="text" placeholder="Индекс, область, город, улица, дом, квартира" value="{{Auth::user()->address}}">
      </div>
      <div class="input">
        <input id="phone" name="phone" type="text" placeholder="Номер телефона для уведомлений" value="{{Auth::user()->phone}}">
      </div>

{{--      <div class="input">--}}
{{--        <input id="bank-card-input" name="card" type="text" placeholder="Номер карты для выплаты" value="{{Auth::user()->card}}">--}}
{{--      </div>--}}

{{--      <span class="validate card hide">Не корректно введен номер карты</span>--}}

      <button id="profile_save_data_button" class="skew"><span>Сохранить</span></button>
    </div>
  </div>

  <div id="bg"></div>

</div>