(function() {
    
    $('.timepicker').timepicker({
        timeFormat: 'H:i',
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });
    
    document.cookie = 'cross-site-cookie=bar; SameSite=None; Secure';
    
    const tomtomApiHref = 'https://api.tomtom.com'
    const searchVersionNumber = 2
    const ext = 'json'
    const apiKey = 'OmjUSjU5i4johYgNQfvhLGWqzbCmdZke'
    const lang = 'pl-PL'
    const countrySet = 'ESP,PRT,IRL,GBR,AND,FRA,MLT,MCO,ITA,VAT,CHE,AUT,LIE,DEU,LUX,BEL,NLD,DNK,NOR,SWE,FIN,EST,LVA,LTU,POL,BLR,UKR,CZE,SVK,SVN,HUN,HRV,BIH,SRB,ROU,MDA,MNE,ALB,MKD,BGR,GRC,TUR,RUS'
    
    const departAtParameterNames = [
        'travel-start-date',
        'travel-start-time'
    ]
    
    const arriveAtParameterNames = [
        'travel-end-date',
        'travel-end-time'
    ]
    
    const avoidParameterNames = [
        'motorways',
        'tollRoads',
        'unpavedRoads',
        'carpools',
        'ferries'
    ]
    
    const vehicleAdrTunnelRestrictionCode = [
        'a',
        'b',
        'c',
        'd'
    ]
    
    const vehicleParameterNames = [
        'vehicleLength',
        'vehicleWidth',
        'vehicleHeight',
        'vehicleWeight',
        'vehicleAxleWeight',
        'vehicleMaxSpeed'
    ]
    
    const vehicleLoadTypeParameterNames = [
        'otherHazmatExplosive',
        'otherHazmatGeneral',
        'otherHazmatHarmfulToWater'
    ]
    
    let waypointMarkersArray = []
        
    let calculateRouteRequestParameterWithArguments = {
        avoid: avoidParameterNames,
        vehicleAdrTunnelRestrictionCode: vehicleAdrTunnelRestrictionCode,
        vehicleLoadType: vehicleLoadTypeParameterNames,
        // if more arrays need to be added here, create function to concatenate them (and put to assignByKey)
        assignByKey: vehicleParameterNames
    }

    var map = tt.map({
        key: apiKey,
        container: 'map',
        style: 'tomtom://vector/1/basic-main',
        center: [21.017532,52.237049],
        zoom: 4,
        language: lang
    });     
    
    map.addControl(new tt.FullscreenControl());
    map.addControl(new tt.NavigationControl());
    
    
    focusOnFirstInputElementWhenPageRefresh()
    
    setHighlightEventToTimeInputsWhenClicked()
    
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
        
        input.focus()
    })
    
    document.querySelector("#show-route-button").addEventListener("click", () => {
        
        closeConfig()
                
        displayRoutes()
    })
    
    let adrTunnelRestrictionCheckboxes = document.querySelectorAll("#vehicleAdrTunnelRestrictionCode input[type=checkbox]")
    
    for (let adrTunnelRestrictionCheckbox of adrTunnelRestrictionCheckboxes) {
        
        adrTunnelRestrictionCheckbox.addEventListener("click", (event) => {
                
            uncheckOthersAdrTunnelRestrictionCheckboxesIfChecked(event.target.getAttribute("name"))
        })
    }
    
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
                
                map.setCenter(new tt.LngLat(searchElementLongitude, searchElementLatitude))
                map.setZoom(8)
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
    
    function setHighlightEventToTimeInputsWhenClicked() {
        
        let timeInputs = document.querySelectorAll('.timepicker')
        
        for (let timeInput of timeInputs) {
            
            timeInput.addEventListener("click", (event) => {

                event.target.setSelectionRange(0, event.target.value.length)
            })
        }
    }
    
    function displayRoutes() {
        
        let routes = getRoutes()
        
        let params = {
            key: apiKey,
            locations: routes
        }
        
        params = setAdditionalParametersToParamsArray(params)
        
        tt.services.calculateRoute(params)
            .go()
            .then(function (response) {
                
                // deletes route from map layer
                clearRouteIfDisplayed('route')
        
                // deletes each chosen localization marker from map (if already exist)
                deleteRouteWaypointMarkers()
                
                // displays markers for each chosen localization
                displayRouteWaypointMarkers(routes)
    
                // displays route info panel and fill it with info
                displayRouteInfoPanel(response, params)
                
                // display date time panels
                displayDepartAtInfoPanel()
                displayArriveAtInfoPanel()
                
                map.addLayer({
                    'id': 'route',
                    'type': 'line',
                    'source': {
                        'type': 'geojson',
                        'data': response.toGeoJson()
                    },
                    'paint': {
                        'line-color': '#02d7ff',
                        'line-width': 6
                    }
                })
                
                map.fitBounds(parseRouteStringToCords(routes), {
                    padding: { top: 30, bottom: 30, left: 30, right: 30 },
                    maxZoom: 5
                });
            });
    }  
    
    function displayRouteInfoPanel(response, params) {
        
        let routeDuration = getRouteDurationInHours(response)
        let routeLength = getRouteLengthInKilometers(response)
        
        let routeDurationIndicatorElement = document.getElementById("route-duration-indicator")
        let routeLengthIndicatorElement = document.getElementById("route-length-indicator")
        
        routeDurationIndicatorElement.innerHTML = ''
        routeDurationIndicatorElement.innerHTML = routeDuration
        
        routeLengthIndicatorElement.innerHTML = ''
        routeLengthIndicatorElement.innerHTML = routeLength
        
        displayArrivalOrDepartureInfo(response, params)
        
        document.getElementById("route-info-panel").setAttribute("style", "visibility: visible;")
    }
    
    function displayArrivalOrDepartureInfo(response, params) {
        
        let date = null
        let arrivalOrDepartureInfoElement = document.getElementById('estimated-departure-or-arrival')
        arrivalOrDepartureInfoElement.innerHTML = ''
        
        if (params['arriveAt']) {
            
            let labelElement = document.createElement('label')
            labelElement.innerHTML = 'Czas wyjazdu:'
            
            date = new Date(response['routes'][0]['summary']['departureTime']) 
            
            let strongElement = document.createElement('strong')
            strongElement.innerHTML = `
                ${date.getDate()} ${parseMonth(date.getMonth())} ${date.getFullYear()} 
                ${parseTime(date.getHours(), date.getMinutes())}
            `
            
            arrivalOrDepartureInfoElement.prepend(strongElement)
            arrivalOrDepartureInfoElement.prepend(labelElement)
            
            arrivalOrDepartureInfoElement.setAttribute("style", "display: inline;")
            
        } else if (params['departAt']) {
            
            let labelElement = document.createElement('label')
            labelElement.innerHTML = 'Czas dojazdu:'
            
            date = new Date(response['routes'][0]['summary']['arrivalTime'])
            
            let strongElement = document.createElement('strong')
            strongElement.innerHTML = `
                ${date.getDate()} ${parseMonth(date.getMonth())} ${date.getFullYear()} 
                ${parseTime(date.getHours(), date.getMinutes())}
            `
            
            arrivalOrDepartureInfoElement.prepend(strongElement)
            arrivalOrDepartureInfoElement.prepend(labelElement)
            
            arrivalOrDepartureInfoElement.setAttribute("style", "display: inline;")
            
        } else {
            
            arrivalOrDepartureInfoElement.setAttribute("style", "display: none;")
        }
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
    
    function parseRouteStringToCords(routes) {
        
        var routesSplited = routes.split(":")
        var routesCords = []
        
        routesSplited.forEach((route) => {
            
            var routeLngLat = route.split(",")   
            
            routesCords.push([
                Number(routeLngLat[0]),
                Number(routeLngLat[1])
            ])
        })
        
        return routesCords
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
            
            // hide route-info-panel
            document.getElementById("route-info-panel").setAttribute("style", "visibility: hidden;")
        }
    }
    
    function setAdditionalParametersToParamsArray(params) {
        
        // >> fill array with single value parameters
        for(let parameter in calculateRouteRequestParameterWithArguments) {
            
            if (calculateRouteRequestParameterWithArguments[parameter] !== undefined) {
                
                calculateRouteRequestParameterWithArguments[parameter].forEach((par) => {
                    
                    let paramCookie = Cookies.get(`${par}-map`)

                    if (paramCookie !== 'false' && paramCookie != 0 && paramCookie !== undefined) {
                        
                        if (parameter === "assignByKey") {
                            
                            // przypisz po kluczu
                            params[par] = paramCookie
                            
                        } else {
 
                            // sprawdz czy już istnieje rząd o danej nazwie, 
                            if (params[parameter] === undefined) {
                                
                                params[parameter] = par
                                
                            } else {
                                
                                params[parameter] = `${params[parameter]},${par}` 
                            }
                        }
                    }
                })
            }
        }
        
        // >> fill array with datetime parameters
        params = setDateTimeParametersToParamsArr(params)
        
        return params
    }
    
    function getRouteDurationInHours(response) {
        
        let totalTime = 0
        
        if (response['routes'][0]['summary']['travelTimeInSeconds']) {
        
            let routeDurationInMinutes = response['routes'][0]['summary']['travelTimeInSeconds'] / 60
            let routeDurationDividedByMinutesInHour = routeDurationInMinutes / 60   

            let numberOfHours = routeDurationDividedByMinutesInHour.toString().split(".")[0]
            let numberOfMinutesLeftAfterHoursSubtraction = (routeDurationInMinutes % 60).toString().split(".")[0]


            if (numberOfHours != 0)
                totalTime = `${numberOfHours}h`

            if (numberOfMinutesLeftAfterHoursSubtraction != 0)
                totalTime = `${totalTime} ${numberOfMinutesLeftAfterHoursSubtraction}min`
        }
        
        return totalTime
    }
    
    function getRouteLengthInKilometers(response) {
        
        let routeLengthInKilometers = 0
        
        if (response['routes'][0]['summary']['lengthInMeters']) {
            
            routeLengthInKilometers = response['routes'][0]['summary']['lengthInMeters'] / 1000
        }
        
        return `${routeLengthInKilometers}km`
    }
    
    function focusOnFirstInputElementWhenPageRefresh() {
        
        let firstInput = document.querySelector("#inputs input:first-child")
        
        firstInput.focus()
    }
    
    function uncheckOthersAdrTunnelRestrictionCheckboxesIfChecked(checkboxName) {
        
        vehicleAdrTunnelRestrictionCode.forEach((element) => {
            
            if (element != checkboxName) {
                
                let AdrTunnelRestrictionCheckboxElement = document.querySelector(`input[name=${element}]`)
                
                if (AdrTunnelRestrictionCheckboxElement.checked)
                    AdrTunnelRestrictionCheckboxElement.checked = false
            }
        })
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
    
    function setValuesToDateAndTimeInput(...names) {
        
        for (let name of names) {
            
            let element = document.querySelector(`input[name='${name}']`)
            let value = element.value
            
            if (value !== '' || value != 0 || value !== undefined) {
            
                Cookies.set(`${name}-map`, value)
            }
        }
        
        displayDepartAtInfoPanel()
        displayArriveAtInfoPanel()
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
    
    function clearCookiesAndRepresentingInputsByName(antagonicPanelName, ...names) {
        
        if (document.getElementById(antagonicPanelName)) {
        
            for (let name of names) {

                let cookieName = `${name}-map`
                let inputElement = document.querySelector(`input[name='${name}']`)

                if (Cookies.get(cookieName)) {

                    Cookies.set(cookieName, '')
                    inputElement.value = ''
                }
            }
            
            clearIndicatorPanelElementIfExists(antagonicPanelName)
        }
    }
    
    // >>>>>>> cookies handler
    
    // >>> travel start date time cookies
    document.getElementById('departAt').addEventListener("submit", (event) => {
        
        event.preventDefault()
        
        setValuesToDateAndTimeInput(...departAtParameterNames)
        
        // remove antagonic datetime cookies
        clearCookiesAndRepresentingInputsByName('arrive-at-indicator', ...arriveAtParameterNames)
    })
    
    putValuesToInputOnRefresh(...departAtParameterNames)
    // <<< travel start date time cookies
 
    // >>> travel end date time cookies
    document.getElementById('arriveAt').addEventListener("submit", (event) => {
        
        event.preventDefault()
        
        setValuesToDateAndTimeInput(...arriveAtParameterNames)
        
        // remove antagonic datetime cookies
        clearCookiesAndRepresentingInputsByName('depart-at-indicator', ...departAtParameterNames)
    })
    
    putValuesToInputOnRefresh(...arriveAtParameterNames)
    // <<< travel end date time cookies
    
    // >>> bypassing cookies
    document.getElementById('bypassing').addEventListener("submit", (event) => {
        
        event.preventDefault()
        
        setValuesToCheckbox(...avoidParameterNames)
        setValuesToCheckbox(...vehicleAdrTunnelRestrictionCode)
    })
    
    
    putValuesToCheckboxOnRefresh(...avoidParameterNames)
    putValuesToCheckboxOnRefresh(...vehicleAdrTunnelRestrictionCode)
    // <<< bypassing cookies
    
    
    // >>> vehicle-specification
    document.getElementById('vehicle-specification').addEventListener("submit", (event) => {
        
        event.preventDefault()
        
        setValuesToInput(...vehicleParameterNames)
        
        setValuesToCheckbox(...vehicleLoadTypeParameterNames)
    })
    
    
    putValuesToInputOnRefresh(...vehicleParameterNames)
    
    putValuesToCheckboxOnRefresh(...vehicleLoadTypeParameterNames)
    
    // <<< vehicle-specification

    // <<<<<< cookies handler
    
    
    displayDepartAtInfoPanel()
    displayArriveAtInfoPanel()
    
    function setDateTimeParametersToParamsArr(params) {
        
        let departAt = getDepartAt()
        
        // add departAt datetime parameter to request params array
        if (departAt !== null)
            params['departAt'] = departAt.toISOString()
        
        let arriveAt = getArriveAt()
        
        // add arriveAt datetime parameter to request params array
        if (arriveAt !== null)
            params['arriveAt'] = arriveAt.toISOString()
         
        return params
    }
    
    function displayDepartAtInfoPanel() {
        
        let departAtDateTime = getDepartAt()
        
        if (departAtDateTime !== null) {
            
            // clear existing panel if already exist
            clearIndicatorPanelElementIfExists('depart-at-indicator')
            
            let departAtIndicator = document.createElement('div')
            departAtIndicator.setAttribute('id', 'depart-at-indicator')
            departAtIndicator.setAttribute('class', 'text-center')

            let departAtLabel = document.createElement('label')
            departAtLabel.innerHTML = 'Wyjazd o:'

            let departAtInfo = document.createElement('strong')
            departAtInfo.innerHTML = `
                ${departAtDateTime.getDate()} ${parseMonth(departAtDateTime.getMonth())} ${departAtDateTime.getFullYear()} 
                ${parseTime(departAtDateTime.getHours(), departAtDateTime.getMinutes())}
            `
            
            departAtIndicator.appendChild(departAtLabel)
            departAtIndicator.appendChild(departAtInfo)
            
            let searchElement = document.getElementById('search')
            searchElement.prepend(departAtIndicator)
        }
    }
    
    function displayArriveAtInfoPanel() {
        
        let arriveAtDateTime = getArriveAt()
        
        if (arriveAtDateTime !== null) {
            
            // clear existing panel if already exist
            clearIndicatorPanelElementIfExists('arrive-at-indicator')
            
            let arriveAtIndicator = document.createElement('div')
            arriveAtIndicator.setAttribute('id', 'arrive-at-indicator')
            arriveAtIndicator.setAttribute('class', 'text-center')

            let arriveAtLabel = document.createElement('label')
            arriveAtLabel.innerHTML = 'Dojazd o:'

            let arriveAtInfo = document.createElement('strong')
            arriveAtInfo.innerHTML = `
                ${arriveAtDateTime.getDate()} ${parseMonth(arriveAtDateTime.getMonth())} ${arriveAtDateTime.getFullYear()} 
                ${parseTime(arriveAtDateTime.getHours(), arriveAtDateTime.getMinutes())}
            `
            
            arriveAtIndicator.appendChild(arriveAtLabel)
            arriveAtIndicator.appendChild(arriveAtInfo)
            
            let searchElement = document.getElementById('search')
            searchElement.prepend(arriveAtIndicator)
        }
    }
    
    function clearIndicatorPanelElementIfExists(indicatorName) {
        
        let panelElement = document.getElementById(indicatorName)

        if (panelElement !== null) {

            panelElement.parentNode.removeChild(panelElement)
        }
    }
    
    function getDepartAt() {
        
        let departAt = null
        
        // if date value exists, create date object
        let departAtTravelStartDateElementValue = document.getElementById('travel-start-date').value
        
        if (departAtTravelStartDateElementValue) {
            
            departAtDateArr = departAtTravelStartDateElementValue.split("-")
            
            departAt = new Date(departAtDateArr[0], departAtDateArr[1] - 1, departAtDateArr[2])
            
            // if time value also exists, add it to date object
            let departAtTravelStartTimeElementValue = document.getElementById('travel-start-time').value
            
            if (departAtTravelStartTimeElementValue) {
                
                departAtTimeArr = departAtTravelStartTimeElementValue.split(":")
                
                departAt.setHours(departAtTimeArr[0], departAtTimeArr[1])
            }
        }
        
        return departAt
    }
    
    function getArriveAt() {
        
        let arriveAt = null
        
        // if date value exists, create date object
        let arriveAtTravelEndDateElementValue = document.getElementById('travel-end-date').value
        
        if (arriveAtTravelEndDateElementValue) {
            
            arriveAtDateArr = arriveAtTravelEndDateElementValue.split("-")
            
            arriveAt = new Date(arriveAtDateArr[0], arriveAtDateArr[1] - 1, arriveAtDateArr[2])
            
            // if time value also exists, add it to date object
            let arriveAtTravelEndTimeElementValue = document.getElementById('travel-end-time').value
            
            if (arriveAtTravelEndTimeElementValue) {
                
                arriveAtTimeArr = arriveAtTravelEndTimeElementValue.split(":")
                
                arriveAt.setHours(arriveAtTimeArr[0], arriveAtTimeArr[1])
            }
        }
        
        return arriveAt
    }
    
    function parseMonth(monthNumber) {
        
        let month = ''
        
        switch (monthNumber) {
            case 0:
              month = 'Styczeń'
              break;
            case 1:
              month = 'Luty'
              break;
            case 2:
              month = 'Marzec'
              break;
            case 3:
              month = 'Kwiecień'
              break;
            case 4:
              month = 'Maj'
              break;
            case 5:
              month = 'Czerwiec'
              break;
            case 6:
              month = 'Lipiec'
              break;
            case 7:
              month = 'Sierpień'
              break;
            case 8:
              month = 'Wrzesień'
              break;
            case 9:
              month = 'Pażdziernik'
              break;
            case 10:
              month = 'Listopad'
              break;
            case 11:
              month = 'Grudzień'
              break;
        }
        
        return month
    }
    
    function parseTime(hours, minutes) {
        
        let hoursString = hours.toString()
        let minutesString = minutes.toString()
        
        if (hoursString.length == 1)
            hoursString = 0 + hoursString
        
        if (minutesString.length == 1)
            minutesString = minutesString + 0
        
        return `${hoursString}:${minutesString}`
    }
})();
