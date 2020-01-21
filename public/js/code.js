$(document).ready(function() 
{
    let backgroundDiv = document.getElementById('background');
    
    $(".copy-button").on('click', function(event) {
        
        let copyText = event.currentTarget.parentNode.children[0];
        copyText.select();
        document.execCommand("copy");
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
        
        let modalFormElement = document.querySelector("div#deleteCode form");
        
        modalFormElement.action = ("http://localhost:8000/code/" + event.target.dataset.code_id);
        
        let modalElement = document.getElementById('deleteCode');
        
        modalElement.classList.add("modal-open");
        modalElement.classList.add("show");

        backgroundDiv.classList.add("dark");
    });
});