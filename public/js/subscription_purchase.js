$(function() {
    
    // jeśli chcesz to wywalić to usuń też pliki bootstrap_datepicker.js oraz bootstrap_datepicker.css
//    $('.dates #subscription_start').datepicker({
//        'format': 'yyyy-mm-dd',
//        'autoclose': true
//    });

    $("#purchaseForm").submit(function(event) 
    {        
        let terms = $('input#terms');
        
        if (terms.length == 1)
        {
            if (terms.prop('checked') == false)
            {
                event.preventDefault();
                                
                if ($("input#terms + div.warning > p.field-warning").length == 0)
                {
                    $("input#terms + div.warning").append('<p class="field-warning">Zaakceptuj powyższy rególamin</p>');
                }
            }
            
        } else {
            
            event.preventDefault();
        }
    });
});