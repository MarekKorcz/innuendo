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
    
    
    document.querySelector("#add-input-button a").addEventListener("click", () => {
                
        let input = document.createElement('input')
        input.setAttribute('type', 'text')
        
        let inputSpanDelete = document.createElement('span')
        inputSpanDelete.classList.add('delete-span')
        inputSpanDelete.innerHTML = ' x'
        inputSpanDelete.addEventListener("click", (event) => {
        
            deleteInput(event.target.previousSibling);
        })
        
        let listElement = document.createElement('li')
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
    
    function deleteInput(inputElement) {
        
        let spanElements = document.querySelectorAll(".delete-span")
        
        if (spanElements.length > 2)
        {
            inputElement.parentNode.remove()
        }
    }
})();






