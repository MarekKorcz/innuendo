$(document).ready(function() 
{
    $("form#contact-page").submit(function(event) 
    {
        let emailInput = $("input#email");
        
        if (emailInput.val() !== "") 
        {                
            $("input#email").removeClass("input-warning");
            $("div#email-error > p.field-warning").remove();

            if (!validateEmail(emailInput.val()))
            {
                event.preventDefault(); 
                $("input#email").addClass("input-warning");
                $("div#email-error").append('<p class="field-warning">Niepoprawny email</p>');
            }

        } else if (emailInput.val() === "") {

            event.preventDefault();

            if ($("div#email-error > p.field-warning").length == 0)
            {
                $("input#email").addClass("input-warning");
                $("div#email-error").append('<p class="field-warning">Wpisz adres email</p>');
            }
        }
        
        let topicInput = $("input#topic");
        
        if (topicInput.val() !== "") 
        {                
            $("input#topic").removeClass("input-warning");
            $("div#topic-error > p.field-warning").remove();

        } else if (emailInput.val() === "") {

            event.preventDefault();

            if ($("div#topic-error > p.field-warning").length == 0)
            {
                $("input#topic").addClass("input-warning");
                $("div#topic-error").append('<p class="field-warning">Wpisz temat wiadomości</p>');
            }
        }
        
        let messageInput = $("textarea#message");
        
        if (messageInput.val() !== "") 
        {                
            $("textarea#message").removeClass("input-warning");
            $("div#message-error > p.field-warning").remove();

        } else if (messageInput.val() === "") {

            event.preventDefault();

            if ($("div#message-error > p.field-warning").length == 0)
            {
                $("textarea#message").addClass("input-warning");
                $("div#message-error").append('<p class="field-warning">Napisz wiadomość</p>');
            }
        }
    });
    
    function validateEmail(email) 
    {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        
        return re.test(String(email).toLowerCase());
    }
});