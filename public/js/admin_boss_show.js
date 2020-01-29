$(document).ready(function() {
    
    $(document).on("change", "#appointment-status", function () {
        
        let statusId = this.value;
        let appointmentId = $(this).find('option:selected').data('appointment');
        
        setAppointmentStatus(statusId, appointmentId);
    });
    
    let backgroundDiv = document.getElementById('background');
    
    document.addEventListener("click", function(event) 
    {
        let clickedElement = event.target;
        
        if (clickedElement.classList.contains('modal-open') && clickedElement.classList.contains('show'))
        {
            clickedElement.classList.remove("modal-open");
            clickedElement.classList.remove("show");

            backgroundDiv.classList.remove("dark");
        }
    });
    
    $(".close").on('click', function(event) 
    {
        let clickedModalWindow = event.target.parentElement.parentElement.parentElement;
        
        clickedModalWindow.classList.remove("modal-open");
        clickedModalWindow.classList.remove("show");
        
        backgroundDiv.classList.remove("dark");
    });
    
    $(".delete").on('click', function(event) 
    {
        event.preventDefault();
        
        let modalFormElement = document.querySelector("div#deleteAppointment form");
        
        modalFormElement.action = ("http://localhost:8000/appointment/" + event.target.dataset.appointment_id);
        
        let modalElement = document.getElementById('deleteAppointment');
        
        modalElement.classList.add("modal-open");
        modalElement.classList.add("show");

        backgroundDiv.classList.add("dark");
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