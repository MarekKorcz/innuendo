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
    <link href="/css/bio/app.css" rel="stylesheet" type="text/css">
    
    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="/js/bio/app.js"></script>
    
    <!-- Fonts -->
    <script src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
</head>
<body>

    <!--Navbar-->
    <nav class="navbar navbar-expand-md navbar-light">

        <a href="{{ route('bioHome') }}">
            <p id="logo-text" class="logo-text navbar-text mr-auto">
                BIOENERGOTERAPIA </br>
                <span id="logo-text-name">
                    Patrycja Dolata
                </span>
            </p>
        </a>

        <div class="collapse navbar-collapse" id="collapse_target">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('bioHome') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('bioReservation') }}">Rezerwacja</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('bioHome') }}">Blog</a>
                </li>
                <li class="nav-item">
                    <a id="facebook" class="nav-link" href="https://www.facebook.com/Bioenergoterapia-Patrycja-Dolata-107696647457410/" target="_blank">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                </li>
            </ul>
        </div>

        <button class="navbar-toggler ml-auto" data-toggle="collapse" data-target="#collapse_target">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>

    <!--@include('inc.messages')-->
    
    @yield('content')
    
    <footer class="container text-center">
        <div class="row">
            <div class="col-sm-12">
                <p id="copyright">
                    &copy; {{ now()->year }} Bioenergoterapia Patrycja Dolata. Wszelkie prawa zastrzeżone
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
