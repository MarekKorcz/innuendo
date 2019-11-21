$(document).ready(function() 
{
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

            element.css("background-color", "#A1D6AC").attr("data-active", "true");
                        
            $("form#request-form").append(`
                <input type="hidden" name="employees[]" value="` + employeeId + `">
            `);
        }
    });
    
    let startTimeInput = $("input[name='start_time']");
    let endTimeInput = $("input[name='end_time']");
    let appointmentQuantityCounter = $("#appointment-quantity-counter");
    
    $("input[name='start_time']").change(function() 
    {
        displayAppointmentQuantityInfo();
    });
    
    $("input[name='end_time']").change(function() 
    {
        displayAppointmentQuantityInfo();
    });
    
    $("#request-form").submit(function(event) 
    {        
        if (startTimeInput.length == 1)
        {            
            if (startTimeInput.val() === "") {

                event.preventDefault();
                
                if ($("input[name='start_time'] + div.warning > p.field-warning").length == 0)
                {
                    $("input[name='start_time']").addClass('input-warning');
                    $("input[name='start_time'] + div.warning").append('<p class="field-warning">Wybierz preferowany czas rozpoczęcia grafiku</p>');
                }
                
            } else {
                
                $("input[name='start_time']").removeClass('input-warning');
                $("input[name='start_time'] + div.warning > p.field-warning").remove();
            }
        }
        
        if (endTimeInput.length == 1)
        {
            if (endTimeInput.val() === "") {

                event.preventDefault();
                
                if ($("input[name='end_time'] + div.warning > p.field-warning").length == 0)
                {
                    $("input[name='end_time']").addClass('input-warning');
                    $("input[name='end_time'] + div.warning").append('<p class="field-warning">Wybierz preferowany czas zakończenia grafiku</p>');
                }
                
            } else {
                
                $("input[name='end_time']").removeClass('input-warning');
                $("input[name='end_time'] + div.warning > p.field-warning").remove();
            }
        }
        
        if (startTimeInput.length == 1 && endTimeInput.length == 1)
        {
            if (startTimeInput.val() !== "" && endTimeInput.val() !== "")
            {
                if (startTimeInput.val() > endTimeInput.val())
                {
                    event.preventDefault();

                    $("input[name='start_time'] + div.warning > p.field-warning").remove();
                    $("input[name='end_time'] + div.warning > p.field-warning").remove();

                    $("input[name='start_time'] + div.warning").append('<p class="field-warning">Czas rozpoczęcia nie może być większy od czasu zakończenia</p>');
                    $("input[name='end_time'] + div.warning").append('<p class="field-warning">Czas zakończenia nie może być mniejszy od czasu rozpoczęcia</p>');

                } else if (startTimeInput.val() == endTimeInput.val()) {
                    
                    event.preventDefault();
                
                    $("input[name='start_time'] + div.warning > p.field-warning").remove();
                    $("input[name='end_time'] + div.warning > p.field-warning").remove();

                    $("input[name='start_time'] + div.warning").append('<p class="field-warning">Czas rozpoczęcia nie może być taki sam jak czas zakończenia</p>');
                    $("input[name='end_time'] + div.warning").append('<p class="field-warning">Czas zakończenia nie może być taki sam jak czas rozpoczęcia</p>');
                }
            }
        }
        
        if ($("input[name='employees[]']").length == 0)
        {
            if($("#employees-warning > p.field-warning").length == 0)
            {
                event.preventDefault();
                $("#employees-warning").append('<p class="field-warning">Wybierz przynajmniej jednego pracownika</p>');
                
                $("ul#employees").children().each(function(index) 
                {
                    $(this).addClass('input-warning');
                });
            }
            
        } else if ($("input[name='employees[]']").length > 0) {
            
            $("#employees-warning > p.field-warning").remove();
            
            $("ul#employees").children().each(function(index) 
            {
                $(this).removeClass('input-warning');
            });
        }
    });
    
    function displayAppointmentQuantityInfo () 
    {        
        if (startTimeInput.length == 1 && endTimeInput.length == 1)
        {
            if (startTimeInput.val() !== "" && endTimeInput.val() !== "")
            {
                if (startTimeInput.val() > endTimeInput.val()) {
                    
                    $("input[name='start_time'] + div.warning > p.field-warning").remove();
                    $("input[name='end_time'] + div.warning > p.field-warning").remove();

                    $("input[name='start_time'] + div.warning").append('<p class="field-warning">Czas rozpoczęcia nie może być większy od czasu zakończenia</p>');
                    $("input[name='end_time'] + div.warning").append('<p class="field-warning">Czas zakończenia nie może być mniejszy od czasu rozpoczęcia</p>');
                    
                    appointmentQuantityCounter.empty();
                    
                } else if (startTimeInput.val() == endTimeInput.val()) {
                    
                    $("input[name='start_time'] + div.warning > p.field-warning").remove();
                    $("input[name='end_time'] + div.warning > p.field-warning").remove();

                    $("input[name='start_time'] + div.warning").append('<p class="field-warning">Czas rozpoczęcia nie może być taki sam jak czas zakończenia</p>');
                    $("input[name='end_time'] + div.warning").append('<p class="field-warning">Czas zakończenia nie może być taki sam jak czas rozpoczęcia</p>');
                    
                    appointmentQuantityCounter.empty();
                    
                } else if (!(startTimeInput.val() > endTimeInput.val()) && !(startTimeInput.val() == endTimeInput.val())) {
                    
                    $("input[name='start_time'] + div.warning > p.field-warning").remove();
                    $("input[name='end_time'] + div.warning > p.field-warning").remove();
                    appointmentQuantityCounter.empty();
                    
                    let subtractedTime = parseTime(endTimeInput.val()) - parseTime(startTimeInput.val());
                    
                    appointmentQuantityCounter.append('<p class="field-success">Wybrana długość grafiku to ' + subtractedTime + ' min</p>');
                }
                
            } else {
                
                appointmentQuantityCounter.empty();
            }
        }
    }
    
    function parseTime(time) 
    {
        var parsedTime = time.split(':');
        
        return parseInt(parsedTime[0]) * 60 + parseInt(parsedTime[1]);
    }
});