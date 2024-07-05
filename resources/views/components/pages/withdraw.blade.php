<div id="withdraw" @class([
    'pageWrapper',
    'hide' => $isHide,
])>
  <div class="form">
    <h2>Запрос на выплату</h2>
    <div class="form">
      <div class="input">
        <input id="withdraw_sum" name="sum" type="number" placeholder="Сумма">
      </div>
      <button id="withdraw_btn"><span>Вывести</span></button>
    </div>
  </div>
</div>