$(document).ready(function() {
    
    $("#copy-button").on('click', function() {
        
        var copyText = document.getElementById("code-text");
        copyText.select();
        document.execCommand("copy");
    });
});