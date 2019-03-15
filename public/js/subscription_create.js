$(document).ready(function() {
    
    $("select#item").click(function() {
        
        $( "select#item option:selected" ).each(function() {
            
            let subscriptionInput = $("input#subscription_id");
            
            if (subscriptionInput)
            {
                subscriptionInput.remove();
            }

            let subscription_id = $(this).attr('data-subscription_id');
            
            if (subscription_id)
            {
                let input = `
                    <input type="hidden" id="subscription_id" name="subscription_id" value="` + subscription_id + `">
                `;
                
                $("div#subscription").append(input);
            }
        });
    });
    
    $("#appointment-create").submit(function(event) {
        
        if ($("p.field-warning")) {
            
            $("p.field-warning").remove();
        }
        
        if (!$('select#item option:selected').val())
        {
            event.preventDefault();
            $("div#item-warning").append('<p class="field-warning">Wybierz rodzaj zabiegu</p>');
            
        }
    });
});