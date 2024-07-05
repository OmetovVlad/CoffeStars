@extends('admin.layouts.admin')

@section('content')
  <h2 class="mt-3">Не обработанные заказы</h2>

  <div class="row">

    @foreach($items as $item)
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3 mb-3">
        <div class="card border-0">

          @foreach($products as $product)
            @if($product->id === $item->product_id)
              <div class="bg-white rounded-3 overflow-hidden d-flex justify-content-center align-items-center" style="width: 100%; height: 280px;">
                <img class="p-3" style="max-width: 100%; max-height: 100%;" src="{{$product->image}}" alt="{{$product->name}}">
              </div>
            @endif
          @endforeach

          <div class="card-body px-0 pb-0">
            @foreach($products as $product)
              @if($product->id === $item->product_id)
                <h5 class="card-title mb-3">{{$product->name}}</h5>
              @endif
            @endforeach

            <p class="card-text text-secondary">{!! $item->address !!}</p>
            <h6 class="card-subtitle mb-3 text-body-secondary">{{number_format($item->price, 0, '', ' ')}} FC</h6>
            <div class="row">
              <div class="col-12">
                <form action="{{route('admin.order.update', $item->id)}}" method="POST">
                  @csrf
                  @method('patch')
                  <button type="submit" class="btn btn-success w-100">Обработать</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach

  </div>

@endsection