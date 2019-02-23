<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>
    
    <!-- Styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="/css/app.css" rel="stylesheet" type="text/css">
    
    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="/js/app.js"></script>
    
    <!-- Fonts -->
    <script src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
    
    <!--ck-editor-->
    <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            CKEDITOR.replace( 'article-ckeditor' );
        });
    </script>
</head>
<body>

    <!--Navbar-->
    <nav class="navbar navbar-expand-md navbar-light bg-light sticky-top">
        
        <a class="navbar-brand" href="{{ route('welcome') }}">
            <img src="/img/sacred-geometry.jpg" width="72" height="72" class="d-inline-block align-top">
        </a>
        <span id="logo-text" class="navbar-text mr-auto">
            <a href="{{ route('welcome') }}">
                {{ config('app.name') }}
            </a>
        </span>
    
        <div class="collapse navbar-collapse" id="collapse_target">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('user/properties') }}">Lokalizacja</a>
                </li>
                <li class="nav-item" style="margin-right: 10px;">
                    <a class="nav-link" href="{{ url('employees') }}">Pracownicy</a>
                </li>
                @guest
                    <li class="nav-item" style="font-weight: 600;">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Zaloguj') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Zarejestruj') }}</a>
                        </li>
                    @endif
                @else
                    @if (Auth::user()->isAdmin)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/property/index') }}">
                                Property index
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            Moje konto
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
                            {{ __('Wyloguj') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
        
        <button class="navbar-toggler ml-auto" data-toggle="collapse" data-target="#collapse_target">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>

    @include('inc.messages')
    
    @yield('content')
    
    <footer class="container-fluid text-center">
        <div class="row">
            <div class="col-sm-6">
                <h4>Kontakt</h4>
                <h5>Email: mark.korcz@gmail.com</h5>
                <h5>Tel: 602 342 396</h5>
            </div>
            <div class="col-sm-3">
                <h4>Connect</h4>
                <a href="#" class="fa fa-facebook"></a>
                <a href="#" class="fa fa-twitter"></a>
                <a href="#" class="fa fa-google"></a>
            </div>
            <div class="col-sm-3">
                <a class="navbar-brand" href="{{ route('welcome') }}">
                    <img src="/img/sacred-geometry.jpg" width="90" height="90">
                </a>
            </div>
        </div>
    </footer>
</body>
</html>
