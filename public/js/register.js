$(document).ready(function() 
{
    $("form#register").submit(function(event) 
    {        
        let nameReady = false;
        let surnameReady = false;
        let phoneNumberReady = false;
        let emailReady = false;
        let codeReady = false;
        let passwordReady = false;
        let passwordConfirmReady = false;
        
        let nameInput = $("input#name");
        
        if (nameInput.length == 1)
        {
            if (nameInput.val() !== "") 
            {                
                $("div#name-error > p.field-warning").remove();
                
                if (nameInput.val().length < 4)
                {
                    event.preventDefault();
                    
                    nameReady = false;
                    
                    $("div#name-error").append('<p class="field-warning">Podane imię jest za krótkie</p>');
                    
                } else {
                    
                    nameReady = true;
                }

            } else if (nameInput.val() === "") {

                event.preventDefault();
                
                nameReady = false;
                
                if ($("div#name-error > p.field-warning").length == 0)
                {
                    $("div#name-error").append('<p class="field-warning">Wpisz imię</p>');
                }
            }
        }
        
        let surnameInput = $("input#surname");
        
        if (surnameInput.length == 1)
        {
            if (surnameInput.val() !== "") 
            {                
                $("div#surname-error > p.field-warning").remove();
                
                if (surnameInput.val().length < 3)
                {
                    event.preventDefault();
                    
                    surnameReady = false;
                    
                    $("div#surname-error").append('<p class="field-warning">Podane nazwisko jest za krótkie</p>');
                    
                } else {
                    
                    surnameReady = true;
                }

            } else if (surnameInput.val() === "") {

                event.preventDefault();
                
                surnameReady = false;
                
                if ($("div#surname-error > p.field-warning").length == 0)
                {
                    $("div#surname-error").append('<p class="field-warning">Wpisz nazwisko</p>');
                }
            }
        }
        
        let phoneNumberInput = $("input#phone_number");
        
        if (phoneNumberInput.length == 1)
        {
            if (phoneNumberInput.val() !== "") 
            {
                $("div#phone_number-error > p.field-warning").remove();
                
                if (phoneNumberInput.val().length < 7)
                {
                    event.preventDefault();
                    
                    phoneNumberReady = false;
                    
                    $("div#phone_number-error").append('<p class="field-warning">Podany numer telefonu jest za krótki</p>');
                    
                } else {
                    
                    phoneNumberReady = true;
                }

            } else if (phoneNumberInput.val() === "") {

                event.preventDefault();
                
                phoneNumberReady = false;
                
                if ($("div#phone_number-error > p.field-warning").length == 0)
                {
                    $("div#phone_number-error").append('<p class="field-warning">Wpisz numer telefonu</p>');
                }
            }
        }
        
        let emailInput = $("input#email");
        
        if (emailInput.length == 1)
        {
            if (emailInput.val() !== "") 
            {                
                $("div#email-error > p.field-warning").remove();
                
                if (!validateEmail(emailInput.val()))
                {
                    event.preventDefault();
                    
                    emailReady = false;
                    
                    $("div#email-error").append('<p class="field-warning">Niepoprawny email</p>');
                    
                } else {
                    
                    emailReady = true;
                }

            } else if (emailInput.val() === "") {

                event.preventDefault();
                
                emailReady = false;
                
                if ($("div#email-error > p.field-warning").length == 0)
                {
                    $("div#email-error").append('<p class="field-warning">Wpisz adres email</p>');
                }
            }
        }
        
        let codeInput = $("input#code");
        
        if (codeInput.length == 1)
        {
            if (codeInput.val() !== "") 
            {
                $("div#code-error > p.field-warning").remove();
                
                if (codeInput.val().length < 6)
                {
                    event.preventDefault();
                    
                    codeReady = false;
                    
                    $("div#code-error").append('<p class="field-warning">Podany kod jest za krótki</p>');
                    
                } else {
                    
                    codeReady = true;
                }

            } else if (codeInput.val() === "") {

                event.preventDefault();
                
                codeReady = false;
                
                if ($("div#code-error > p.field-warning").length == 0)
                {
                    $("div#code-error").append('<p class="field-warning">Wpisz kod rejestracyjny</p>');
                }
            }
        }
        
        let passwordInput = $("input#password");
        
        if (passwordInput.length == 1)
        {
            if (passwordInput.val() !== "") 
            {
                $("div#password-error > p.field-warning").remove();
                
                if (passwordInput.val().length < 7)
                {
                    event.preventDefault();
                    
                    passwordReady = false;
                    
                    $("div#password-error").append('<p class="field-warning">Hasło musi składać się przynajmniej z 7 znaków</p>');
                    
                } else {
                    
                    passwordReady = true;
                }

            } else if (passwordInput.val() === "") {

                event.preventDefault();
                
                passwordReady = false;
                
                if ($("div#password-error > p.field-warning").length == 0)
                {
                    $("div#password-error").append('<p class="field-warning">Wpisz hasło</p>');
                }
            }
        }
        
        let passwordConfirmInput = $("input#password_confirm");
        
        if (passwordConfirmInput.length == 1)
        {
            if (passwordConfirmInput.val() !== "") 
            {
                $("div#password_confirm-error > p.field-warning").remove();
                
                if (passwordConfirmInput.val().length < 7)
                {
                    event.preventDefault();
                    
                    passwordConfirmReady = false;
                    
                    $("div#password_confirm-error").append('<p class="field-warning">Hasło musi składać się przynajmniej z 7 znaków</p>');
                    
                } else if (passwordConfirmInput.val().length >= 7 && passwordInput.val() !== passwordConfirmInput.val()) {
                    
                    event.preventDefault();
                    
                    passwordConfirmReady = false;
                    
                    $("div#password_confirm-error").append('<p class="field-warning">Podane hasła różnią się</p>');
                    
                } else {
                    
                    passwordConfirmReady = true;
                }

            } else if (passwordConfirmInput.val() === "") {

                event.preventDefault();
                
                passwordConfirmReady = false;
                
                if ($("div#password_confirm-error > p.field-warning").length == 0)
                {
                    $("div#password_confirm-error").append('<p class="field-warning">Powtórz hasło</p>');
                }
            }
        }
        
        if (nameReady && surnameReady && phoneNumberReady && emailReady && codeReady && passwordReady && passwordConfirmReady)
        {
            let codeDataElement = document.getElementById("code-data");
                    
            if (codeDataElement !== null && codeDataElement.getAttribute('data-for') !== null)
            {
                if (codeDataElement.getAttribute('data-for') == 'boss') {
                    
                    event.preventDefault();  
                    
                    // remove previously set inputs
                    $("form#register-boss > input[name='name']").remove();
                    $("form#register-boss > input[name='surname']").remove();
                    $("form#register-boss > input[name='phone_number']").remove();
                    $("form#register-boss > input[name='email']").remove();
                    $("form#register-boss > input[name='code']").remove();
                    $("form#register-boss > input[name='password']").remove();
                    
                    // append inputs from first form
                    $("form#register-boss").append(`
                        <input type="hidden" name="name" value="` + nameInput.val() + `">
                        <input type="hidden" name="surname" value="` + surnameInput.val() + `">
                        <input type="hidden" name="phone_number" value="` + phoneNumberInput.val() + `">
                        <input type="hidden" name="email" value="` + emailInput.val() + `">
                        <input type="hidden" name="code" value="` + codeInput.val() + `">
                        <input type="hidden" name="password" value="` + passwordInput.val() + `">
                    `);
    
                    $('#registerNewBoss').modal('show');
                }
            }
        }
    });
    
    $("input#code").on("keyup change", function(event) {
        
        let codeInput = $("input#code");
        
        if (codeInput.val().length > 5)
        {
            checkIfCodeExists(codeInput.val());
            
        } else {
            
            $("div#code-error > p.field-warning").remove();
        }
    });
    
    $("form#register-boss").submit(function(event) 
    {
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
                    $("input#city + div.warning").append('<p class="field-warning">Podana nazwa miasta jest za krótka</p>');
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
    
    function checkIfCodeExists(code)
    {        
        return fetch('http://localhost:8000/register/check-if-code-exists', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                code: code
            })
        })
        .then((res) => res.json())
        .then((data) => {
            
            let codeDataElement = document.getElementById("code-data");
            codeDataElement.removeAttribute('data-for');
            
            if (data.status == "notExisting")
            {                       
                $("div#code-error > p.field-warning").remove();
                $("div#code-error").append('<p class="field-warning">Podany kod nie istnieje</p>');
                
            } else if (data.status == "existing") {
                
                $("div#code-error > p.field-warning").remove();
                
                codeDataElement.dataset.for = data.for;
            }
        });
    }
});