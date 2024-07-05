{{-- <div id="shop" @class([
    'pageWrapper',
    'hide' => $isHide,
])>
  <h2>Магазин</h2>

  <div class="list">

    @foreach($products as $product)
      <div class="product_card" data-bs-toggle="modal" data-bs-target="#productModal_{{$product->id}}">
        <div class="photo" style="background-image: url('{{$product->image}}');"></div>

        <h4>{{$product->name}}</h4>

        <button type="button" class="buy skew">
          <span>{{number_format($product->price, 0, '', ' ')}} FC</span>
        </button>
      </div>
    @endforeach


  </div>
</div> --}}

<div id="shop" @class([
    'updateInProgress',
    'pageWrapper',
    'hide' => $isHide,
])>
  <div class="content">
    <h2>Не пугайтесь, это временно!</h2>
    <p>Готовим крутое обновление 😎</p>
  </div>
</div>