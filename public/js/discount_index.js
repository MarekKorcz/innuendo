$(document).ready(function() 
{
    let backgroundDiv = document.getElementById('background');
    
    document.addEventListener("click", function(event) 
    {
        let clickedElement = event.target;
        
        if (clickedElement.classList.contains('modal-open') && clickedElement.classList.contains('show'))
        {
            clickedElement.classList.remove("modal-open");
            clickedElement.classList.remove("show");

            backgroundDiv.classList.remove("dark");
        }
    });
    
    $(".close").on('click', function(event) 
    {
        let clickedModalWindow = event.target.parentElement.parentElement.parentElement;
        
        clickedModalWindow.classList.remove("modal-open");
        clickedModalWindow.classList.remove("show");
        
        backgroundDiv.classList.remove("dark");
    });
    
    $(".delete").on('click', function(event) 
    {
        event.preventDefault();
        
        let modalFormElement = document.querySelector("div#deleteDiscount form");
        
        modalFormElement.action = ("http://localhost:8000/admin/discount/" + event.target.dataset.discount_id);
        
        let modalElement = document.getElementById('deleteDiscount');
        
        modalElement.classList.add("modal-open");
        modalElement.classList.add("show");

        backgroundDiv.classList.add("dark");
    });
});