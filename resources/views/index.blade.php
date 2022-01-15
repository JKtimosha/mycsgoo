<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="shortcut icon" href="/favicon.ico">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $settings->title }}</title>

        <meta name="description" content="{{ $settings->description }}">
        <meta name="keywords" content="{{ $settings->keywords }}">

        <meta name="settings" content='{!! json_encode($settings) !!}'>

        @if(Auth::check())
        <meta name="user" content='{!! json_encode($u) !!}'>
        @else
        <meta name="user" content='{!! json_encode(null) !!}'>
        @endif

        <link rel="stylesheet" href="{{ asset('/css/project.css') }}?v={{time()}}">
    </head>
    <body>
        <div id="app">
            <app></app>
        </div>
        <a href="//showstreams.tv/"><img src="//www.free-kassa.ru/img/fk_btn/21.png" title="Бесплатный видеохостинг"></a>
    </body>
    <script src="{{ mix('js/all.js') }}?v={{time()}}"></script>
</html>