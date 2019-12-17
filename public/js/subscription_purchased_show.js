$(document).ready(function() 
{
    $('#timePeriod').change(function() 
    {
        let userId = document.getElementById('search').dataset.user_id;
        
        let intervalId = $(this).children("option:selected").val();
        let substartId = $(this).data("substart_id");

        if (userId !== undefined && intervalId !== undefined && substartId !== undefined)
        {            
            getUserAppointmentsFromDatabase(userId, substartId, intervalId);
        }
    });
    
    // function to display all user appointments
    // (it grabs time period selected option value(intervalId))
    function getUserAppointmentsFromDatabase(userId, substartId, intervalId)
    {        
        return fetch('http://localhost:8000/user/get-user-appointments-from-database', {
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
                                <a href="http://localhost:8000/user/calendar/` + value.calendar_id + `/` + value.year + `/` + value.month + `/` + value.day + `" target="_blank">
                                    ` + value.date + `
                                </a>
                            </td>
                            <td>` + value.time + `</td>
                            <td>` + value.user + `</td>
                            <td>` + value.item + `</td>
                            <td> 
                                <a href="http://localhost:8000/employee/` + value.employee_slug + `" target="_blank">
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