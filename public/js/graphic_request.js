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

            element.css("background-color", "lightgreen").attr("data-active", "true");
                        
            $("form#request-form").append(`
                <input type="hidden" name="employees[]" value="` + employeeId + `">
            `);
        }
    });
    
    let startTimeInput = $("input#start_time");
    let endTimeInput = $("input#end_time");
    let appointmentQuantityCounter = $("#appointment-quantity-counter");
    
    $("input#start_time").change(function() 
    {
        displayAppointmentQuantityInfo();
    });
    
    $("input#end_time").change(function() 
    {
        displayAppointmentQuantityInfo();
    });
    
    $("#request-form").submit(function(event) 
    {
        if (startTimeInput.length == 1)
        {
            if (startTimeInput.val() === "") {

                event.preventDefault();
                
                if ($("input#start_time + div.warning > p.field-warning").length == 0)
                {
                    $("input#start_time + div.warning").append('<p class="field-warning">Wybierz preferowany czas rozpoczęcia grafiku</p>');
                }
            } else {
                
                $("input#start_time + div.warning > p.field-warning").remove();
            }
        }
        
        if (endTimeInput.length == 1)
        {
            if (endTimeInput.val() === "") {

                event.preventDefault();
                
                if ($("input#end_time + div.warning > p.field-warning").length == 0)
                {
                    $("input#end_time + div.warning").append('<p class="field-warning">Wybierz preferowany czas zakończenia grafiku</p>');
                }
                
            } else {
                
                $("input#end_time + div.warning > p.field-warning").remove();
            }
        }
        
        if (startTimeInput.length == 1 && endTimeInput.length == 1)
        {
            if (startTimeInput.val() !== "" && endTimeInput.val() !== "")
            {
                if (startTimeInput.val() > endTimeInput.val())
                {
                    event.preventDefault();

                    $("input#start_time + div.warning > p.field-warning").remove();
                    $("input#end_time + div.warning > p.field-warning").remove();

                    $("input#start_time + div.warning").append('<p class="field-warning">Czas rozpoczęcia nie może być większy od czasu zakończenia</p>');
                    $("input#end_time + div.warning").append('<p class="field-warning">Czas zakończenia nie może być mniejszy od czasu rozpoczęcia</p>');

                } else if (startTimeInput.val() == endTimeInput.val()) {
                    
                    event.preventDefault();
                
                    $("input#start_time + div.warning > p.field-warning").remove();
                    $("input#end_time + div.warning > p.field-warning").remove();

                    $("input#start_time + div.warning").append('<p class="field-warning">Czas rozpoczęcia nie może być taki sam jak czas zakończenia</p>');
                    $("input#end_time + div.warning").append('<p class="field-warning">Czas zakończenia nie może być taki sam jak czas rozpoczęcia</p>');
                }
            }
        }
        
        if ($("input[name='employees[]']").length == 0)
        {
            if($("#employees-warning > p.field-warning").length == 0)
            {
                event.preventDefault();
                $("#employees-warning").append('<p class="field-warning">Wybierz przynajmniej jednego pracownika</p>');
            }
            
        } else if ($("input[name='employees[]']").length > 0) {
            
            $("#employees-warning > p.field-warning").remove();
        }
    });
    
    function displayAppointmentQuantityInfo () 
    {        
        if (startTimeInput.length == 1 && endTimeInput.length == 1)
        {
            if (startTimeInput.val() !== "" && endTimeInput.val() !== "")
            {
                if (startTimeInput.val() > endTimeInput.val()) {
                    
                    $("input#start_time + div.warning > p.field-warning").remove();
                    $("input#end_time + div.warning > p.field-warning").remove();

                    $("input#start_time + div.warning").append('<p class="field-warning">Czas rozpoczęcia nie może być większy od czasu zakończenia</p>');
                    $("input#end_time + div.warning").append('<p class="field-warning">Czas zakończenia nie może być mniejszy od czasu rozpoczęcia</p>');
                    
                    appointmentQuantityCounter.empty();
                    
                } else if (startTimeInput.val() == endTimeInput.val()) {
                    
                    $("input#start_time + div.warning > p.field-warning").remove();
                    $("input#end_time + div.warning > p.field-warning").remove();

                    $("input#start_time + div.warning").append('<p class="field-warning">Czas rozpoczęcia nie może być taki sam jak czas zakończenia</p>');
                    $("input#end_time + div.warning").append('<p class="field-warning">Czas zakończenia nie może być taki sam jak czas rozpoczęcia</p>');
                    
                    appointmentQuantityCounter.empty();
                    
                } else if (!(startTimeInput.val() > endTimeInput.val()) && !(startTimeInput.val() == endTimeInput.val())) {
                    
                    $("input#start_time + div.warning > p.field-warning").remove();
                    $("input#end_time + div.warning > p.field-warning").remove();
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