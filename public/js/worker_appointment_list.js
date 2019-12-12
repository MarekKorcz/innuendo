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
                
                clearSearchInputAndShowAllAppointments();
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
        
        if (element.attr('id') == "search" || element.attr('id') == "showAllWorkers")
        {
            clearSearchInputAndShowAllAppointments();
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
                let resultList = $("#result");
                
                resultList.width($("input#search.form-control").outerWidth());
                
                $.each(data.users, function(key, value){
                    resultList.append('<li class="list-group-item" data-name="' + value.name + ' ' + value.surname + '" value="' + value.id + '">'+ value.name + " " + value.surname + " | " + value.email + '</li>');
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
            
            if (data.type === "success")
            {       
                $("#appointments-table").html('');

                let appointmentsTable = `
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>                
                                <td>` + data.date_description + `</td>
                                <td>` + data.hour_description + `</td>
                                <td>` + data.name_and_surname_description + `</td>
                                <td>` + data.massage_description + `</td>
                                <td>` + data.executor_description + `</td>
                                <td>` + data.status_description + `</td>
                            </tr>
                        </thead>
                        <tbody id="appointments">
                        </tbody>
                    </table>
                `;

                $("div#appointments-table").append(appointmentsTable);
                
                $("#search").val(data.worker_name + " " + data.worker_surname);
                
                $.each(data.appointments, function(key, value) 
                {
                    $("div#appointments-table > table > tbody#appointments").append(`
                        <tr>
                            <td>
                                <a href="http://localhost:8000/boss/calendar/` + value.calendar_id + `/` + value.year + `/` + value.month + `/` + value.day + `" target="_blanc">
                                    ` + value.date + `
                                </a>
                            </td>
                            <td>` + value.time + `</td>
                            <td>` + value.worker + `</td>
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
            
            if (data.type === "success")
            {          
                $("#appointments-table").html('');

                let appointmentsTable = `
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <td>` + data.date_description + `</td>
                                <td>` + data.hour_description + `</td>
                                <td>` + data.name_and_surname_description + `</td>
                                <td>` + data.massage_description + `</td>
                                <td>` + data.executor_description + `</td>
                                <td>` + data.status_description + `</td>
                            </tr>
                        </thead>
                        <tbody id="appointments">
                        </tbody>
                    </table>
                `;

                $("div#appointments-table").append(appointmentsTable);
                
                $.each(data.appointments, function(key, value) 
                {
                    $("div#appointments-table > table > tbody#appointments").append(`
                        <tr>
                            <td>
                                <a href="http://localhost:8000/boss/calendar/` + value.calendar_id + `/` + value.year + `/` + value.month + `/` + value.day + `" target="_blanc">
                                    ` + value.date + `
                                </a>
                            </td>
                            <td>` + value.time + `</td>
                            <td>` + value.worker + `</td>
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
    
    function clearSearchInputAndShowAllAppointments()
    {
        let searchField = document.getElementById("search");
        searchField.setAttribute('data-user_id', '');
        searchField.value = '';

        let timePeriod = $("#timePeriod");
        let intervalId = timePeriod.children("option:selected").val();
        let substartId = timePeriod.data("substart_id");

        getUsersAppointmentsFromDatabase(intervalId, substartId);
    }
});