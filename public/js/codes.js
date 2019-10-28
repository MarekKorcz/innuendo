$(document).ready(function() 
{
    handleTurnOnCodeRegistrationButtonActivation();
    
    let backgroundDiv = document.getElementById('background');
    
    document.addEventListener("click", function(event) 
    {
        let clickedElement = event.target;
        
        if (clickedElement.classList.contains('modal-open') && clickedElement.classList.contains('show'))
        {
            clickedElement.classList.remove("modal-open");
            clickedElement.classList.remove("show");

            backgroundDiv.classList.remove("dark");
        }
    });
    
    $(".copy-button").on('click', function(event) {
        
        let copyText = event.currentTarget.parentNode.children[0];
        copyText.select();
        document.execCommand("copy");
    });
    
    $(".close").on('click', function(event) 
    {
        let clickedModalWindow = event.target.parentElement.parentElement.parentElement;
        
        clickedModalWindow.classList.remove("modal-open");
        clickedModalWindow.classList.remove("show");
        
        backgroundDiv.classList.remove("dark");
    });
    
    $(".delete").on('click', function(event) 
    {
        event.preventDefault();
        
        let modalFormElement = document.querySelector("div#deleteCode form");
        
        modalFormElement.action = ("http://localhost:8000/code/" + event.target.dataset.code_id);
        
        let modalElement = document.getElementById('deleteCode');
        
        modalElement.classList.add("modal-open");
        modalElement.classList.add("show");

        backgroundDiv.classList.add("dark");
    });
    
    $("#addPropertyButton").on('click', function() 
    {
        let modalElement = document.getElementById('addProperty');
        
        let propertyId = modalElement.dataset.property_id;
        let codeId = modalElement.dataset.code_id;
        
        setChosenProperty(propertyId, codeId, 0);
        
        let propertyElement = document.querySelector("div[data-code_id='" + codeId + "'] li[data-property_id='" + propertyId + "']");
                
        propertyElement.classList.add('property-highlight');
        propertyElement.dataset.active = "true";
        
        modalElement.classList.remove("modal-open");
        modalElement.classList.remove("show");

        backgroundDiv.classList.remove("dark");
    });
    
    $("#removePropertyButton").on('click', function() 
    {
        let modalElement = document.getElementById('removeProperty');
        
        let propertyId = modalElement.dataset.property_id;
        let chosenPropertyId = modalElement.dataset.chosen_property_id;
        let codeId = modalElement.dataset.code_id;
        
        let propertyElement = document.querySelector("div[data-code_id='" + codeId + "'] li[data-property_id='" + propertyId + "']");
        
        deleteChosenProperty(chosenPropertyId);
                
        propertyElement.classList.remove('property-highlight');
        propertyElement.dataset.active = "false";
        
        let codeSubscriptionElement = document.querySelector("div[data-code_id='" + codeId + "'] ul.subscriptions");
        let codeSubscriptionElementChildren = codeSubscriptionElement.children;
                
        for (let i = 0; i < codeSubscriptionElementChildren.length; i++)
        {
            codeSubscriptionElementChildren[i].classList.remove('subscription-highlight');
            codeSubscriptionElementChildren[i].dataset.active = "false";
        }
        
        modalElement.classList.remove("modal-open");
        modalElement.classList.remove("show");

        backgroundDiv.classList.remove("dark");
    });
    
    $("#addSubscriptionButton").on('click', function() 
    {
        let modalElement = document.getElementById('addSubscription');
        
        let subscriptionId = modalElement.dataset.subscription_id;
        
        let codeId = modalElement.dataset.code_id;
        let propertyId = null;
            
        let codePropertyElement = document.querySelector("div[data-code_id='" + codeId + "'] ul.property");
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
            setChosenProperty(propertyId, codeId, subscriptionId);

            for (let i = 0; i < codePropertyElementChildren.length; i++) 
            {
                codePropertyElementChildren[i].classList.add('property-highlight');
                codePropertyElementChildren[i].dataset.active = "true";
            }

            isPropertySet = true;
        }        
        
        let subscriptionElement = document.querySelector("div[data-code_id='" + codeId + "'] li[data-subscription_id='" + subscriptionId + "']");
        
        if (isPropertySet)
        {
            if (subscriptionElement.dataset.active === "false")
            {
                subscriptionElement.classList.add('subscription-highlight');
                subscriptionElement.dataset.active = true;
            }
            
            let chosenPropertyId = subscriptionElement.parentElement.dataset.chosen_property_id;
            
            setSubscriptionToChosenPropertySubscription(chosenPropertyId, subscriptionId);
        }      
        
        modalElement.classList.remove("modal-open");
        modalElement.classList.remove("show");

        backgroundDiv.classList.remove("dark");
    });
    
    $("#removeSubscriptionButton").on('click', function() 
    {
        let modalElement = document.getElementById('removeSubscription');
        
        let subscriptionId = modalElement.dataset.subscription_id;
        let codeId = modalElement.dataset.code_id;
        
        let subscriptionElement = document.querySelector("div[data-code_id='" + codeId + "'] li[data-subscription_id='" + subscriptionId + "']");

        if (subscriptionElement.dataset.active === "true")
        {
            subscriptionElement.classList.remove('subscription-highlight');
            subscriptionElement.dataset.active = false;
        }
        
        let chosenPropertyId = subscriptionElement.parentElement.dataset.chosen_property_id;

        setSubscriptionToChosenPropertySubscription(chosenPropertyId, subscriptionId); 

        modalElement.classList.remove("modal-open");
        modalElement.classList.remove("show");

        backgroundDiv.classList.remove("dark");
    });
    
    $("ul.property > li.form-control").click(function (event) {

        let element = event.target;
        let codeElement = element.parentElement.parentElement;
        
        if (element.dataset.active === "true")
        {
            let propertyId = element.dataset.property_id;
            let chosenPropertyId = element.dataset.chosen_property_id;
            let codeId = element.parentElement.parentElement.dataset.code_id;

            let propertyModal = document.getElementById('removeProperty');

            propertyModal.dataset.property_id = propertyId;
            propertyModal.dataset.chosen_property_id = chosenPropertyId;
            propertyModal.dataset.code_id = codeId;

            propertyModal.classList.add('show');
            propertyModal.classList.add('modal-open');

            backgroundDiv.classList.add('dark');

        } else {
            
            let propertyId = element.dataset.property_id;
            let codeId = codeElement.dataset.code_id;
            
            let propertyModal = document.getElementById('addProperty');
            
            propertyModal.dataset.property_id = propertyId;
            propertyModal.dataset.code_id = codeId;
            
            propertyModal.classList.add('show');
            propertyModal.classList.add('modal-open');
            
            backgroundDiv.classList.add('dark');
        }  
    });
    
    $("ul.subscriptions > li.form-control").click(function (event) 
    {
        let element = event.target;
        let subscriptionId = element.dataset.subscription_id;
        let subscriptionModal = document.getElementById('removeSubscription');
            
        if (element.dataset.active == 'false')
        {
            subscriptionModal = document.getElementById('addSubscription');
        }

        let codeElement = element.parentElement.parentElement;            
        let codeId = codeElement.dataset.code_id;
        subscriptionModal.dataset.code_id = codeId;
        
        subscriptionModal.dataset.subscription_id = subscriptionId;

        subscriptionModal.classList.add('show');
        subscriptionModal.classList.add('modal-open');

        backgroundDiv.classList.add('dark');
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
            
            handleTurnOnCodeRegistrationButtonActivation();
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
                handleTurnOnCodeRegistrationButtonActivation();
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
                handleTurnOnCodeRegistrationButtonActivation();
            }
        });
    }
    
    function handleTurnOnCodeRegistrationButtonActivation()
    {        
        // get all code-items elements
        let codeItemsElements = document.getElementsByClassName("code-items");
        
        if (codeItemsElements.length > 0)
        {
            // iterate through all property and subscription elements to check whether at least one property 
            // and one subscription in code are activated by boss
            for (let codeItem of codeItemsElements) 
            {
                // set variables
                let activePropertyElements = false;
                let activeSubscriptionsElements = false;
            
                for (let ulItem of codeItem.children) 
                {
                    if (ulItem.className == "property")
                    {
                        for (let liItem of ulItem.children) 
                        {
                            if (liItem.dataset.active === "true")
                            {
                                activePropertyElements = true;
                            }
                        }
                    }
                    
                    if (ulItem.className == "subscriptions")
                    {
                        for (let liItem of ulItem.children) 
                        {
                            if (liItem.dataset.active === "true")
                            {
                                activeSubscriptionsElements = true;
                            }
                        }
                    }
                }
                
                // get code id and find each code`s submit button
                let codeId = codeItem.dataset.code_id;
                let activateRegistrationButton = document.body.querySelector("div[data-code_id='" + codeId + "'] + div > input[type='submit']");
                
                // decide whether enable or disable registration button in code
                if (activePropertyElements && activeSubscriptionsElements)
                {
                    activateRegistrationButton.removeAttribute("disabled");
                    
                } else {
                    
                    activateRegistrationButton.setAttribute("disabled", "disabled");
                }
            }
        }
    }
});