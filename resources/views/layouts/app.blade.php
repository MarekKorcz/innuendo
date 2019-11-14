<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} {{ config('app.name_2nd_part') }}</title>
    
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
    <nav class="navbar navbar-expand-md navbar-light bg-light">
        
<!--        <a class="navbar-brand" href="{{ route('welcome') }}">
            <img src="/img/sacred-geometry.jpg" width="72" height="72" class="d-inline-block align-top">
        </a>-->
        <span id="logo-text" class="logo-text navbar-text mr-auto" style="margin: 0 9px 0 3px;">
            <a href="{{ route('welcome') }}">
                {{ config('app.name') }}
                <span style="font-size: 21px;">
                    {{ config('app.name_2nd_part') }}
                </span>
            </a>
        </span>
    
        <div class="collapse navbar-collapse" id="collapse_target">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item" style="margin-right: 9px;">
                    <a class="nav-link" href="{{ url('subscriptions') }}">@lang('navbar.subscriptions')</a>
                </li>
                <li class="nav-item" style="margin-right: 9px;">
                    <a class="nav-link" href="{{ url('discounts') }}">@lang('navbar.discounts')</a>
                </li>
<!--                <li class="nav-item" style="margin-right: 9px;">
                    <a class="nav-link" href="{{ url('employees') }}">@lang('navbar.employees')</a>
                </li>-->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">@lang('navbar.login')</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">@lang('navbar.register')</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            @lang('navbar.my_account')
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
                            @lang('navbar.logout')
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                @endguest
                <li class="nav-item">
                    @if (Session('locale') == "en")
                        <a class="nav-link" href="{{ url('locale/pl') }}">Polski</a>
                    @else
                        <a class="nav-link" href="{{ url('locale/en') }}">English</a>
                    @endif
                </li>
            </ul>
        </div>
        
        <button class="navbar-toggler ml-auto" data-toggle="collapse" data-target="#collapse_target">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>

    @include('inc.messages')
    
    @yield('content')
    
    <footer class="container-fluid text-center">
<!--            <div class="col-sm-3" style="padding: 15px;">
                <h4>@lang('footer.contact')</h4>
                <h5>mark.korcz@gmail.com</h5>
                <h5>602 342 396</h5>
                <a id="facebook" href="https://www.facebook.com/Gabinet.Masazu.Mokotow/">
                    <i class="fab fa-facebook-square"></i>
                </a>
            </div>
            <div class="col-sm-6">
                <a class="text-center" href="{{ route('welcome') }}">
                    <img src="/img/sacred-geometry.jpg" width="99" height="99" class="img-fluid">
                </a>
                <br>
                <span id="footer-name" class="logo-text">
                    {{ config('app.name') }}
                    <span style="font-size: 21px;">
                        {{ config('app.name_2nd_part') }}
                    </span>
                </span>
            </div>
            <div class="col-sm-3 footer-3">
                <div class="text-center">
                    <h5>
                        <a href="{{ route('contact_page') }}" target="_blanc">
                            @lang('contact.contact_page')
                        </a>
                    </h5>
                    <h5>
                        <a href="{{ route('cookies_policy') }}" target="_blanc">
                            @lang('cookies.cookies_policy')
                        </a>
                    </h5>
                    <h5>
                        <a href="{{ route('private_policy') }}" target="_blanc">
                            @lang('private_policy.private_policy')
                        </a>
                    </h5>
                </div>
            </div>-->
        <div class="row">
            <div class="col-1"></div>
            <div class="col-5">
                <div class="position-center">
                    <p>
                        <span id="footer-name" class="logo-text">
                            {{ config('app.name') }}
                            <span style="font-size: 21px;">
                                {{ config('app.name_2nd_part') }}
                            </span>
                        </span>
                    </p>
                </div>
            </div>
            <div class="col-5">
                <ul>
                    <li>
                        <a href="{{ route('contact_page') }}" target="_blanc">
                            @lang('contact.contact_page')
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('cookies_policy') }}" target="_blanc">
                            @lang('cookies.cookies_policy')
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('private_policy') }}" target="_blanc">
                            @lang('private_policy.private_policy')
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('rodo') }}" target="_blanc">
                            @lang('common.rodo_policy')
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-1"></div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <p id="copyright">{{ now()->year }} &copy; @lang('footer.all_rights')</p>
            </div>
        </div>
    </footer>
</body>
</html>
