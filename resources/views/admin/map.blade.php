<!DOCTYPE html>
<html class='use-all-space'>
<head>
    <meta http-equiv='X-UA-Compatible' content='IE=Edge' />
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no'/>
    
    <title>My Map</title>
    
    <link rel='stylesheet' type='text/css' href='https://api.tomtom.com/maps-sdk-for-web/cdn/5.x/5.45.0/maps/maps.css'/>
    <link rel='stylesheet' type='text/css' href='https://api.tomtom.com/maps-sdk-for-web/cdn/5.x/5.45.0/maps/css-styles/traffic-incidents.css'/>
    <link rel='stylesheet' type='text/css' href='https://api.tomtom.com/maps-sdk-for-web/cdn/5.x/5.45.0/maps/css-styles/routing.css'/>
    <link rel='stylesheet' type='text/css' href='https://api.tomtom.com/maps-sdk-for-web/cdn/5.x/5.45.0/maps/css-styles/poi.css'/>
    <link href="/css/map.css" rel="stylesheet" type="text/css">
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body>
    <!--<div id="map" class="map"></div>-->
    <div id="search" class="container">
        <div id="inputs">
            <div class="search-box">
                <ul>
                    <li>
                        <input type="text">
                        <span class="delete-span">x</span>
                    </li>
                    <li>
                        <input type="text">
                        <span class="delete-span">x</span>
                    </li>
                </ul>
            </div>
        </div>
        <div id="add-input-button" class="text-center">
            <a class="btn btn-success" style="color: white;">Dodaj kordynaty</a>
        </div>
    </div>
    
    <script src='https://api.tomtom.com/maps-sdk-for-web/cdn/5.x/5.45.0/maps/maps-web.min.js'></script>
    <script src="https://api.tomtom.com/maps-sdk-for-web/cdn/5.x/5.45.0/services/services-web.min.js"></script>
    <script src="/js/map.js"></script>
</body>
</html>