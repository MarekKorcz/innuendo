$(document).ready(function() 
{
    let scrollTime = 1000;
    
    scrollToWorkersTable();
    
    $("#subscription-workers-update").submit(function(event) 
    {
        let workersOnInputs = $("input[name*='workers_on']:checked");
        let workersOffInputs = $("input[name*='workers_off']:checked");
        
        if (workersOnInputs.length == 0 && workersOffInputs.length == 0)
        {
            event.preventDefault();
                
            if ($("div#submit-warning > p.field-warning").length == 0)
            {
                $("div#submit-warning").append('<p class="field-warning">Żaden pracownik nie został wybrany</p>');
            }

        } else if (workersOnInputs.length > 0 || workersOffInputs.length > 0) {
            
            $("div#submit-warning > p.field-warning").remove();
        }
    });
    
    function scrollToWorkersTable ()
    {
        $('html, body').animate({
            scrollTop: $("#workers-table").offset().top - 216
        }, scrollTime);
    }
});