@extends('admin.layouts.admin')

@section('content')
  <h2 class="mt-3">Все товар</h2>

  <div class="row">

    @foreach($items as $item)
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3 mb-3">
        <div class="card border-0">
          <div class="bg-white rounded-3 overflow-hidden d-flex justify-content-center align-items-center" style="width: 100%; height: 280px;">
            <img class="p-3" style="max-width: 100%; max-height: 100%;" src="{{$item->image}}" alt="{{$item->name}}">
          </div>
          <div class="card-body px-0 pb-0">
            <h5 class="card-title mb-3">{{$item->name}}</h5>
            <h6 class="card-subtitle mb-3 text-body-secondary">{{number_format($item->price, 0, '', ' ')}} FC</h6>
            <div class="row">
              <div class="col-6"><a href="{{ route('admin.product.edit', $item->id) }}" class="btn btn-primary w-100">Edit</a></div>
              <div class="col-6">
                <form action="{{route('admin.product.delete', $item->id)}}" method="POST">
                  @csrf
                  @method('delete')
                  <button type="submit" class="btn btn-danger w-100">Delete</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach

  </div>

@endsection