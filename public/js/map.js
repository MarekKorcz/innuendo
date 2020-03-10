(function() 
{
    document.cookie = 'cross-site-cookie=bar; SameSite=None; Secure';
    
    let tomtomApiHref = 'https://api.tomtom.com'
    let searchVersionNumber = 2
    let ext = 'json'
    let apiKey = 'OmjUSjU5i4johYgNQfvhLGWqzbCmdZke'
    let lang = 'pl-PL'
    let countrySet = 'ESP,PRT,IRL,GBR,AND,FRA,MLT,MCO,ITA,VAT,CHE,AUT,LIE,DEU,LUX,BEL,NLD,DNK,NOR,SWE,FIN,EST,LVA,LTU,POL,BLR,UKR,CZE,SVK,SVN,HUN,HRV,BIH,SRB,ROU,MDA,MNE,ALB,MKD,BGR,GRC,TUR,RUS'
    let waypointMarkersArray = []

    var map = tt.map({
        key: apiKey,
        container: 'map',
        style: 'tomtom://vector/1/basic-main',
        center: [21.017532,52.237049],
        zoom: 9,
        language: lang
    });        

    //var config = {
    //    key: 'OmjUSjU5i4johYgNQfvhLGWqzbCmdZke',
    //    style: 'tomtom://vector/1/relative',
    //    refresh: 30000
    //};
    //
    //map.on('load', function() {
    //    map.addTier(new tt.TrafficFlowTilesTier(config));
    //});

    //map.addControl(new tt.FullscreenControl());

    map.addControl(new tt.NavigationControl());
    
    document.querySelector("#add-input-button").addEventListener("click", () => {
                
        let input = document.createElement('input')
        input.setAttribute('type', 'text')
        searchInputEvents(input)
        
        let inputSpanDelete = document.createElement('span')
        inputSpanDelete.classList.add('delete-span')
        inputSpanDelete.innerHTML = ' x'
        inputSpanDelete.addEventListener("click", (event) => {
        
            deleteInput(event.target.previousSibling);
        })
        
        let searchElement = document.createElement('div')
        searchElement.setAttribute('class', 'search')
        
        let listElement = document.createElement('li')
        listElement.setAttribute('draggable', true)
        listElement.addEventListener('dragover', dragover)
        listElement.addEventListener('dragstart', dragstart)
        listElement.addEventListener('dragend', dragend)
        listElement.appendChild(input)
        listElement.appendChild(inputSpanDelete)
        listElement.appendChild(searchElement)
        
        document.querySelector("#inputs ul").appendChild(listElement)
    })
    
    document.querySelector("#show-route-button").addEventListener("click", () => {
                
        displayRoutes()
    })
    
    let searchInputs = document.querySelectorAll("#inputs input")
    
    for (let searchInput of searchInputs) {
        
        searchInputEvents(searchInput)
    }
    
    
    let deleteSpanElements = document.querySelectorAll(".delete-span")
    
    for(var i = 0; i < deleteSpanElements.length; i++) {
        
        deleteSpanElements[i].addEventListener("click", (event) => {
        
            deleteInput(event.target.previousSibling.previousSibling);
        })
    }
    
    
    let liElements = document.querySelectorAll("#inputs li")
    
    for(var i = 0; i < liElements.length; i++) {
        
        liElements[i].setAttribute('draggable', true)
        liElements[i].addEventListener('dragstart', dragstart)
        liElements[i].addEventListener('dragover', dragover)
        liElements[i].addEventListener('dragend', dragend)
    }
    
    
    let buttonElements = document.getElementById('buttons').children
            
    for(var i = 0; i < buttonElements.length; i++) {
        
        buttonElements[i].addEventListener("click", () => {
        
            closeConfigPanels()
            
            let clickedElementId = event.target.getAttribute('id')
            
            if (clickedElementId !== null && clickedElementId == 'add-input-button')
                closeConfig()
        })
    }
    
    
    let configButtons = document.getElementsByClassName('config-button')
    
    for(var i = 0; i < configButtons.length; i++) {
        
        configButtons[i].addEventListener("click", (event) => {
            
            let clickedElementHrefAttribute = event.target.getAttribute('href').substring(1)
            let clickedElementDescriptionElement = document.getElementById(clickedElementHrefAttribute)
            
            if(!clickedElementDescriptionElement.classList.contains('show'))
                closeConfigPanels()
        })
    }
    
    
    let configPanelButtonsElements = document.querySelectorAll("#config-panel-buttons a")
    
    for(var i = 0; i < configPanelButtonsElements.length; i++) {
        
        configPanelButtonsElements[i].addEventListener("click", (event) => {
            
            removeStyleFromClickedConfigButton()
            
            event.target.classList.add('config-button-clicked')
        })
    }
    
    
    let textInputElements = document.querySelectorAll("#vehicle-specification input[type='text']")
    
    for(var i = 0; i < textInputElements.length; i++) {
        
        textInputElements[i].addEventListener("keyup", (event) => {
            
            let targetInput = event.target
            let targetValue = targetInput.value
                          
            if (targetValue == '' || !isNaN(targetValue) && 
                Number(targetInput.getAttribute('min')) <= Number(targetValue) && Number(targetValue) <= Number(targetInput.getAttribute('max'))) {
                
                if (targetInput.classList.contains('input-warning'))
                    targetInput.classList.remove('input-warning')
                
            } else {
                
                targetInput.classList.add('input-warning')
            }
        })
    }
    
    
    function searchInputEvents(input) {
        
        input.addEventListener("keyup", (event) => {
            
            let element = event.target
            let value = element.value
            let searchElement = element.parentNode.children[element.parentNode.children.length-1]
            
            if (value.length > 2) {
                        
                let valueEncoded = encodeURIComponent(event.target.value)    
                let limit = 3

                return fetch(`${tomtomApiHref}/search/${searchVersionNumber}/geocode/${valueEncoded}.${ext}?key=${apiKey}&limit=${limit}&language=${lang}&countrySet=${countrySet}`, {
                    method: 'GET',
                    headers: {
                        'Content-type': 'application/json'
                    }
                })
                .then((res) => res.json())
                .then((data) => {     
                    
                    displaySearchHints(searchElement, ...data.results)
                });
                
            } else if (value.length == 0) {
                
                clearSearchHintElements(searchElement)
                
                if (element.getAttribute('data-lon') !== null && element.getAttribute('data-lat') !== null) {
                    
                    element.removeAttribute('data-lon')
                    element.removeAttribute('data-lat')
                }
                
                isFirstSearchedElement()
            }
        })
        
        
        input.addEventListener("focus", (event) => {
            
            event.target.setSelectionRange(0, event.target.value.length)
        })
    }
    
    function displaySearchHints(searchElement, ...results) {
        
        clearSearchHintElements(searchElement)
        
        for (let result of results) {
        
            let div = document.createElement('div')
            div.setAttribute('data-lat', result.position.lat)
            div.setAttribute('data-lon', result.position.lon)
            div.innerHTML = `
                ${result.address.freeformAddress}, ${result.address.country}
            `
            searchHintElementClickEvent(div)

            searchElement.appendChild(div)
        }
    }
    
    function clearSearchHintElements(element) {
        
        if (element.children.length > 0) {
            
            element.innerHTML = ''
        }
    }
        
    function searchHintElementClickEvent(element) {
        
        element.addEventListener("click", (event) => {
            
            let searchElement = event.target
            let searchElementParentDiv = searchElement.parentNode
            let searchElementParentLi = searchElementParentDiv.parentNode
            let inputElement = searchElementParentLi.firstElementChild
            
            searchElementLongitude = searchElement.getAttribute('data-lon')
            searchElementLatitude = searchElement.getAttribute('data-lat')
            
            inputElement.value = searchElement.innerText
            inputElement.setAttribute('data-lon', searchElementLongitude)
            inputElement.setAttribute('data-lat', searchElementLatitude)
            
            clearSearchHintElements(searchElementParentDiv)
            
            if (isFirstSearchedElement()) {
                
                map.setCenter(new tt.LngLat(searchElementLongitude, searchElementLatitude));
            }
        })
    }
    
    function isFirstSearchedElement() {
        
        let numberOfSelectedDestinations = 0
        let searchInputs = document.querySelectorAll("#inputs input")
        
        for (let searchInput of searchInputs) {
            
            if (searchInput.getAttribute('data-lon') !== null && searchInput.getAttribute('data-lat') !== null) {
                
                numberOfSelectedDestinations += 1
            }
        }
        
        checkWhetherMoreThanTwoLocationsAreChosen(numberOfSelectedDestinations)
        
        return numberOfSelectedDestinations > 1 ? false : true
    }
    
    function checkWhetherMoreThanTwoLocationsAreChosen(numberOfSelectedDestinations) {
        
        let showRouteButton = document.getElementById('show-route-button')
        
        if (numberOfSelectedDestinations >= 2) {
            
            showRouteButton.removeAttribute('disabled')
            
        } else {
            
            showRouteButton.setAttribute('disabled', true)
        }
    }    
    
    function displayRoutes() {
        
        let routes = getRoutes()
        
        tt.services.calculateRoute({
            batchMode: 'sync',
            key: apiKey,
            locations: routes
        })
            .go()
            .then(function (response) {
                
                // deletes route from map layer
                clearRouteIfDisplayed('route')
        
                // deletes each chosen localization marker from map (if already exist)
                deleteRouteWaypointMarkers()
                
                // displays markers for each chosen localization
                displayRouteWaypointMarkers(routes)
    
                var geojson = response.toGeoJson()
                map.addLayer({
                    'id': 'route',
                    'type': 'line',
                    'source': {
                        'type': 'geojson',
                        'data': geojson
                    },
                    'paint': {
                        'line-color': '#02d7ff',
                        'line-width': 6
                    }
                })
                
                var bounds = new tt.LngLatBounds()
                
                geojson.features[0].geometry.coordinates.forEach(function (point) {
                    bounds.extend(tt.LngLat.convert(point))
                })
                
                map.fitBounds(bounds, { padding: 20 })
            });
    } 
    
    function getRoutes() {
        
        let routes = ''
        let searchInputs = document.querySelectorAll("#inputs input")
        
        for (let searchInput of searchInputs) {
            
            let inputLon = searchInput.getAttribute('data-lon')
            let inputLat = searchInput.getAttribute('data-lat')
            
            if (inputLon !== null && inputLat !== null) {
                
                let cordinates = `${inputLon},${inputLat}`
                
                if (routes == '') {
                    
                    routes = cordinates
                    
                } else {
                    
                    routes += ':' + cordinates
                }
            }
        }
        
        return routes
    }
    
    function getRoutes() {
        
        let routes = ''
        let searchInputs = document.querySelectorAll("#inputs input")
        
        for (let searchInput of searchInputs) {
            
            let inputLon = searchInput.getAttribute('data-lon')
            let inputLat = searchInput.getAttribute('data-lat')
            
            if (inputLon !== null && inputLat !== null) {
                
                let cordinates = `${inputLon},${inputLat}`
                
                if (routes == '') {
                    
                    routes = cordinates
                    
                } else {
                    
                    routes += ':' + cordinates
                }
            }
        }
        
        return routes
    }
    
    function displayRouteWaypointMarkers(routes) {
        
        let routesObject = getRoutesObject(routes)
        
        routesObject.forEach((routeObject, index) => {
            
            let waypointMarker = createWaypointMarker([routeObject['lon'], routeObject['lat']], index + 1)
            
            waypointMarkersArray.push(waypointMarker)
        })
    }
    
    function deleteRouteWaypointMarkers() {
        
        if (waypointMarkersArray.length > 0) {
            
            waypointMarkersArray.forEach((waypointMarker) => {

                waypointMarker.remove()
            })

            waypointMarkersArray = []
        }
    }
    
    function getRoutesObject(routes) {
        
        let routesSplited = routes.split(':')
        let cordinates = []
        
        routesSplited.forEach((route) => {
            
            let cordinate = route.split(',')
            
            cordinates.push({
                lon: cordinate[0],
                lat: cordinate[1]
            })
        })
        
        return cordinates
    }

    function createWaypointMarker(markerCoordinates, index) {
        
        const waypointMarkerElement = document.createElement('div')
        waypointMarkerElement.innerHTML = `<div class='route-waypoint-pointer'>${index}</div>`
        
        return new tt.Marker({element: waypointMarkerElement}).setLngLat(markerCoordinates).addTo(map)
    }
    
    function clearRouteIfDisplayed(routeName) {
        
        if (map.getLayer(routeName) !== undefined) {

            map.removeLayer(routeName)
            map.removeSource(routeName)
        }
    }
    
    function deleteInput(inputElement) {
        
        let spanElements = document.querySelectorAll(".delete-span")
        
        if (spanElements.length > 1) {
            
            inputElement.parentNode.remove()
        }
    }
    
    function dragstart() {
        
        this.classList.add('dragged')
    }
    
    function dragover(event) {
        
        event.preventDefault()
        
        let draggedElement = document.getElementsByClassName('dragged')[0]
        
        if (draggedElement.children[0] !== this.children[0]) {
            
            let draggedElementKey = 0;
            let hoveredElementKey = 0;
            
            let liElements = document.querySelectorAll("#inputs li")
        
            for(let i = 0; i < liElements.length; i++) {

                if (liElements[i] == draggedElement)
                {
                    draggedElementKey = i
                    
                } else if (liElements[i] == this) {
                    
                    hoveredElementKey = i
                }
            }
            
            let ulElement = document.querySelector("#inputs ul")
            
            if (draggedElementKey > hoveredElementKey)
            {
                ulElement.insertBefore(draggedElement, this)
                
            } else if (hoveredElementKey > draggedElementKey) {
                
                ulElement.insertBefore(this, draggedElement)
            }
        }
    }
    
    function dragend() {
        
        let liElements = document.querySelectorAll("#inputs li")
        
        for(var i = 0; i < liElements.length; i++) {
        
            if (liElements[i].classList.contains('dragged'))
            {
                liElements[i].classList.remove('dragged')
            }
        }
    }
    
    function closeConfigPanels() {
        
        let config = document.getElementById('config-panel')
        let configPanels = config.getElementsByClassName('collapse')

        for (var i = 0; i < configPanels.length; i++) {
            
            if (configPanels[i].classList.contains('show'))
                configPanels[i].classList.remove('show')
        }
        
        removeStyleFromClickedConfigButton()
    }
    
    function closeConfig() {
        
        let config = document.getElementById('collapseConfig')
        
        if (config.classList.contains('show'))
            config.classList.remove('show')
        
        removeStyleFromClickedConfigButton()
    }
    
    function removeStyleFromClickedConfigButton() {
        
        let clickedConfigPanelButton = document.querySelector(".config-button-clicked")
            
        if (clickedConfigPanelButton !== null)
            clickedConfigPanelButton.classList.remove('config-button-clicked')
    }
    
    function setValuesToInput(...names) {
        
        for (let name of names) {
            
            let element = document.querySelector(`input[name='${name}']`)
            let value = Number(element.value)
            
            if (value !== '' && !isNaN(value)) {
            
                let elementMinValue = Number(element.getAttribute('min'))
                let elementMaxValue = Number(element.getAttribute('max'))

                if (elementMinValue <= value && value <= elementMaxValue) {

                    element.value = value
                    Cookies.set(`${name}-map`, value)

                } else if (elementMinValue > value) {

                    element.value = elementMinValue
                    Cookies.set(`${name}-map`, elementMinValue)

                } else if (elementMaxValue < value) {

                    element.value = elementMaxValue
                    Cookies.set(`${name}-map`, elementMaxValue)
                }
                
                if (element.classList.contains('input-warning'))
                    element.classList.remove('input-warning')
            }
        }
    }
    
    function setValuesToCheckbox(...names) {
        
        for (let name of names) {
            
            let value = document.querySelector(`input[name='${name}']`).checked
            Cookies.set(`${name}-map`, value)
        }
    }
    
    function putValuesToInputOnRefresh(...names) {
        
        for (let name of names) {
            
            let input = document.querySelector(`input[name='${name}']`)
                    
            if (Cookies.get(`${name}-map`) !== "" && Cookies.get(`${name}-map`) !== undefined) {
                
                input.value = Cookies.get(`${name}-map`)
            }
        }
    }
    
    function putValuesToCheckboxOnRefresh(...names) {
        
        for (let name of names) {
            
            let mapCookie = Cookies.get(`${name}-map`)
            let inputElement = document.querySelector(`input[name='${name}']`)

            if (mapCookie === undefined || mapCookie == 'false') {

                inputElement.removeAttribute("checked")

            } else if (mapCookie == 'true') {

                inputElement.setAttribute('checked', mapCookie)
            }
        }
    }
    
    // >>>>>>> cookies handler
    
    // >>> bypassing coockies
    document.getElementById('bypassing').addEventListener("submit", (event) => {
        
        event.preventDefault()
        
        setValuesToCheckbox(...[
            'highway',
            'dirt-road',
            'toll-road',
            'roads-for-vehicles-with-passengers',
            'ferry'
        ])
    })
    
    
    putValuesToCheckboxOnRefresh(...[
        'highway',
        'dirt-road',
        'toll-road',
        'roads-for-vehicles-with-passengers',
        'ferry'
    ])
    // <<< bypassing coockies
    
    
    // >>> vehicle-specification
    document.getElementById('vehicle-specification').addEventListener("submit", (event) => {
        
        event.preventDefault()
        
        setValuesToInput(...[
            'truck-length',
            'truck-width',
            'truck-height',
            'truck-weight',
            'truck-axle-pressure',
            'truck-max-speed'
        ])
        
        setValuesToCheckbox(...[
            'explosives',
            'other-hazardous-materials',
            'water-contamination'
        ])
    })
    
    
    putValuesToInputOnRefresh(...[
        'truck-length',
        'truck-width',
        'truck-height',
        'truck-weight',
        'truck-axle-pressure',
        'truck-max-speed'
    ])
    
    putValuesToCheckboxOnRefresh(...[
        'explosives',
        'other-hazardous-materials',
        'water-contamination'
    ])
    
    // <<< vehicle-specification

    // <<<<<< cookies handler
})();
