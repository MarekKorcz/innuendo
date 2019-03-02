$(document).ready(function() {
    $("#search").on('keyup', function() 
    {
        $("#result").html('');
        let searchField = $("#search").val();
        
        if (searchField !== "") 
        {
            getUserFromDatabase(searchField);
        }
    });
    
    $("#result").on('click', function(event)
    {
        let id = event.target.value;
        let name = $(event.target).data('name');
        
        $("#search").val(name);
        $("#userId").val(id);
        
        $("#result").html('');
    });
    
    $(":checkbox#isNew").change(function() 
    {        
        if ($("div#client").children().length == 1)
        {
            $("div#client > div.row > div#credential-1").html("").append(`
                <div class="form-group">
                    <label for="name">Imię</label>
                    <input id="name" class="form-control" type="text" name="name" placeholder="Podaj imię">
                </div>
            `);
            
            $("div#client").append(`
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label for="surname">Nazwisko</label>
                            <input id="surname" class="form-control" type="text" name="surname" placeholder="Podaj nazwisko">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" class="form-control" type="text" name="email" placeholder="Podaj email">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label for="phone">Telefon</label>
                            <input id="phone" class="form-control" type="text" name="phone" placeholder="Podaj telefon">
                        </div>
                    </div>
                </div>
            `);
            
        } else {
            
            $("div#client > div.row > div#credential-1").html("").append(`
                <div class="form-group">
                    <label for="search">Klient</label>
                    <input id="search" class="form-control" type="text" name="search" placeholder="Szukaj klienta">
                </div>
                <ul id="result" class="list-group"></ul>
            `);
            
            $("div#client").children().eq(3).remove();
            $("div#client").children().eq(2).remove();
            $("div#client").children().eq(1).remove();
        }
    });
    
    function getUserFromDatabase(searchField)
    {
        return fetch('http://localhost:8000/employee/backend-appointment/get-user-from-database', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                searchField: searchField
            })
        })
        .then((res) => res.json())
        .then((data) => {            
            if (data.type === "success")
            {
                $.each(data.users, function(key, value){
                    $("#result").append('<li class="list-group-item" data-name="' + value.name + '" value="' + value.id + '">'+ value.name + ' | ' + value.email +'</li>');
                });
            }
        });
    }
});