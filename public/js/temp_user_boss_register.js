$(document).ready(function()
{    
    $("#temp-boss-register").submit(function(event) 
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
        
        let bossEmailInput = $("input#boss_email");
        
        if (bossEmailInput.length == 1)
        {
            if (bossEmailInput.val() !== "") 
            {
                $("input#boss_email + div.warning > p.field-warning").remove();
                
                if (!validateEmail(bossEmailInput.val()))
                {
                    event.preventDefault();
                    $("input#boss_email + div.warning").append('<p class="field-warning">Niepoprawny email</p>');
                }

            } else if (bossEmailInput.val() === "") {

                event.preventDefault();
                
                if ($("input#boss_email + div.warning > p.field-warning").length == 0)
                {
                    $("input#boss_email + div.warning").append('<p class="field-warning">Wpisz adres email</p>');
                }
            }
        }
        
        let bossPhoneNumberInput = $("input#boss_phone_number");
        
        if (bossPhoneNumberInput.length == 1)
        {
            if (bossPhoneNumberInput.val() !== "") 
            {
                $("input#boss_phone_number + div.warning > p.field-warning").remove();
                
                if (bossPhoneNumberInput.val().length < 7)
                {
                    event.preventDefault();
                    $("input#boss_phone_number + div.warning").append('<p class="field-warning">Podany numer telefonu jest za krótki</p>');
                }

            } else if (bossPhoneNumberInput.val() === "") {

                event.preventDefault();
                
                if ($("input#boss_phone_number + div.warning > p.field-warning").length == 0)
                {
                    $("input#boss_phone_number + div.warning").append('<p class="field-warning">Wpisz numer telefonu</p>');
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
        
        let propertyNameInput = $("input#property_name");
        
        if (propertyNameInput.length == 1)
        {
            if (propertyNameInput.val() !== "") 
            {
                $("input#property_name + div.warning > p.field-warning").remove();
                
                if (propertyNameInput.val().length < 3)
                {
                    event.preventDefault();
                    $("input#property_name + div.warning").append('<p class="field-warning">Podana nazwa jest za krótka</p>');
                }

            } else if (propertyNameInput.val() === "") {

                event.preventDefault();
                
                if ($("input#property_name + div.warning > p.field-warning").length == 0)
                {
                    $("input#property_name + div.warning").append('<p class="field-warning">Wpisz nazwę lokalizacji</p>');
                }
            }
        }
        
//        let propertyEmailInput = $("input#property_email");
//        
//        if (propertyEmailInput.length == 1)
//        {
//            if (propertyEmailInput.val() !== "") 
//            {
//                $("input#property_email + div.warning > p.field-warning").remove();
//                
//                if (!validateEmail(propertyEmailInput.val()))
//                {
//                    event.preventDefault();
//                    $("input#property_email + div.warning").append('<p class="field-warning">Niepoprawny email</p>');
//                }
//
//            } else if (propertyEmailInput.val() === "") {
//
//                event.preventDefault();
//                
//                if ($("input#property_email + div.warning > p.field-warning").length == 0)
//                {
//                    $("input#property_email + div.warning").append('<p class="field-warning">Wpisz email lokalizacji</p>');
//                }
//            }
//        }
//        
//        let propertyPhoneNumberInput = $("input#property_phone_number");
//        
//        if (propertyPhoneNumberInput.length == 1)
//        {
//            if (propertyPhoneNumberInput.val() !== "") 
//            {
//                $("input#property_phone_number + div.warning > p.field-warning").remove();
//                
//                if (propertyPhoneNumberInput.val().length < 7)
//                {
//                    event.preventDefault();
//                    $("input#property_phone_number + div.warning").append('<p class="field-warning">Podany numer telefonu jest za krótki</p>');
//                }
//
//            } else if (propertyPhoneNumberInput.val() === "") {
//
//                event.preventDefault();
//                
//                if ($("input#property_phone_number + div.warning > p.field-warning").length == 0)
//                {
//                    $("input#property_phone_number + div.warning").append('<p class="field-warning">Wpisz numer telefonu lokalizacji</p>');
//                }
//            }
//        }
        
        let streetInput = $("input#street");
        
        if (streetInput.length == 1)
        {
            if (streetInput.val() !== "") 
            {
                $("input#street + div.warning > p.field-warning").remove();
                
                if (streetInput.val().length < 3)
                {
                    event.preventDefault();
                    $("input#street + div.warning").append('<p class="field-warning">Podana nazwa ulicy jest za krótka</p>');
                }

            } else if (streetInput.val() === "") {

                event.preventDefault();
                
                if ($("input#street + div.warning > p.field-warning").length == 0)
                {
                    $("input#street + div.warning").append('<p class="field-warning">Wpisz ulicę pod którą znajduje się lokalizacja</p>');
                }
            }
        }
        
        let streetNumberInput = $("input#street_number");
        
        if (streetNumberInput.length == 1)
        {
            if (streetNumberInput.val() !== "") 
            {
                $("input#street_number + div.warning > p.field-warning").remove();

            } else if (streetNumberInput.val() === "") {

                event.preventDefault();
                
                if ($("input#street_number + div.warning > p.field-warning").length == 0)
                {
                    $("input#street_number + div.warning").append('<p class="field-warning">Wpisz numer ulicy pod którym znajduje się lokalizacja</p>');
                }
            }
        }
        
        let houseNumberInput = $("input#house_number");
        
        if (houseNumberInput.length == 1)
        {
            if (houseNumberInput.val() !== "") 
            {
                $("input#house_number + div.warning > p.field-warning").remove();

            } else if (houseNumberInput.val() === "") {

                event.preventDefault();
                
                if ($("input#house_number + div.warning > p.field-warning").length == 0)
                {
                    $("input#house_number + div.warning").append('<p class="field-warning">Wpisz numer budynku pod którym znajduje się lokalizacja</p>');
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