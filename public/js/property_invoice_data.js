$(document).ready(function()
{    
    $("#property-invoice-data").submit(function(event) 
    {        
        let websiteInput = $("input#website");
        
        if (websiteInput.length == 1)
        {
            if (websiteInput.val() !== "") 
            {
                $("input#website + div.warning > p.field-warning").remove();
                
                if (!validateWebsite(websiteInput.val()))
                {                    
                    event.preventDefault();
                    $("input#website + div.warning").append('<p class="field-warning">Podany adres strony internetowej jest niepoprawny</p>');
                }

            } else if (websiteInput.val() === "") {

                event.preventDefault();
                
                if ($("input#website + div.warning > p.field-warning").length == 0)
                {
                    $("input#website + div.warning").append('<p class="field-warning">Wpisz adres strony internetowej</p>');
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
                    $("input#email + div.warning").append('<p class="field-warning">Podany adres email jest niepoprawny</p>');
                }

            } else if (emailInput.val() === "") {

                event.preventDefault();
                
                if ($("input#email + div.warning > p.field-warning").length == 0)
                {
                    $("input#email + div.warning").append('<p class="field-warning">Wpisz adres email</p>');
                }
            }
        }
        
        let nipInput = $("input#nip");
        
        if (nipInput.length == 1)
        {
            if (nipInput.val() !== "") 
            {
                $("input#nip + div.warning > p.field-warning").remove();
                
                if (!validateNip(nipInput.val()) || nipInput.val().length < 10)
                {
                    event.preventDefault();
                    $("input#nip + div.warning").append('<p class="field-warning">Podany numer nip jest niepoprawny</p>');
                }

            } else if (nipInput.val() === "") {

                event.preventDefault();
                
                if ($("input#nip + div.warning > p.field-warning").length == 0)
                {
                    $("input#nip + div.warning").append('<p class="field-warning">Wpisz numer nip</p>');
                }
            }
        }
        
        let bankNameInput = $("input#bank_name");
        
        if (bankNameInput.length == 1)
        {
            if (bankNameInput.val() !== "") 
            {
                $("input#bank_name + div.warning > p.field-warning").remove();
                
                if (bankNameInput.val().length < 2)
                {
                    event.preventDefault();
                    $("input#bank_name + div.warning").append('<p class="field-warning">Nazwa banku jest za krótka</p>');
                }

            } else if (bankNameInput.val() === "") {

                event.preventDefault();
                
                if ($("input#bank_name + div.warning > p.field-warning").length == 0)
                {
                    $("input#bank_name + div.warning").append('<p class="field-warning">Wpisz nazwe banku</p>');
                }
            }
        }
        
        let accountNumberInput = $("input#account_number");
        
        if (accountNumberInput.length == 1)
        {
            let accountNumber = accountNumberInput.val().replace(/\s/g, "");
            
            if (accountNumber !== "")
            {
                $("input#account_number + div.warning > p.field-warning").remove();
                
                if (!validateBankAccountNumber(accountNumber))
                {
                    event.preventDefault();
                    $("input#account_number + div.warning").append('<p class="field-warning">Numer rachunku bankowego jest niepoprawny</p>');
                }

            } else if (accountNumber === "") {

                event.preventDefault();
                
                if ($("input#account_number + div.warning > p.field-warning").length == 0)
                {
                    $("input#account_number + div.warning").append('<p class="field-warning">Wpisz numer rachunku bankowego</p>');
                }
            }
        }
    });
    
    function validateWebsite(website) 
    {
        var re = /^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/;
        
        return re.test(String(website).toLowerCase());
    }
    
    function validateNip(nip) 
    {
        var re = /^(\d+-?)+\d+$/;
        
        return re.test(String(nip).toLowerCase());
    }
    
    function validateBankAccountNumber(number) 
    {
        var re = /^\d{26}$/;
        
        return re.test(String(number).toLowerCase());
    }
    
    function validateEmail(email) 
    {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        
        return re.test(String(email).toLowerCase());
    }
});