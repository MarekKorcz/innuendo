$(document).ready(function() 
{
    document.addEventListener("keyup", function() 
    {
        $("#result").html('');
        let searchField = document.getElementById("search");
        let calendarInput = $("input[name=calendarId]");
        
        if (searchField !== null && searchField.value !== "") 
        {
            getUserFromDatabase(searchField.value, calendarInput.val());
        }
    });    
    
    $(window).click(function(event) 
    {
        if (event.target.id == "search")
        {
            event.target.value = '';
        }
        
        if (event.target.parentElement !== null && event.target.parentElement.id == 'result')
        {
            let userId = event.target.value;
            let name = $(event.target).data('name');
            let surname = $(event.target).data('surname');
            
            let possibleAppointmentLengthInMinutes = $("input[name='possibleAppointmentLengthInMinutes']").val();
            let propertyId = $("input[name='propertyId']").val();

            $("#search").val(name + " " + surname);
            $("#userId").val(userId);

            $("#result").html('');
            $("#items").html('');

            getUserItemsFromDatabase(userId, propertyId, possibleAppointmentLengthInMinutes);
        }
        
        if (event.target.id == 'item')
        {            
            $( "select#item option:selected" ).each(function() {

                let purchaseInput = $("input#purchase_id");

                if (purchaseInput)
                {
                    purchaseInput.remove();
                }

                let purchase_id = $(this).attr('data-purchase_id');

                if (purchase_id)
                {
                    let input = `
                        <input id="purchase_id" type="hidden" name="purchase_id" value="` + purchase_id + `">
                    `;

                    $("div#items").append(input);
                }
                
                let itemInput = $("input#item_id");

                if (itemInput)
                {
                    itemInput.remove();
                }
                
                let item_id = $(this).val();

                if (item_id)
                {
                    let input = `
                        <input id="item_id" type="hidden" name="item_id" value="` + item_id + `">
                    `;

                    $("div#items").append(input);
                }
            });
        }
    });

    
    $("#appointment-create").submit(function(event) 
    {        
        let searchInput = $("input#userId");
        
        if (searchInput.length == 1)
        {
            if (searchInput.val() !== "") 
            {
                $("div#credential-1 > div.warning > p.field-warning").remove();

            } else if (searchInput.val() === "") {

                event.preventDefault();
                
                if ($("div#credential-1 > div.warning > p.field-warning").length == 0)
                {
                    $("div#credential-1 > div.warning").append('<p class="field-warning">Wybierz klienta</p>');
                }
            }
        }
        
        let nameInput = $("input#name");
        
        if (nameInput.length == 1)
        {
            if (nameInput.val() !== "") 
            {
                $("div#credential-1 > div.form-group > div.warning > p.field-warning").remove();

            } else if (nameInput.val() === "") {

                event.preventDefault();
                
                if ($("div#credential-1 > div.form-group > div.warning > p.field-warning").length == 0)
                {
                    $("div#credential-1 > div.form-group > div.warning").append('<p class="field-warning">Wpisz imię</p>');
                }
            }
        }
        
        let surnameInput = $("input#surname");
        
        if (surnameInput.length == 1)
        {
            if (surnameInput.val() !== "") 
            {
                $("div#surname > div.col-7 > div.warning > p.field-warning").remove();

            } else if (surnameInput.val() === "") {

                event.preventDefault();
                
                if ($("div#surname > div.col-7 > div.warning > p.field-warning").length == 0)
                {
                    $("div#surname > div.col-7 > div.warning").append('<p class="field-warning">Wpisz nazwisko</p>');
                }
            }
        }
        
        let emailInput = $("input#email");
        
        if (emailInput.length == 1)
        {
            if (emailInput.val() !== "") 
            {
                $("div#email > div.col-7 > div.warning > p.field-warning").remove();

            } else if (emailInput.val() === "") {

                event.preventDefault();
                
                if ($("div#email > div.col-7 > div.warning > p.field-warning").length == 0)
                {
                    $("div#email > div.col-7 > div.warning").append('<p class="field-warning">Wpisz email</p>');
                }
            }
        }
        
        let phoneInput = $("input#phone");
        
        if (phoneInput.length == 1)
        {
            if (phoneInput.val() !== "") 
            {
                $("div#phone > div.col-7 > div.warning > p.field-warning").remove();

            } else if (phoneInput.val() === "") {

                event.preventDefault();
                
                if ($("div#phone > div.col-7 > div.warning > p.field-warning").length == 0)
                {
                    $("div#phone > div.col-7 > div.warning").append('<p class="field-warning">Wpisz telefon</p>');
                }
            }
        }
        
        let itemSelect = $("select#item");
        
        if (itemSelect.length == 1)
        {
            let purchaseInput = $("input#purchase_id");
            
            if (purchaseInput.length == 1)
            {
                $("div#items > div.form-group > div.warning > p.field-warning").remove();
                
            } else if (purchaseInput.length == 0) {
                
                event.preventDefault();
                
                if ($("div#items > div.form-group > div.warning > p.field-warning").length == 0)
                {
                    $("div#items > div.form-group > div.warning").append('<p class="field-warning">Wybierz rodzaj zabiegu</p>');
                }
            }
            
        } else {
            
            event.preventDefault();
        }
    });
    
    $(":checkbox#isNew").change(function() 
    {               
        if ($("div#client").children().length == 1)
        {
            $("div#items").html("");
            
            $("div#client > div.row > div#credential-1").html("").append(`
                <div class="form-group">
                    <label for="name">Imię</label>
                    <input id="name" class="form-control" type="text" name="name" placeholder="Podaj imię">
                    <div class="warning"></div>
                </div>
            `);
            
            $("div#client").append(`
                <div id="surname" class="row">
                    <div class="col-1"></div>
                    <div class="col-10">
                        <div class="form-group">
                            <label for="surname">Nazwisko</label>
                            <input id="surname" class="form-control" type="text" name="surname" placeholder="Podaj nazwisko">
                        </div>
                        <div class="warning"></div>
                    </div>
                    <div class="col-1"></div>
                </div>
                <div id="email" class="row">
                    <div class="col-1"></div>
                    <div class="col-10">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" class="form-control" type="text" name="email" placeholder="Podaj email">
                        </div>
                        <div class="warning"></div>
                    </div>
                    <div class="col-1"></div>
                </div>
                <div id="phone" class="row">
                    <div class="col-1"></div>
                    <div class="col-10">
                        <div class="form-group">
                            <label for="phone">Telefon</label>
                            <input id="phone" class="form-control" type="text" name="phone" placeholder="Podaj telefon">
                        </div>
                        <div class="warning"></div>
                    </div>
                    <div class="col-1"></div>
                </div>
            `);
            
            let possibleAppointmentLengthInMinutes = $("input[name='possibleAppointmentLengthInMinutes']").val();
            let propertyId = $("input[name='propertyId']").val();

            getUserItemsFromDatabase(0, propertyId, possibleAppointmentLengthInMinutes);
            
        } else {
            
            $("div#client > div.row > div#credential-1").html("").append(`
                <div class="form-group">
                    <div class="text-center">
                        <label for="search">Klient:</label>
                    </div>
                    <input id="search" class="form-control" type="text" name="search" placeholder="Szukaj klienta" autocomplete="off">
                    <ul id="result" class="list-group"></ul>
                </div>
                <div class="warning"></div>
                <input id="userId" type="hidden" name="userId" value="">
            `);
            
            $("div#client").children().eq(3).remove();
            $("div#client").children().eq(2).remove();
            $("div#client").children().eq(1).remove();
            
            $("div#items").html("");
        }
    });   
    
    function getUserFromDatabase(searchField, calendarId)
    {
        return fetch('http://localhost:8000/employee/backend-appointment/get-user-from-database', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                searchField: searchField,
                calendarId: calendarId
            })
        })
        .then((res) => res.json())
        .then((data) => {            
            if (data.type === "success")
            {
                if ($("div#credential-1 > div.warning > p.field-warning").length == 1)
                {
                    $("div#credential-1 > div.warning > p.field-warning").remove();
                }
                
                let resultList = $("#result");
                
                resultList.width($("input#search.form-control").outerWidth());
                
                $.each(data.users, function(key, value){
                    resultList.append('<li class="list-group-item" data-name="' + value.name + '" data-surname="' + value.surname + '" value="' + value.id + '">'+ value.name + ` ` + value.surname + ' | ' + value.email +'</li>');
                });
            }
        });
    }
    
    function getUserItemsFromDatabase(userId, propertyId, possibleAppointmentLengthInMinutes)
    {
        return fetch('http://localhost:8000/employee/backend-appointment/get-user-items-from-database', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                userId: userId,
                propertyId: propertyId,
                possibleAppointmentLengthInMinutes: possibleAppointmentLengthInMinutes
                
            })
        })
        .then((res) => res.json())
        .then((data) => {
            if (data.type === "success")
            {
                if (data.items.length > 0)
                {
                    $("div#items").append(`
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col-10">
                                <div class="form-group">
                                    <label for="items">
                                        Zabiegi wybrane dla 
                                        <strong>` + data.user_name + `</strong>
                                    </label>
                                    <select id="item" class="form-control">
                                        <option disabled selected value> --- wybierz rodzaj zabiegu --- </option>
                                    </select>
                                    <div class="warning"></div>
                                </div>
                            </div>
                            <div class="col-1"></div>
                        </div>
                    `);

                    $.each(data.items, function(key, value){
                        $("select#item").append(`
                            <option 
                                data-purchase_id="` + value.purchase_id + `"
                                data-item_minutes="` + value.item_minutes + `" 
                                value="` + value.item_id + `"
                            >`+ value.item_name + `</option>`
                        );
                    });
                }                
            }
        });
    }
});