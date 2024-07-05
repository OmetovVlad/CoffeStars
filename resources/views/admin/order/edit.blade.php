@extends('admin.layouts.admin')

@section('content')
  <h2 class="mt-3">Добавить товар</h2>
  <form action="{{route('admin.product.update', $product->id)}}" method="POST">
    @csrf
    @method('patch')
    <div class="mb-3">
      <label for="name" class="form-label">Название</label>
      <input name="name" type="text" class="form-control" id="name" value="{{$product->name}}">
    </div>
    <div class="mb-3">
      <label for="photo" class="form-label">Фото</label>
      <input name="image" type="text" class="form-control" id="photo" value="{{$product->image}}">
    </div>
    <div class="mb-3">
      <label for="price" class="form-label">Цена</label>
      <input name="price" type="number" class="form-control" id="price" value="{{$product->price}}">
    </div>
    <div class="mb-3">
      <div class="form-check">
        <input class="form-check-input" type="radio" id="flexRadioDefault1" name="limit" value="1">
        <label class="form-check-label" for="flexRadioDefault1">
          Купить можно только 1 раз
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" id="flexRadioDefault2" name="limit" value="0" checked>
        <label class="form-check-label" for="flexRadioDefault2">
          Количество покупок не ограничено
        </label>
      </div>
    </div>

    <input type="hidden" name="description" value="{{$product->price}}">

    <button type="submit" class="btn btn-primary">Изменить товар</button>
  </form>
@endsection