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
<!--                    <a class="btn btn-block btn-sm config-button" data-toggle="collapse" href="#collapseDateTime">
                        Czas wyjazdu
                    </a>-->
                    <a class="btn btn-block btn-sm config-button" data-toggle="collapse" href="#collapseSkip">
                        Omijanie
                    </a>
                    <a class="btn btn-block btn-sm config-button" data-toggle="collapse" href="#collapseVehicleSpecification">
                        Specyfikacja pojazdu
                    </a>
                </div>
                <div id="config-panel-info" style="padding-top: 1rem;">
<!--                    <div class="collapse" id="collapseDateTime">
                        <div class="card card-body">
                            pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.
                        </div>
                    </div>-->
                    <div class="collapse" id="collapseSkip">
                        <div class="card card-body">
                            <div id="highway">
                                <label style="padding-right: 121px;">
                                    <strong>
                                        Autostrady
                                    </strong>
                                </label>
                                <input type="radio">
                            </div>
                            <div id="dirt-road">
                                <label style="padding-right: 93px;">
                                    <strong>
                                        Droga gruntowa
                                    </strong>
                                </label>
                                <input type="radio">
                            </div>
                            <div id="toll-road">
                                <label style="padding-right: 111px;">
                                    <strong>
                                        Droga płatna
                                    </strong>
                                </label>
                                <input type="radio">
                            </div>
                            <div id="roads-for-vehicles-with-passengers">
                                <label>
                                    <strong>
                                        Pasy dla pojazdów z pasażerami
                                    </strong>
                                </label>
                                <input type="radio">
                            </div>
                            <div id="ferry">
                                <label style="padding-right: 65px;">
                                    <strong>
                                        Promy / autokuszetki
                                    </strong>
                                </label>
                                <input type="radio">
                            </div>
                            
                            <div class="text-center" style="margin-top: 1rem;">
                                <a class="btn btn-info btn-sm" 
                                   style="color: white;" 
                                   data-toggle="collapse" 
                                   href="#collapseConfig">Gotowe</a>
                            </div>
                        </div>
                    </div>
                    <div class="collapse" id="collapseVehicleSpecification">
                        <div class="card card-body">
                            <div id="truck-length">
                                <label style="padding-right: 99px;"><strong>Długość</strong> (0 - 25,25 m)</label>
                                <input type="text">
                            </div>
                            <div id="truck-width">
                                <label style="padding-right: 93px;"><strong>Szerokość</strong> (0 - 2,60 m)</label>
                                <input type="text">
                            </div>
                            <div id="truck-height">
                                <label style="padding-right: 94px;"><strong>Wysokość</strong> (0 - 4,95 m)</label>
                                <input type="text">
                            </div>
                            <div id="truck-weight">
                                <label style="padding-right: 101px;"><strong>Masa brutto</strong> (0 - 60 t)</label>
                                <input type="text">
                            </div>
                            <div id="truck-axle-pressure">
                                <label style="padding-right: 110px;"><strong>Nacisk osi</strong> (0 - 13 t)</label>
                                <input type="text">
                            </div>
                            <div id="truck-max-speed">
                                <label style="padding-right: 12px;"><strong>Maksymalna prędkość</strong> (0 - 100 km/h)</label>
                                <input type="text">
                            </div>
                            
                            <div id="hazardous-materials">
                                <label data-toggle="collapse" href="#collapseHazardousMaterials">
                                    <strong style="cursor: pointer;"> > Materiały niebiezpieczne</strong>
                                </label>
                                <div class="collapse" id="collapseHazardousMaterials">
                                    <div class="card card-body">
                                        <div id="explosives">
                                            <label style="padding-right: 51px;">
                                                <strong>
                                                    Materiały wybuchowe
                                                </strong>
                                            </label>
                                            <input type="radio">
                                        </div>
                                        <div id="other-hazardous-materials">
                                            <label style="padding-right: 9px;">
                                                <strong>
                                                    Inne materiały niebezpieczne
                                                </strong>
                                            </label>
                                            <input type="radio">
                                        </div>
                                        <div id="water-contamination">
                                            <label style="padding-right: 62px;">
                                                <strong>
                                                    Szkodliwe dla wody
                                                </strong>
                                            </label>
                                            <input type="radio">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center" style="margin-top: 1rem;">
                                <a class="btn btn-info btn-sm" 
                                   style="color: white;" 
                                   data-toggle="collapse" 
                                   href="#collapseConfig">Gotowe</a>
                            </div>
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