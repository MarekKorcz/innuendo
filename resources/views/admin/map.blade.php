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
        <div id="buttons">
            <a id="add-input-button" 
               class="btn btn-success btn-sm" 
               style="color: white; background-color: #21B556;">+ Dodaj kordynaty</a>
            <a class="btn btn-info btn-sm" 
               style="color: white; margin-left: 81px;" 
               data-toggle="collapse" 
               href="#collapseConfig">Konfiguruj</a>
        </div>
        
        <div class="collapse" id="collapseConfig">
            <hr>
            <div id="config-panel">
                <div id="config-panel-buttons" class="text-center">
                    <a class="btn btn-block btn-sm config-button" data-toggle="collapse" href="#collapseDateTime">
                        Czas wyjazdu
                    </a>
                    <a class="btn btn-block btn-sm config-button" data-toggle="collapse" href="#collapseSkip">
                        Omijanie
                    </a>
                    <a class="btn btn-block btn-sm config-button" data-toggle="collapse" href="#collapseVehicleSpecification">
                        Specyfikacja pojazdu
                    </a>
                </div>
                <div id="config-panel-info" style="padding-top: 1rem;">
                    <div class="collapse" id="collapseDateTime">
                        <div class="card card-body">
                            pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.
                        </div>
                    </div>
                    <div class="collapse" id="collapseSkip">
                        <div class="card card-body">
                            cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.
                        </div>
                    </div>
                    <div class="collapse" id="collapseVehicleSpecification">
                        <div class="card card-body">
                            reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
    <script src='https://api.tomtom.com/maps-sdk-for-web/cdn/5.x/5.45.0/maps/maps-web.min.js'></script>
    <script src="https://api.tomtom.com/maps-sdk-for-web/cdn/5.x/5.45.0/services/services-web.min.js"></script>
    <script src="/js/map.js"></script>
</body>
</html>