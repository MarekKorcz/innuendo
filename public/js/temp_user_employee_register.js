$(document).ready(function()
{    
    $("#temp-employee-register").submit(function(event) 
    {        
        let nameInput = $("input#name");
        
        if (nameInput.length == 1)
        {
            if (nameInput.val() !== "") 
            {
                $("input#name + div.warning > p.field-warning").remove();
                
                if (nameInput.val().length < 4)
                {
                    event.preventDefault();
                    $("input#name + div.warning").append('<p class="field-warning">Podane imię jest za krótkie</p>');
                }

            } else if (nameInput.val() === "") {

                event.preventDefault();
                
                if ($("input#name + div.warning > p.field-warning").length == 0)
                {
                    $("input#name + div.warning").append('<p class="field-warning">Wpisz imię</p>');
                }
            }
        }
        
        let surnameInput = $("input#surname");
        
        if (surnameInput.length == 1)
        {
            if (surnameInput.val() !== "") 
            {
                $("input#surname + div.warning > p.field-warning").remove();
                
                if (surnameInput.val().length < 3)
                {
                    event.preventDefault();
                    $("input#surname + div.warning").append('<p class="field-warning">Podane nazwisko jest za krótkie</p>');
                }

            } else if (surnameInput.val() === "") {

                event.preventDefault();
                
                if ($("input#surname + div.warning > p.field-warning").length == 0)
                {
                    $("input#surname + div.warning").append('<p class="field-warning">Wpisz nazwisko</p>');
                }
            }
        }
        
        let emailInput = $("input#email");
        
        if (emailInput.length == 1)
        {
            if (emailInput.val() !== "") 
            {
                $("input#email + div.warning > p.field-warning").remove();
                
                if (!validateEmail(emailInput.val()))
                {
                    event.preventDefault();
                    $("input#email + div.warning").append('<p class="field-warning">Niepoprawny email</p>');
                }

            } else if (emailInput.val() === "") {

                event.preventDefault();
                
                if ($("input#email + div.warning > p.field-warning").length == 0)
                {
                    $("input#email + div.warning").append('<p class="field-warning">Wpisz adres email</p>');
                }
            }
        }
        
        let phoneNumberInput = $("input#phone_number");
        
        if (phoneNumberInput.length == 1)
        {
            if (phoneNumberInput.val() !== "") 
            {
                $("input#phone_number + div.warning > p.field-warning").remove();
                
                if (phoneNumberInput.val().length < 7)
                {
                    event.preventDefault();
                    $("input#phone_number + div.warning").append('<p class="field-warning">Podany numer telefonu jest za krótki</p>');
                }

            } else if (phoneNumberInput.val() === "") {

                event.preventDefault();
                
                if ($("input#phone_number + div.warning > p.field-warning").length == 0)
                {
                    $("input#phone_number + div.warning").append('<p class="field-warning">Wpisz numer telefonu</p>');
                }
            }
        }
        
        let passwordInput = $("input#password");
        
        if (passwordInput.length == 1)
        {
            if (passwordInput.val() !== "") 
            {
                $("input#password + div.warning > p.field-warning").remove();
                
                if (passwordInput.val().length < 7)
                {
                    event.preventDefault();
                    $("input#password + div.warning").append('<p class="field-warning">Hasło musi składać się przynajmniej z 7 znaków</p>');
                }

            } else if (passwordInput.val() === "") {

                event.preventDefault();
                
                if ($("input#password + div.warning > p.field-warning").length == 0)
                {
                    $("input#password + div.warning").append('<p class="field-warning">Wpisz hasło</p>');
                }
            }
        }
        
        let passwordConfirmInput = $("input#password_confirmation");
        
        if (passwordConfirmInput.length == 1)
        {
            if (passwordConfirmInput.val() !== "") 
            {
                $("input#password_confirmation + div.warning > p.field-warning").remove();
                
                if (passwordConfirmInput.val().length < 7)
                {
                    event.preventDefault();
                    $("input#password_confirmation + div.warning").append('<p class="field-warning">Hasło musi składać się przynajmniej z 7 znaków</p>');
                    
                } else if (passwordConfirmInput.val().length >= 7 && passwordInput.val() !== passwordConfirmInput.val()) {
                    
                    event.preventDefault();
                    $("input#password_confirmation + div.warning").append('<p class="field-warning">Podane hasła różnią się</p>');
                }

            } else if (passwordConfirmInput.val() === "") {

                event.preventDefault();
                
                if ($("input#password_confirmation + div.warning > p.field-warning").length == 0)
                {
                    $("input#password_confirmation + div.warning").append('<p class="field-warning">Powtórz hasło</p>');
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