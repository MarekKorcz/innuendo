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
    
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>
    
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
                    <a class="btn btn-block btn-sm config-button" data-toggle="collapse" href="#collapseSkip">
                        Omijanie
                    </a>
                    <a class="btn btn-block btn-sm config-button" data-toggle="collapse" href="#collapseVehicleSpecification">
                        Specyfikacja pojazdu
                    </a>
                </div>
                <div id="config-panel-info" style="padding-top: 1rem;">
                    <div class="collapse" id="collapseSkip">
                        <div class="card card-body">
                            <form id="bypassing" method="post">
                                <div id="highway">
                                    <label for="highway" style="padding-right: 121px;">
                                        <strong>
                                            Autostrady
                                        </strong>
                                    </label>
                                    <input name="highway" type="checkbox">
                                </div>
                                <div id="dirt-road">
                                    <label for="dirt-road" style="padding-right: 93px;">
                                        <strong>
                                            Droga gruntowa
                                        </strong>
                                    </label>
                                    <input name="dirt-road" type="checkbox">
                                </div>
                                <div id="toll-road">
                                    <label for="toll-road" style="padding-right: 111px;">
                                        <strong>
                                            Droga płatna
                                        </strong>
                                    </label>
                                    <input name="toll-road" type="checkbox">
                                </div>
                                <div id="roads-for-vehicles-with-passengers">
                                    <label for="roads-for-vehicles-with-passengers">
                                        <strong>
                                            Pasy dla pojazdów z pasażerami
                                        </strong>
                                    </label>
                                    <input name="roads-for-vehicles-with-passengers" type="checkbox">
                                </div>
                                <div id="ferry">
                                    <label for="ferry" style="padding-right: 65px;">
                                        <strong>
                                            Promy / autokuszetki
                                        </strong>
                                    </label>
                                    <input name="ferry" type="checkbox">
                                </div>

                                <div class="text-center" style="margin-top: 1rem;">
                                    <input type="submit" 
                                           value="Gotowe" 
                                           class="btn btn-info btn-sm" 
                                           style="color: white;" 
                                           data-toggle="collapse" 
                                           href="#collapseConfig">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="collapse" id="collapseVehicleSpecification">
                        <div class="card card-body">
                            <form id="vehicle-specification" method="post">
                                <div id="truck-length">
                                    <label for="truck-length" style="padding-right: 99px;"><strong>Długość</strong> (0 - 25,25 m)</label>
                                    <input name="truck-length" type="text">
                                </div>
                                <div id="truck-width">
                                    <label for="truck-width" style="padding-right: 93px;"><strong>Szerokość</strong> (0 - 2,60 m)</label>
                                    <input name="truck-width" type="text">
                                </div>
                                <div id="truck-height">
                                    <label for="truck-height" style="padding-right: 94px;"><strong>Wysokość</strong> (0 - 4,95 m)</label>
                                    <input name="truck-height" type="text">
                                </div>
                                <div id="truck-weight">
                                    <label for="truck-weight" style="padding-right: 101px;"><strong>Masa brutto</strong> (0 - 60 t)</label>
                                    <input name="truck-weight" type="text">
                                </div>
                                <div id="truck-axle-pressure">
                                    <label style="padding-right: 110px;"><strong>Nacisk osi</strong> (0 - 13 t)</label>
                                    <input name="truck-axle-pressure" type="text">
                                </div>
                                <div id="truck-max-speed">
                                    <label for="truck-max-speed" style="padding-right: 12px;"><strong>Maksymalna prędkość</strong> (0 - 100 km/h)</label>
                                    <input name="truck-max-speed" type="text">
                                </div>

                                <div id="hazardous-materials">
                                    <label data-toggle="collapse" href="#collapseHazardousMaterials">
                                        <strong style="cursor: pointer;"> > Materiały niebiezpieczne</strong>
                                    </label>
                                    <div class="collapse" id="collapseHazardousMaterials">
                                        <div class="card card-body">
                                            <div id="explosives">
                                                <label for="explosives" style="padding-right: 51px;">
                                                    <strong>
                                                        Materiały wybuchowe
                                                    </strong>
                                                </label>
                                                <input name="explosives" type="checkbox">
                                            </div>
                                            <div id="other-hazardous-materials">
                                                <label for="other-hazardous-materials" style="padding-right: 9px;">
                                                    <strong>
                                                        Inne materiały niebezpieczne
                                                    </strong>
                                                </label>
                                                <input name="other-hazardous-materials" type="checkbox">
                                            </div>
                                            <div id="water-contamination">
                                                <label for="water-contamination" style="padding-right: 62px;">
                                                    <strong>
                                                        Szkodliwe dla wody
                                                    </strong>
                                                </label>
                                                <input name="water-contamination" type="checkbox">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center" style="margin-top: 1rem;">
                                    <input type="submit" 
                                           value="Gotowe" 
                                           class="btn btn-info btn-sm" 
                                           style="color: white;" 
                                           data-toggle="collapse" 
                                           href="#collapseConfig">
                                </div>
                            </form>
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