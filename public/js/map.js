(function() 
{
    document.cookie = 'cross-site-cookie=bar; SameSite=None; Secure';

//    var map = tt.map({
//        key: 'OmjUSjU5i4johYgNQfvhLGWqzbCmdZke',
//        container: 'map',
//        style: 'tomtom://vector/1/basic-main',
//        center: [21.017532,52.237049],
//        zoom: 8
//    });        
//
//    //var config = {
//    //    key: 'OmjUSjU5i4johYgNQfvhLGWqzbCmdZke',
//    //    style: 'tomtom://vector/1/relative',
//    //    refresh: 30000
//    //};
//    //
//    //map.on('load', function() {
//    //    map.addTier(new tt.TrafficFlowTilesTier(config));
//    //});
//
//    //map.addControl(new tt.FullscreenControl());
//
//    map.addControl(new tt.NavigationControl());
    
    
    document.querySelector("#add-input-button").addEventListener("click", () => {
                
        let input = document.createElement('input')
        input.setAttribute('type', 'text')
        
        let inputSpanDelete = document.createElement('span')
        inputSpanDelete.classList.add('delete-span')
        inputSpanDelete.innerHTML = ' x'
        inputSpanDelete.addEventListener("click", (event) => {
        
            deleteInput(event.target.previousSibling);
        })
        
        let listElement = document.createElement('li')
        listElement.setAttribute('draggable', true)
        listElement.addEventListener('dragover', dragover)
        listElement.addEventListener('dragstart', dragstart)
        listElement.addEventListener('dragend', dragend)
        listElement.appendChild(input)
        listElement.appendChild(inputSpanDelete)
        
        document.querySelector("#inputs ul").appendChild(listElement)
    })
    
    
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

    
    function deleteInput(inputElement) {
        
        let spanElements = document.querySelectorAll(".delete-span")
        
        if (spanElements.length > 2) {
            
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
    
    
    // >>>>>> input validators
    
    // >>> bypassing validators
    
    
    // <<< bypassing validators
    
    // <<<<<< input validators
    
    
    // >>>>>>> cookies handler
    
    // >>> bypassing coockies
    document.getElementById('bypassing').addEventListener("submit", (event) => {
        
        event.preventDefault()
        
        let highwayValue = document.querySelector("input[name='highway']").checked
        Cookies.set('highway-map', highwayValue)
        
        let dirtRoadValue = document.querySelector("input[name='dirt-road']").checked
        Cookies.set('dirt-road-map', dirtRoadValue)
        
        let tollRoadValue = document.querySelector("input[name='toll-road']").checked
        Cookies.set('toll-road-map', tollRoadValue)
        
        let roadsForVehiclesWithPassengersValue = document.querySelector("input[name='roads-for-vehicles-with-passengers']").checked
        Cookies.set('roads-for-vehicles-with-passengers-map', roadsForVehiclesWithPassengersValue)
        
        let ferryValue = document.querySelector("input[name='ferry']").checked
        Cookies.set('ferry-map', ferryValue)
    })
    
    
    let highwayMapCookie = Cookies.get('highway-map')
    let highwayInputElement = document.querySelector("input[name='highway']")
    
    if (highwayMapCookie === undefined || highwayMapCookie == 'false') {
        
        highwayInputElement.removeAttribute("checked")
        
    } else if (highwayMapCookie == 'true') {
        
        highwayInputElement.setAttribute('checked', highwayMapCookie)
    }
    
    
    let dirtRoadMapCookie = Cookies.get('dirt-road-map')
    let dirtRoadInputElement = document.querySelector("input[name='dirt-road']")
    
    if (dirtRoadMapCookie === undefined || dirtRoadMapCookie == 'false') {
        
        dirtRoadInputElement.removeAttribute("checked")
        
    } else if (dirtRoadMapCookie == 'true') {
        
        dirtRoadInputElement.setAttribute('checked', dirtRoadMapCookie)
    }
    
    
    let tollRoadMapCookie = Cookies.get('toll-road-map')
    let tollRoadInputElement = document.querySelector("input[name='toll-road']")
    
    if (tollRoadMapCookie === undefined || tollRoadMapCookie == 'false') {
        
        tollRoadInputElement.removeAttribute("checked")
        
    } else if (tollRoadMapCookie == 'true') {
        
        tollRoadInputElement.setAttribute('checked', tollRoadMapCookie)
    }
    
    
    let roadsForVehiclesWithPassengersMapCookie = Cookies.get('roads-for-vehicles-with-passengers-map')
    let roadsForVehiclesWithPassengersInputElement = document.querySelector("input[name='roads-for-vehicles-with-passengers']")
    
    if (roadsForVehiclesWithPassengersMapCookie === undefined || roadsForVehiclesWithPassengersMapCookie == 'false') {
        
        roadsForVehiclesWithPassengersInputElement.removeAttribute("checked")
        
    } else if (roadsForVehiclesWithPassengersMapCookie == 'true') {
        
        roadsForVehiclesWithPassengersInputElement.setAttribute('checked', roadsForVehiclesWithPassengersMapCookie)
    }
    
    
    let ferryMapCookie = Cookies.get('ferry-map')
    let ferryInputElement = document.querySelector("input[name='ferry']")
    
    if (ferryMapCookie === undefined || ferryMapCookie == 'false') {
        
        ferryInputElement.removeAttribute("checked")
        
    } else if (ferryMapCookie == 'true') {
        
        ferryInputElement.setAttribute('checked', ferryMapCookie)
    }
    // <<< bypassing coockies
    
    
    // >>> vehicle-specification
    document.getElementById('vehicle-specification').addEventListener("submit", (event) => {
        
        event.preventDefault()
        
        let truckLengthValue = document.querySelector("input[name='truck-length']").value
        Cookies.set('truck-length-map', truckLengthValue)
        
        let truckWidthValue = document.querySelector("input[name='truck-width']").value
        Cookies.set('truck-width-map', truckWidthValue)
        
        let truckHeightValue = document.querySelector("input[name='truck-height']").value
        Cookies.set('truck-height-map', truckHeightValue)
        
        let truckWeightValue = document.querySelector("input[name='truck-weight']").value
        Cookies.set('truck-weight-map', truckWeightValue)
        
        let truckAxlePressureValue = document.querySelector("input[name='truck-axle-pressure']").value
        Cookies.set('truck-axle-pressure-map', truckAxlePressureValue)
        
        let truckMaxSpeedValue = document.querySelector("input[name='truck-max-speed']").value
        Cookies.set('truck-max-speed-map', truckMaxSpeedValue)
        
        let explosivesValue = document.querySelector("input[name='explosives']").checked
        Cookies.set('explosives-map', explosivesValue)
        
        let otherHazardousMaterialsValue = document.querySelector("input[name='other-hazardous-materials']").checked
        Cookies.set('other-hazardous-materials-map', otherHazardousMaterialsValue)
        
        let waterContaminationValue = document.querySelector("input[name='water-contamination']").checked
        Cookies.set('water-contamination-map', waterContaminationValue)
    })
    
    
    document.querySelector("input[name='truck-length']").value = Cookies.get('truck-length-map') !== "" ? Cookies.get('truck-length-map') : ''
    
    document.querySelector("input[name='truck-width']").value = Cookies.get('truck-width-map') !== "" ? Cookies.get('truck-width-map') : ''
    
    document.querySelector("input[name='truck-height']").value = Cookies.get('truck-height-map') !== "" ? Cookies.get('truck-height-map') : ''

    document.querySelector("input[name='truck-weight']").value = Cookies.get('truck-weight-map') !== "" ? Cookies.get('truck-weight-map') : ''
    
    document.querySelector("input[name='truck-axle-pressure']").value = Cookies.get('truck-axle-pressure-map') !== "" ? Cookies.get('truck-axle-pressure-map') : ''
    
    document.querySelector("input[name='truck-max-speed']").value = Cookies.get('truck-max-speed-map') !== "" ? Cookies.get('truck-max-speed-map') : ''
    
    
    let explosivesMapCookie = Cookies.get('explosives-map')
    let explosivesInputElement = document.querySelector("input[name='explosives']")
    
    if (explosivesMapCookie === undefined || explosivesMapCookie == 'false') {
        
        explosivesInputElement.removeAttribute("checked")
        
    } else if (explosivesMapCookie == 'true') {
        
        explosivesInputElement.setAttribute('checked', explosivesMapCookie)
    }
    
    
    let otherHazardousMaterialsMapCookie = Cookies.get('other-hazardous-materials-map')
    let otherHazardousMaterialsInputElement = document.querySelector("input[name='other-hazardous-materials']")
    
    if (otherHazardousMaterialsMapCookie === undefined || otherHazardousMaterialsMapCookie == 'false') {
        
        otherHazardousMaterialsInputElement.removeAttribute("checked")
        
    } else if (otherHazardousMaterialsMapCookie == 'true') {
        
        otherHazardousMaterialsInputElement.setAttribute('checked', otherHazardousMaterialsMapCookie)
    }
    
    
    let waterContaminationMapCookie = Cookies.get('water-contamination-map')
    let waterContaminationInputElement = document.querySelector("input[name='water-contamination']")
    
    if (waterContaminationMapCookie === undefined || waterContaminationMapCookie == 'false') {
        
        waterContaminationInputElement.removeAttribute("checked")
        
    } else if (waterContaminationMapCookie == 'true') {
        
        waterContaminationInputElement.setAttribute('checked', waterContaminationMapCookie)
    }
    // <<< vehicle-specification

    // <<<<<< cookies handler
})();






