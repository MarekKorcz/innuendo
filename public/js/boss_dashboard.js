$(document).ready(function() {
    
    $(".copy-button").on('click', function(event) {
        
        let copyText = event.currentTarget.parentNode.children[0];
        copyText.select();
        document.execCommand("copy");
    });
    
    $("ul.property > li.form-control").click(function (event) {

        let element = event.target;
        let codeElement = element.parentElement.parentElement;
        
        if (element.dataset.active === "true")
        {
            if (confirm("Czy chcesz WYŁĄCZYĆ tę lokalizacje w danym kodzie")) 
            {
                let chosenPropertyId = element.dataset.chosen_property_id;
                
                deleteChosenProperty(chosenPropertyId);
                element.style.backgroundColor =  "";
                element.dataset.active = "false";

                let codeElement = element.parentElement.parentElement;
                let codeSubscriptionElement = codeElement.children[1];
                let codeSubscriptionElementChildren = codeSubscriptionElement.children;
                
                for (let i = 0; i < codeSubscriptionElementChildren.length; i++)
                {
                    codeSubscriptionElementChildren[i].style.backgroundColor =  "";
                    codeSubscriptionElementChildren[i].dataset.active = "false";
                }
            }

        } else {

            if (confirm("Czy chcesz WŁĄCZYĆ tę lokalizacje w danym kodzie")) 
            {
                let propertyId = element.dataset.property_id;
                let codeId = codeElement.dataset.code_id;
                
                setChosenProperty(propertyId, codeId, 0);
                element.style.backgroundColor = "lightskyblue";
                element.dataset.active = "true";
            }
        }  
    });
    
    $("ul.subscriptions > li.form-control").click(function (event) {
        
        let element = event.target; 
        let question;
        
        if (element.dataset.active === 'true')
        {
            question = "Czy chcesz usunąć subskrypcje z danego kodu?";
            
        } else {
            
            question = "Czy chcesz dodać subskrypcje do danego kodu?";
        }
        
        if (confirm(question)) 
        {
            // check and set property to code
            let subscriptionListParentElement = element.parentElement.parentElement;
            let codePropertyElement = subscriptionListParentElement.firstElementChild;
            let codePropertyElementChildren = codePropertyElement.children;
            let isPropertySet = false;
            
            for (let i = 0; i < codePropertyElementChildren.length; i++) 
            {
                propertyId = codePropertyElementChildren[i].dataset.property_id;
                
                if (codePropertyElementChildren[i].dataset.active == "true")
                {
                    isPropertySet = true;
                }
            }
            
            if (!isPropertySet)
            {
                let code_id = subscriptionListParentElement.dataset.code_id;
                let subscription_id = element.dataset.subscription_id;
                
                setChosenProperty(propertyId, code_id, subscription_id);
                
                for (let i = 0; i < codePropertyElementChildren.length; i++) 
                {
                    codePropertyElementChildren[i].style.backgroundColor = "lightskyblue";
                    codePropertyElementChildren[i].dataset.active = "true";
                }
                
                isPropertySet = true;
            }
            
            // change code's subscription
            if (isPropertySet)
            {
                let chosenPropertyId = element.parentElement.dataset.chosen_property_id;
                let subscriptionId = element.dataset.subscription_id;

                if (element.dataset.active === "true")
                {
                    element.style.backgroundColor = "";
                    element.dataset.active = false;

                } else {

                    element.style.backgroundColor = "lightgreen";
                    element.dataset.active = true;
                }

                setSubscriptionToChosenPropertySubscription(chosenPropertyId, subscriptionId); 
                
            } else {
                
                element.style.backgroundColor = "lightgreen";
                element.dataset.active = "true";
                
                console.log('Nie można zmienić aktualnego stanu subskrypcji kodu');
            }
        }
    });
    
    function setChosenProperty(propertyId, codeId, subscriptionId = 0)
    {
        return fetch('http://localhost:8000/subscription/set-chosen-property', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                propertyId: parseInt(propertyId),
                codeId: parseInt(codeId),
                subscriptionId: parseInt(subscriptionId)
            })
        })
        .then((res) => res.json())
        .then((data) => {
            if (data.type === "success")
            {
                console.log(data.message);
                                
                document.querySelectorAll('[data-code_id]').forEach( 
                    function(item) {
                        if (item.dataset.code_id == codeId)
                        {
                            let grandChildren = item.children;
                            for (var i = 0; i < grandChildren.length; i++) 
                            {
                                if (grandChildren[i].className == "property")
                                {
                                    let grandGrandChildren = grandChildren[i].children;
                                    for (var i = 0; i < grandGrandChildren.length; i++) 
                                    {
                                        if (grandGrandChildren[i].dataset.property_id == propertyId)
                                        {
                                            grandGrandChildren[i].dataset.chosen_property_id = data.newChosenPropertyId;
                                        }
                                    }
                                }                

                                if (grandChildren[i].className == "subscriptions")
                                {
                                    grandChildren[i].dataset.chosen_property_id = data.newChosenPropertyId;
                                }  
                            }
                        }
                    }
                );
            }
        });
    }
    
    function deleteChosenProperty(chosenPropertyId)
    {
        return fetch('http://localhost:8000/subscription/delete-chosen-property', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                chosenPropertyId: parseInt(chosenPropertyId)
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
    
    function setSubscriptionToChosenPropertySubscription(chosenPropertyId, subscriptionId)
    {
        return fetch('http://localhost:8000/subscription/set-subscription-to-chosen-property-subscription', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                chosenPropertyId: parseInt(chosenPropertyId),
                subscriptionId: parseInt(subscriptionId)
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