<!DOCTYPE html>
<html>
<head>
    <meta http-equiv='X-UA-Compatible' content='IE=Edge' />
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no'/>
    
    <title>My Map</title>
    
    <link rel='stylesheet' type='text/css' href='https://api.tomtom.com/maps-sdk-for-web/cdn/5.x/5.45.0/maps/maps.css'/>
    <link rel='stylesheet' type='text/css' href='https://api.tomtom.com/maps-sdk-for-web/cdn/5.x/5.45.0/maps/css-styles/traffic-incidents.css'/>
    <link rel='stylesheet' type='text/css' href='https://api.tomtom.com/maps-sdk-for-web/cdn/5.x/5.45.0/maps/css-styles/routing.css'/>
    <link rel='stylesheet' type='text/css' href='https://api.tomtom.com/maps-sdk-for-web/cdn/5.x/5.45.0/maps/css-styles/poi.css'/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.css">
    <link href="/css/map.css" rel="stylesheet" type="text/css">
    
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.js"></script>
</head>
<body>
    <div id="map" class="map"></div>
    <div id="search" class="container">
        <div id="inputs">
            <ul>
                <li>
                    <input type="text">
                    <span class="delete-span">x</span>
                    <div class="search"></div>
                </li>
            </ul>
        </div>  
        <div id="buttons">
            <div id="first-bar" class="bar" style="padding-bottom: 6px;">
                <a id="add-input-button">+ Dodaj kordynaty</a>
            </div>
            <div id="second-bar" class="bar">
                <a class="btn btn-info btn-sm"
                   data-toggle="collapse" 
                   href="#collapseConfig">Konfiguruj</a>
                <button id="show-route-button" 
                   class="btn btn-sm" 
                   style="color: white; background-color: #21B556; margin-left: 81px;"
                   disabled>Pokaż połączenie</button>
            </div>
        </div>
        
        <div class="collapse" id="collapseConfig" style="padding-top: 1px;">
            <hr>
            <div id="config-panel">
                <div id="config-panel-buttons" class="text-center">
                    <a class="btn btn-block btn-sm config-button" data-toggle="collapse" href="#collapseStartDateTime">
                        Data wyjazdu
                    </a>
                    <a class="btn btn-block btn-sm config-button" data-toggle="collapse" href="#collapseEndDateTime">
                        Data dojazdu
                    </a>
                    <a class="btn btn-block btn-sm config-button" data-toggle="collapse" href="#collapseSkip">
                        Omijanie
                    </a>
                    <a class="btn btn-block btn-sm config-button" data-toggle="collapse" href="#collapseVehicleSpecification">
                        Specyfikacja pojazdu
                    </a>
                </div>
                <div id="config-panel-info" style="padding-top: 1rem;">
                    <div class="collapse" id="collapseStartDateTime">
                        <div class="card card-body">
                            <form id="departAt" method="post">
                                <div>
                                    <label class="left" for="travel-start-date">
                                        <strong>
                                            Dzień:
                                        </strong>
                                    </label>
                                    <input class="right" style="width: 153px; height: 24px;" type="date" id="travel-start-date" name="travel-start-date">
                                    <div class="clear"></div>
                                </div>
                                <div>
                                    <label class="left" for="travel-start-time">
                                        <strong>
                                            Godzina:
                                        </strong>
                                    </label>
                                    <input class="timepicker right" style="width: 102px; height: 24px;" id="travel-start-time" name="travel-start-time">
                                    <div class="clear"></div>
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
                    <div class="collapse" id="collapseEndDateTime">
                        <div class="card card-body">
                            <form id="arriveAt" method="post">
                                <div>
                                    <label class="left" for="travel-end-date">
                                        <strong>
                                            Dzień:
                                        </strong>
                                    </label>
                                    <input class="right" style="width: 153px; height: 24px;" type="date" id="travel-end-date" name="travel-end-date">
                                    <div class="clear"></div>
                                </div>
                                <div>
                                    <label class="left" for="travel-end-time">
                                        <strong>
                                            Godzina:
                                        </strong>
                                    </label>
                                    <input class="timepicker right" style="width: 102px; height: 24px;" id="travel-end-time" name="travel-end-time">
                                    <div class="clear"></div>
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
                    <div class="collapse" id="collapseSkip">
                        <div class="card card-body">
                            <form id="bypassing" method="post">
                                <div id="motorways">
                                    <label for="motorways" class="left">
                                        <strong>
                                            Autostrady
                                        </strong>
                                    </label>
                                    <input name="motorways" type="checkbox" class="right">
                                    <div class="clear"></div>
                                </div>
                                <div id="tollRoads">
                                    <label for="tollRoads" class="left">
                                        <strong>
                                            Drogi płatne
                                        </strong>
                                    </label>
                                    <input name="tollRoads" type="checkbox" class="right">
                                    <div class="clear"></div>
                                </div>
                                <div id="unpavedRoads">
                                    <label for="unpavedRoads" class="left">
                                        <strong>
                                            Drogi nieutwardzone 
                                        </strong>
                                    </label>
                                    <input name="unpavedRoads" type="checkbox" class="right">
                                    <div class="clear"></div>
                                </div>
                                <div id="carpools">
                                    <label for="carpools" class="left">
                                        <strong>
                                            Przejazdy
                                        </strong>
                                    </label>
                                    <input name="carpools" type="checkbox" class="right">
                                    <div class="clear"></div>
                                </div>
                                <div id="ferries">
                                    <label for="ferries" class="left">
                                        <strong>
                                            Promy / autokuszetki
                                        </strong>
                                    </label>
                                    <input name="ferries" type="checkbox" class="right">
                                    <div class="clear"></div>
                                </div>
                                
                                <div id="vehicleAdrTunnelRestrictionCodeElement">
                                    <label data-toggle="collapse" href="#vehicleAdrTunnelRestrictionCode">
                                        <strong style="cursor: pointer;"> > Kody ograniczeń przejazdu przez tunele</strong>
                                    </label>
                                    <div class="collapse" id="vehicleAdrTunnelRestrictionCode">
                                        <div class="card card-body">
                                            <div id="a">
                                                <label for="a" class="left">
                                                    <strong>
                                                        A
                                                    </strong>
                                                </label>
                                                <input name="a" type="checkbox" class="right">
                                                <div class="clear"></div>
                                            </div>
                                            <div id="b">
                                                <label for="b" class="left">
                                                    <strong>
                                                        B
                                                    </strong>
                                                </label>
                                                <input name="b" type="checkbox" class="right">
                                                <div class="clear"></div>
                                            </div>
                                            <div id="c">
                                                <label for="c" class="left">
                                                    <strong>
                                                        C
                                                    </strong>
                                                </label>
                                                <input name="c" type="checkbox" class="right">
                                                <div class="clear"></div>
                                            </div>
                                            <div id="d">
                                                <label for="d" class="left">
                                                    <strong>
                                                        D
                                                    </strong>
                                                </label>
                                                <input name="d" type="checkbox" class="right">
                                                <div class="clear"></div>
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
                    <div class="collapse" id="collapseVehicleSpecification">
                        <div class="card card-body">
                            <form id="vehicle-specification" method="post">
                                <div id="vehicleLength">
                                    <label for="vehicleLength" class="left"><strong>Długość</strong> (0 - 25.25 m)</label>
                                    <input name="vehicleLength" class="right" style="height: 22px;" min="0" max="25.25" type="text">
                                    <div class="clear"></div>
                                </div>
                                <div id="vehicleWidth">
                                    <label for="vehicleWidth" class="left"><strong>Szerokość</strong> (0 - 2.60 m)</label>
                                    <input name="vehicleWidth" class="right" style="height: 22px;" min="0" max="2.60" type="text">
                                    <div class="clear"></div>
                                </div>
                                <div id="vehicleHeight">
                                    <label for="vehicleHeight" class="left"><strong>Wysokość</strong> (0 - 4.95 m)</label>
                                    <input name="vehicleHeight" class="right" style="height: 22px;" min="0" max="4.95" type="text">
                                    <div class="clear"></div>
                                </div>
                                <div id="vehicleWeight">
                                    <label for="vehicleWeight" class="left"><strong>Masa brutto</strong> (0 - 60 t)</label>
                                    <input name="vehicleWeight" class="right" style="height: 22px;" min="0" max="60" type="text">
                                    <div class="clear"></div>
                                </div>
                                <div id="vehicleAxleWeight">
                                    <label for="vehicleAxleWeight" class="left"><strong>Nacisk osi</strong> (0 - 13 t)</label>
                                    <input name="vehicleAxleWeight" class="right" style="height: 22px;" min="0" max="13" type="text">
                                    <div class="clear"></div>
                                </div>
                                <div id="vehicleMaxSpeed">
                                    <label for="vehicleMaxSpeed" class="left"><strong>Maksymalna prędkość</strong> (0 - 100 km/h)</label>
                                    <input name="vehicleMaxSpeed" class="right" style="height: 22px;" min="0" max="100" type="text">
                                    <div class="clear"></div>
                                </div>

                                <div id="hazardous-materials">
                                    <label data-toggle="collapse" href="#collapseHazardousMaterials">
                                        <strong style="cursor: pointer;"> > Materiały niebiezpieczne</strong>
                                    </label>
                                    <div class="collapse" id="collapseHazardousMaterials">
                                        <div class="card card-body">
                                            <div id="otherHazmatExplosive">
                                                <label for="otherHazmatExplosive" class="left">
                                                    <strong>
                                                        Materiały wybuchowe
                                                    </strong>
                                                </label>
                                                <input name="otherHazmatExplosive" class="right" type="checkbox">
                                                <div class="clear"></div>
                                            </div>
                                            <div id="otherHazmatGeneral">
                                                <label for="otherHazmatGeneral" class="left">
                                                    <strong>
                                                        Inne materiały niebezpieczne
                                                    </strong>
                                                </label>
                                                <input name="otherHazmatGeneral" class="right" type="checkbox">
                                                <div class="clear"></div>
                                            </div>
                                            <div id="otherHazmatHarmfulToWater">
                                                <label for="otherHazmatHarmfulToWater" class="left">
                                                    <strong>
                                                        Szkodliwe dla wody
                                                    </strong>
                                                </label>
                                                <input name="otherHazmatHarmfulToWater" class="right" type="checkbox">
                                                <div class="clear"></div>
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
    <div id="route-info-panel">
        <div id="estimated-departure-or-arrival"></div>
        <div style="float: left; margin-right: 6px;">
            <label>Czas przejazdu:</label>
            <strong id="route-duration-indicator"></strong>
        </div>
        <div style="float: left; margin-right: 6px;">
            <label>Dystans całkowity:</label>
            <strong id="route-length-indicator"></strong>
        </div>
        <div style="clear: both;"></div>
    </div>
    
    <script src='https://api.tomtom.com/maps-sdk-for-web/cdn/5.x/5.45.0/maps/maps-web.min.js'></script>
    <script src="https://api.tomtom.com/maps-sdk-for-web/cdn/5.x/5.45.0/services/services-web.min.js"></script>
    <script src="/js/map.js"></script>
</body>
</html>