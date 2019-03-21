$(document).ready(function() {
    
    $(".copy-button").on('click', function() {
        
        var copyText = document.getElementByClassName("code-text");
        copyText[0].select();
        document.execCommand("copy");
    });
});