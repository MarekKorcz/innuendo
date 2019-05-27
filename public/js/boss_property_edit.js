$(document).ready(function()
{        
    $("#property-edit").submit(function(event) 
    {        
        let propertyNameInput = $("input#name");
        
        if (propertyNameInput.length == 1)
        {
            if (propertyNameInput.val() !== "") 
            {
                $("input#name + div.warning > p.field-warning").remove();
                
                if (propertyNameInput.val().length < 3)
                {
                    event.preventDefault();
                    $("input#name + div.warning").append('<p class="field-warning">Podana nazwa jest za krótka</p>');
                }

            } else if (propertyNameInput.val() === "") {

                event.preventDefault();
                
                if ($("input#name + div.warning > p.field-warning").length == 0)
                {
                    $("input#name + div.warning").append('<p class="field-warning">Wpisz nazwę lokalizacji</p>');
                }
            }
        }
        
        let propertyEmailInput = $("input#email");
        
        if (propertyEmailInput.length == 1)
        {
            if (propertyEmailInput.val() !== "") 
            {
                $("input#email + div.warning > p.field-warning").remove();
                
                if (!validateEmail(propertyEmailInput.val()))
                {
                    event.preventDefault();
                    $("input#email + div.warning").append('<p class="field-warning">Niepoprawny email</p>');
                }

            } else if (propertyEmailInput.val() === "") {

                event.preventDefault();
                
                if ($("input#email + div.warning > p.field-warning").length == 0)
                {
                    $("input#email + div.warning").append('<p class="field-warning">Wpisz email lokalizacji</p>');
                }
            }
        }
        
        let propertyPhoneNumberInput = $("input#phone_number");
        
        if (propertyPhoneNumberInput.length == 1)
        {
            if (propertyPhoneNumberInput.val() !== "") 
            {
                $("input#phone_number + div.warning > p.field-warning").remove();
                
                if (propertyPhoneNumberInput.val().length < 7)
                {
                    event.preventDefault();
                    $("input#phone_number + div.warning").append('<p class="field-warning">Podany numer telefonu jest za krótki</p>');
                }

            } else if (propertyPhoneNumberInput.val() === "") {

                event.preventDefault();
                
                if ($("input#phone_number + div.warning > p.field-warning").length == 0)
                {
                    $("input#phone_number + div.warning").append('<p class="field-warning">Wpisz numer telefonu lokalizacji</p>');
                }
            }
        }
        
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
        
        let cityInput = $("input#city");
        
        if (cityInput.length == 1)
        {
            if (cityInput.val() !== "") 
            {
                $("input#city + div.warning > p.field-warning").remove();
                
                if (cityInput.val().length < 3)
                {
                    event.preventDefault();
                    $("input#city + div.warning").append('<p class="field-warning">Nazwa miasta jest za krótka</p>');
                }

            } else if (cityInput.val() === "") {

                event.preventDefault();
                
                if ($("input#city + div.warning > p.field-warning").length == 0)
                {
                    $("input#city + div.warning").append('<p class="field-warning">Wpisz miasto</p>');
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