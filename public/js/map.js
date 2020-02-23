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
    }
    
    function closeConfig() {
        
        let config = document.getElementById('collapseConfig')
        
        if (config.classList.contains('show'))
            config.classList.remove('show')
    }
})();






