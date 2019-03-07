$(document).ready(function() {
    $("li.form-control").click(function (event) {

        if (confirm("Do you wanna change property's subscription")) 
        {
            let element = $(event.target);
            let subscriptionId = element.parent().attr('data-subscription_id');
            let propertyId = element.val();

            if (element.attr("data-active") == "true")
            {
                element.css("background-color", "").attr("data-active", "false");

            } else {

                element.css("background-color", "lightgreen").attr("data-active", "true");
            }

            setSubscriptionToProperty(subscriptionId, propertyId);            
        }        
    });
    
    function setSubscriptionToProperty(subscriptionId, propertyId)
    {
        return fetch('http://localhost:8000/subscription/set-subscription-to-property', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                subscriptionId: parseInt(subscriptionId),
                propertyId: parseInt(propertyId)
            })
        })
        .then((res) => res.json())
        .then((data) => {
            if (data.type === "success")
            {
                console.log(data.message);
            }
        });
    }
});