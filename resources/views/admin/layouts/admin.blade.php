<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Finger Mining - Товары</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container">
      <a class="navbar-brand" href="{{route('admin.index')}}">FingerMining</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="{{route('admin.product.index')}}">Все товары</a></li>
          <li class="nav-item"><a class="nav-link" href="{{route('admin.product.create')}}">Добавить товар</a></li>
          <li class="nav-item"><a class="nav-link" href="{{route('admin.order.index')}}">Заказы</a></li>
{{--          <li class="nav-item dropdown">--}}
{{--            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">--}}
{{--              Магазин--}}
{{--            </a>--}}
{{--            <ul class="dropdown-menu">--}}
{{--              <li><a class="dropdown-item" href="{{route('product.index')}}">Все товары</a></li>--}}
{{--              <li><a class="dropdown-item" href="{{route('product.create')}}">Добавить товар</a></li>--}}
{{--              <li><a class="dropdown-item" href="#">Заказы</a></li>--}}
{{--            </ul>--}}
{{--          </li>--}}
        </ul>
      </div>
    </div>
  </nav>
    <div class="container">
      @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>