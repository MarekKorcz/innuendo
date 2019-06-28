$(document).ready(function() 
{
    $(document).on("click", ".appointment-term", function () 
    {
        var myAppointmentTerm = $(this).data('id');        
        $(".modal-body #appointmentTerm").val(myAppointmentTerm);
        $( "label[name='appointmentTerm']" ).text("Godzina wizyty: " + myAppointmentTerm);
   });
   
   $(document).on("click", "#request-btn", function () 
   {
        console.log('click');
   });
   
   $("ul#employees > li.form-control").click(function (event) {

        let element = $(event.target);
        let employeeId = element.val();

        if (element.attr("data-active") == "true")
        {
            element.css("background-color", "").attr("data-active", "false");
            
            let addedEmployeeInput = $("input[name='employees[]'][value='" + employeeId + "']");
            
            if (addedEmployeeInput.length == 1)
            {                
                addedEmployeeInput.remove();
            }

        } else {

            element.css("background-color", "lightgreen").attr("data-active", "true");
                        
            $("form#request-form").append(`
                <input type="hidden" name="employees[]" value="` + employeeId + `">
            `);
        }
    });
});