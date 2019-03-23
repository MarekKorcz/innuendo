$(document).ready(function() {
    
    $(".copy-button").on('click', function(event) {
        
        let copyText = event.currentTarget.parentNode.children[0];
        copyText.select();
        document.execCommand("copy");
    });
    
    $("ul.subscriptions > li.form-control").click(function (event) {

        if (confirm("Czy chcesz zmienić subskrypcje lokalizacji w danym kodzie")) 
        {
            let element = $(event.target);
            let subscriptionId = element.parent().attr('data-subscription_id');
            let itemId = element.val();

            if (element.attr("data-active") == "true")
            {
                element.css("background-color", "").attr("data-active", "false");

            } else {

                element.css("background-color", "lightgreen").attr("data-active", "true");
            }

            setItemToChosenSubscription(subscriptionId, itemId);            
        }        
    });
    
    function setItemToChosenSubscription(subscriptionId, itemId)
    {
        return fetch('http://localhost:8000/subscription/set-item-to-subscription', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                subscriptionId: parseInt(subscriptionId),
                itemId: parseInt(itemId)
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