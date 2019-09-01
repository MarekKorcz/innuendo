$(document).ready(function() 
{
    $("form#login").submit(function(event) 
    {        
        let emailInput = $("input#email");
        
        if (emailInput.length == 1)
        {
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
        }
        
        let passwordInput = $("input#password");
        
        if (passwordInput.length == 1)
        {
            if (passwordInput.val() !== "") 
            {
                $("input#password").removeClass("input-warning");
                $("div#password-error > p.field-warning").remove();
                
                if (passwordInput.val().length < 7)
                {
                    event.preventDefault();  
                    $("input#password").addClass("input-warning");
                    $("div#password-error").append('<p class="field-warning">Hasło musi składać się przynajmniej z 7 znaków</p>');
                }

            } else if (passwordInput.val() === "") {

                event.preventDefault();
                
                if ($("div#password-error > p.field-warning").length == 0)
                {
                    $("input#password").addClass("input-warning");
                    $("div#password-error").append('<p class="field-warning">Wpisz hasło</p>');
                }
            }
        }
    });
    
    function validateEmail(email) 
    {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        
        return re.test(String(email).toLowerCase());
    }
});