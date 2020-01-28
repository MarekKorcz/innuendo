$(document).ready(function() 
{
    $("ul#subscriptions > li.form-control").click(function (event) 
    {
        let element = $(event.target);
        let subscriptionId = element.val();

        if (element.attr("data-active") == "true")
        {
            element.css("background-color", "").attr("data-active", "false");

            let addedSubscriptionInput = $("input[name='subscriptions[]'][value='" + subscriptionId + "']");

            if (addedSubscriptionInput.length == 1)
            {                
                addedSubscriptionInput.remove();
            }

        } else {

            element.css("background-color", "lightgreen").attr("data-active", "true");

            $("form#promo-code").append(`
                <input type="hidden" name="subscriptions[]" value="` + subscriptionId + `">
            `);
        }      
    });
});