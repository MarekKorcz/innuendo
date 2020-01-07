$(document).ready(function() 
{
    $(".copy-button").on('click', function() {
        
        // copy code
        let codeElement = document.getElementById('code')
        codeElement.select()
        document.execCommand("copy")
        
        // make register link visible
        let registerElement = document.getElementById('register')
        registerElement.style.visibility = null
    });
    
});