$(document).ready(function() {
    
    $("input#search").on('keyup', function(event) 
    {        
        let searchFieldValue = event.target.value;
        let substartId = $("#timePeriod").data("substart_id");
        
        $("#result").html('');
        
        if (substartId !== 0) 
        {
            if (searchFieldValue !== "") 
            {
                getSubscriptionUsersFromDatabase(searchFieldValue, substartId);
                
            } else {
                
                let searchField = document.getElementById("search");
                searchField.setAttribute('data-user_id', '');
                searchField.setAttribute('value', '');
                
                let timePeriod = $("#timePeriod");
                let intervalId = timePeriod.children("option:selected").val();
                let substartId = timePeriod.data("substart_id");
                
                getUsersAppointmentsFromDatabase(intervalId, substartId);
            }
        }
    });
    
    $(window).click(function(event) 
    {
        let element = $(event.target);
        let listResultElement = $("#result");
        
        if (listResultElement.children().length > 0 && !element.hasClass("list-group-item"))
        {
            listResultElement.html('');
        }
        
        if (element.hasClass('list-group-item'))
        {
            let userName = element.data('name');
            let userId = element.val();
            
            let searchField = document.getElementById("search");
            searchField.setAttribute('data-user_id', userId);
            searchField.setAttribute('value', userName);
            
            let substartId = $("#timePeriod").data("substart_id");
            
            let intervalId = 0;
            
            if ($("select#timePeriod").length > 0)
            {
                intervalId = $("select#timePeriod").children("option:selected").val();
            }
            
            $("#appointments-table").html('');
            
            getUserAppointmentsFromDatabase(userId, substartId, intervalId);
            
            listResultElement.html('');
        }
    });
    
    $('#timePeriod').change(function() 
    {
        let userId = document.getElementById('search').dataset.user_id;
        
        let intervalId = $(this).children("option:selected").val();
        let substartId = $(this).data("substart_id");

        if (userId == undefined || userId == '')
        {
            getUsersAppointmentsFromDatabase(intervalId, substartId);
            
        } else {
            
            getUserAppointmentsFromDatabase(userId, substartId, intervalId);
        }
    });
    
    function getSubscriptionUsersFromDatabase(searchField, substartId)
    {
        return fetch('http://localhost:8000/boss/get-subscription-users-from-database', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                searchField: searchField,
                substartId: substartId
            })
        })
        .then((res) => res.json())
        .then((data) => {            
            if (data.type === "success")
            {                
                $.each(data.users, function(key, value){
                    $("#result").append('<li class="list-group-item" data-name="' + value.name + ' ' + value.surname + '" value="' + value.id + '">'+ value.name + " " + value.surname + " | " + value.email + '</li>');
                });
            }
        });
    }
    
    // function to display all boss worker appointments after choosing one through search input 
    // (it grabs time period selected option value(intervalId) 
    // or doing download without it (with no intervalId existed (non activated subscription scenario)))
    function getUserAppointmentsFromDatabase(userId, substartId, intervalId)
    {        
        return fetch('http://localhost:8000/boss/get-user-appointments-from-database', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                userId: userId,
                substartId: substartId, 
                intervalId: intervalId
            })
        })
        .then((res) => res.json())
        .then((data) => {
            
            $("#appointments-table").html('');
                
            let appointmentsTable = `
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>                
                            <td>Data</td>
                            <td>Godzina</td>
                            <td>Imię i Nazwisko</td>
                            <td>Zabieg</td>
                            <td>Wykonawca</td>
                            <td>Status</td>
                        </tr>
                    </thead>
                    <tbody id="appointments">
                    </tbody>
                </table>
            `;

            $("div#appointments-table").append(appointmentsTable);
                
            if (data.type === "success")
            {                         
                $.each(data.appointments, function(key, value) 
                {
                    $("div#appointments-table > table > tbody#appointments").append(`
                        <tr>
                            <td>` + value.date + `</td>
                            <td>` + value.time + `</td>
                            <td>
                                <a href="http://localhost:8000/boss/worker/show/` + value.worker_id + `/` + value.substart_id + `/` + value.interval_id + `" target="_blanc">
                                    ` + value.worker + `
                                </a>
                            </td>
                            <td>` + value.item + `</td>
                            <td> 
                                <a href="http://localhost:8000/employee/` + value.employee_slug + `" target="_blanc">
                                    ` + value.employee + `
                                </a>
                            </td>
                            <td>` + value.status + `</td>
                        </tr>
                    `);
                });                
            }
        });
    }
    
    // function to display all boss workers based on only intervalId and substartId
    // after selecting time period with empty search input
    function getUsersAppointmentsFromDatabase(intervalId, substartId)
    {
        return fetch('http://localhost:8000/boss/get-users-appointments-from-database', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                intervalId: intervalId,
                substartId: substartId
            })
        })
        .then((res) => res.json())
        .then((data) => {
            
            $("#appointments-table").html('');
                
            let appointmentsTable = `
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>                
                            <td>Data</td>
                            <td>Godzina</td>
                            <td>Imię i Nazwisko</td>
                            <td>Zabieg</td>
                            <td>Wykonawca</td>
                            <td>Status</td>
                        </tr>
                    </thead>
                    <tbody id="appointments">
                    </tbody>
                </table>
            `;

            $("div#appointments-table").append(appointmentsTable);
                
            if (data.type === "success")
            {          
                $.each(data.appointments, function(key, value) 
                {
                    $("div#appointments-table > table > tbody#appointments").append(`
                        <tr>
                            <td>` + value.date + `</td>
                            <td>` + value.time + `</td>
                            <td>
                                <a href="http://localhost:8000/boss/worker/show/` + value.worker_id + `/` + value.substart_id + `/` + value.interval_id + `" target="_blanc">
                                    ` + value.worker + `
                                </a>
                            </td>
                            <td>` + value.item + `</td>
                            <td>
                                <a href="http://localhost:8000/employee/` + value.employee_slug + `" target="_blanc">
                                    ` + value.employee + `
                                </a>
                            </td>
                            <td>` + value.status + `</td>
                        </tr>
                    `);
                });                
            }
        });
    }
});