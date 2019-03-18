$(document).ready(function() {
    
    let codeInput = $("input#code");
    
    if (!codeInput.val())
    {
        codeInput.val(Math.random().toString(36).substring(7));
    }
    
    $("#generateCode").click(function() {
        codeInput.val(Math.random().toString(36).substring(7));
    });
    
    $("#register-code").submit(function(event) {
        
        if ($("p.field-warning")) {
            
            $("p.field-warning").remove();
        }
        
        if (!codeInput.val())
        {
            event.preventDefault();
            $("div#code-warning").append('<p class="field-warning">Wygeneruj kod</p>');
        }
    });
});