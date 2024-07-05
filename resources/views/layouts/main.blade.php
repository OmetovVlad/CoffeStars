<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Finger Mining</title>

    <meta name="verification" content="3a1dd98341fafc1dfe9bcf36360e6b84" />
    @vite('resources/sass/style.scss')
  </head>
  <body>
    @auth
      @include('components.header')
      <main>
    @endauth

      @yield('content')

    @auth
      </main>

      @yield('modals')
      @yield('navbar')
      @include('components.preloader')
      @include('components.scripts')
    @endauth
  </body>
</html>
