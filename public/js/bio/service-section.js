document.addEventListener("DOMContentLoaded", function() {
    
    document.querySelector(".button a").addEventListener("click",function(){
        
        // element which needs to be scrolled to
        let element = document.querySelector("#service-description");
    
        // scroll to element
        element.scrollIntoView({ behavior: 'smooth'});
    })
    
})