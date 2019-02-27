$(document).ready(function() {
    $(document).on("change", "#appointment-status", function () {
        
        let statusId = this.value;
        let appointmentId = $(this).find('option:selected').data('appointment');
        
        setAppointmentStatus(statusId, appointmentId);
    });
    
    function setAppointmentStatus(statusId, appointmentId)
    {
        return fetch('http://localhost:8000/employee/backend-appointment/set-appointment-status', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                statusId: parseInt(statusId),
                appointmentId: appointmentId
            })
        })
        .then((res) => res.json())
        .then((data) => {
            if (data.type === "success")
            {
                $("strong#status").html(data.status);
            }
        });
    }
});