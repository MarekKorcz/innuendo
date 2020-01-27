$(document).ready(function() {
    
    $("input#search").on('keyup', function(event) 
    {              
        let searchFieldValue = event.target.value;
        let propertyId = $("#timePeriod").data("property_id");
        
        $("#result").html('');
        
        if (propertyId !== 0) 
        {
            if (searchFieldValue !== "") 
            {
                getUsersFromDatabase(searchFieldValue, propertyId);
                
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
            
            let propertyId = $("#timePeriod").data("property_id");
            
            let monthId = 0;
            
            if ($("select#timePeriod").length > 0)
            {
                monthId = $("select#timePeriod").children("option:selected").data('month_id');
            }
            
            $("#appointments-table").html('');
            
            getUserAppointmentsFromDatabase(userId, propertyId, monthId);
            
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
        
        let monthId = $(this).children("option:selected").data('month_id');
        let propertyId = $(this).data("property_id");
        
        if (userId == undefined || userId == '')
        {
            getUsersAppointmentsFromDatabase(propertyId, monthId);
            
        } else {
            
            getUserAppointmentsFromDatabase(userId, propertyId, monthId);
        }
        
        getMonthlyPaymentsForDoneAppointments(monthId);
    });
    
    function getMonthlyPaymentsForDoneAppointments(monthId)
    {
        return fetch('http://localhost:8000/boss/get-monthly-payments-for-done-appointments', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                monthId: monthId
            })
        })
        .then((res) => res.json())
        .then((data) => {
            
            if (data.type === "success")
            {      
                let monthlyPaymentsParentElement = $("#monthly-payments");
                monthlyPaymentsParentElement.html('');
                
                if (data.locale == "en")
                {
                    console.log('angielski')
                    
                    monthlyPaymentsParentElement.append(`
                        <h3>
                            ` + data.total_amount_for_done_appointments_description + `
                            ` + data.monthEn + `
                            (` + data.monthStartDateTime + ` - ` + data.monthEndDateTime + `)
                        </h3>
                    `);
                    
                } else {
                    
                    monthlyPaymentsParentElement.append(`
                        <h3>
                            ` + data.total_amount_for_done_appointments_description + `
                            ` + data.month + `
                            (` + data.monthStartDateTime + ` - ` + data.monthEndDateTime + `)
                        </h3>
                    `);
                }
                
                if (Object.keys(data.payments).length > 0)
                {
                    monthlyPaymentsParentElement.append(`
                        <p>
                            <strike>
                                ` + data.payments['totalAmountWithoutDiscounts'] + ` zł  
                            </strike>
                            &nbsp;
                            <strong>
                                ` + data.payments['totalAmount'] + ` zł
                            </strong>
                            (` + data.discount_description + ` - ` + data.payments['totalDiscountPercentage'] + `%)
                        </p>
                    `)
                    
                } else {
                    
                    monthlyPaymentsParentElement.append(`
                        <p>
                            ` + data.no_payments_description + `
                        </p>
                    `)
                }
            }
        });
    }
    
    function getUsersFromDatabase(searchField, propertyId)
    {
        return fetch('http://localhost:8000/boss/get-property-users-from-database', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                searchField: searchField,
                propertyId: propertyId
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
    function getUserAppointmentsFromDatabase(userId, propertyId, monthId)
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
                propertyId: propertyId, 
                monthId: monthId
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
                                <a href="http://localhost:8000/boss/calendar/` + data.propertyId + `/` + value.year + `/` + value.month + `/` + value.day + `" target="_blank">
                                    ` + value.date + `
                                </a>
                            </td>
                            <td>` + value.time + `</td>
                            <td>
                                <a href="http://localhost:8000/boss/worker/appointment/list/` + data.propertyId + `/` + value.worker_id + `">
                                    ` + value.worker + `
                                </a>
                            </td>
                            <td>` + value.item + `</td>
                            <td> 
                                <a href="http://localhost:8000/employee/` + value.employee_slug + `" target="_blank">
                                    ` + value.employee_name + `
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
    function getUsersAppointmentsFromDatabase(propertyId, monthId)
    {        
        return fetch('http://localhost:8000/boss/get-users-appointments-from-database', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                propertyId: propertyId,
                monthId: monthId
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
                                <a href="http://localhost:8000/boss/calendar/` + data.propertyId + `/` + value.year + `/` + value.month + `/` + value.day + `" target="_blank">
                                    ` + value.date + `
                                </a>
                            </td>
                            <td>` + value.time + `</td>
                            <td>
                                <a href="http://localhost:8000/boss/worker/appointment/list/` + data.propertyId + `/` + value.worker_id + `">
                                    ` + value.worker + `
                                </a>
                            </td>
                            <td>` + value.item + `</td>
                            <td> 
                                <a href="http://localhost:8000/employee/` + value.employee_slug + `" target="_blank">
                                    ` + value.employee_name + `
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
        let propertyId = timePeriod.data("property_id");
        let monthId = timePeriod.children("option:selected").data('month_id');

        getUsersAppointmentsFromDatabase(propertyId, monthId);
    }
});