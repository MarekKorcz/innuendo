$(document).ready(function() {
    
    $('#timePeriod').change(function() 
    {
        let userId = $("#workerId").data('worker_id');        
        let intervalId = $(this).children("option:selected").val();
        let substartId = $(this).data("substart_id");
        let intervalTimePeriod = $(this).children("option:selected").data('time_period');
            
        getUserAppointmentsFromDatabase(userId, substartId, intervalId, intervalTimePeriod);
    });
    
    function getUserAppointmentsFromDatabase(userId, substartId, intervalId, intervalTimePeriod)
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
            
            $("#intervalPeriod").html('');
                
            if (data.type === "success")
            {         
                $("#intervalPeriod").html('za okres ' + intervalTimePeriod);
                
                $.each(data.appointments, function(key, value) 
                {
                    $("div#appointments-table > table > tbody#appointments").append(`
                        <tr>
                            <td>` + value.date + `</td>
                            <td>` + value.time + `</td>
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