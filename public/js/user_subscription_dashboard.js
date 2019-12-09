window.addEventListener('DOMContentLoaded', () => {
    
    let properties = document.getElementById("properties");
    let subscriptions = document.getElementById("subscriptions");
    
    properties.addEventListener("click", function(event)
    {
        let element = event.target;
        
        if (!element.classList.contains("wrapper") || !element.classList.contains("cont"))
        {
            eraseHighlightFromElementChildren(element);
            element.classList.add("highlighted");
            element.classList.remove("box");
            
            getPropertySubscriptions(element.dataset.property_id);
        }
    });
    
    subscriptions.addEventListener("click", function(event)
    {
        let element = event.target;
        
        if (!element.classList.contains("wrapper") || !element.classList.contains("cont"))
        {
            eraseHighlightFromElementChildren(element);
            element.classList.add("highlighted");
            element.classList.remove("box");
            
            let highlightedElements = document.getElementsByClassName('highlighted');
            let propertyId;
            
            for (let element of highlightedElements) 
            {
                if (element.dataset.property_id)
                {
                    propertyId = element.dataset.property_id;
                }
            }
            
            getSubscriptionSubstarts(propertyId, element.dataset.subscription_id);
        }
    });
    
    window.addEventListener("click", function(event)
    {        
        let element = event.target;
        
        if (element.classList.contains('substart'))
        {
            eraseHighlightFromElementChildren(element);
            element.classList.add("highlighted");
            element.classList.remove("box");
            
            let elementSubstartId = element.dataset.substart_id;
            getSubscriptionWorkers(elementSubstartId);
        }
    });
    
    function getPropertySubscriptions(propertyId)
    {
        return fetch('http://localhost:8000/user/get/property/subscription', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                propertyId: parseInt(propertyId)
            })
        })
        .then((res) => res.json())
        .then((data) => {
            if (data.type === "success")
            {
                let subscriptions = $("#subscriptions");
                subscriptions.empty();
                
                let substartsHeader = $("#substarts-header");
                substartsHeader.empty();
                
                let substarts = $("#substarts");
                substarts.empty();
                
                $.each(data.subscriptions, function (index, subscription) 
                {       
                    let subscriptionNode = null;
                        
                    subscriptionNode = `
                        <div class="box text-center" data-subscription_id="` + subscription.id + `">
                            <div class="data">
                                <p>` + subscription.name_description + `: <strong>` + subscription.name + `</strong></p>
                                <p>` + subscription.description_description + `: <strong>` + subscription.description + `</strong></p>
                                <p>` + subscription.old_price_description + `: <strong>` + subscription.old_price + `</strong></p>
                                <p>` + subscription.new_price_description + `: <strong>` + subscription.new_price + `</strong></p>
                                <p>` + subscription.quantity_description + `: <strong>` + subscription.quantity + `</strong></p>
                                <p>` + subscription.duration_description + `: <strong>` + subscription.duration + `</strong></p>
                            </div>
                        </div>
                    `;
                    
                    subscriptions.append(subscriptionNode);
                });
            }
        });
    }
    
    function getSubscriptionSubstarts(propertyId, subscriptionId)
    {
        return fetch('http://localhost:8000/user/get/subscription/substarts', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                propertyId: parseInt(propertyId),
                subscriptionId: parseInt(subscriptionId)
            })
        })
        .then((res) => res.json())
        .then((data) => {          
            
            let substartsHeader = $("#substarts-header");
            substartsHeader.empty();
                
            let substarts = $("#substarts");
            substarts.empty();
            
            if (data.type === "success")
            {
                substartsHeader.append(`
                    <h2>` + data.header +`:</h2>
                `);
                
                // >> adding substarts
                $.each(data.substarts, function (index, substart) 
                {
                    let substartNode = ``;
                    
                    if (substart.id == data.newestSubstart[0].id)
                    {
                        if (substart.isActive == 1)
                        {
                            substartNode = `
                                <div class="substart text-center highlighted" data-substart_id="` + data.newestSubstart[0].id + `">
                                    <div class="data">
                                        <p>
                                            ` + substart.start_date_description + `: <strong>` + substart.start_date + `</strong> 
                                            ` + substart.end_date_description + `: <strong>` + substart.end_date + `</strong>
                                        </p>                    
                                        <p>` + substart.isActiveMessage + `</p>
                                    </div>
                                    <a class="btn pallet-1-3" style="color: white;" target="_blanc" href="` + substart.button + `">
                                        ` + substart.button_description + `
                                    </a>
                                </div>
                            `;
                            
                        } else {
                            
                            substartNode = `
                                <div class="substart text-center highlighted" data-substart_id="` + data.newestSubstart[0].id +`">
                                    <div class="data">
                                        <p>
                                            ` + substart.start_date_description + `: <strong>` + substart.start_date + `</strong> 
                                            ` + substart.end_date_description + `: <strong>` + substart.end_date + `</strong>
                                        </p>                    
                                        <p>` + substart.isActiveMessage + `</p>
                                    </div>
                                </div>
                            `;
                        }
                        
                    } else {
                        
                        if (substart.isActive == 1)
                        {
                            substartNode = `
                                <div class="substart text-center" data-substart_id="` + substart.id +`">
                                    <div class="data">
                                        <p>
                                            ` + substart.start_date_description + `: <strong>` + substart.start_date + `</strong> 
                                            ` + substart.end_date_description + `: <strong>` + substart.end_date + `</strong>
                                        </p>                    
                                        <p>` + substart.isActiveMessage + `</p>
                                    </div>
                                    <a class="btn pallet-1-3" style="color: white;" target="_blanc" href="` + substart.button + `">
                                        ` + substart.button_description + `
                                    </a>
                                </div>
                            `;
                            
                        } else {
                            
                            substartNode = `
                                <div class="substart text-center" data-substart_id="` + substart.id +`">
                                    <div class="data">
                                        <p>
                                            ` + substart.start_date_description + `: <strong>` + substart.start_date + `</strong> 
                                            ` + substart.end_date_description + `: <strong>` + substart.end_date + `</strong>
                                        </p>                    
                                        <p>` + substart.isActiveMessage + `</p>
                                    </div>
                                </div>
                            `;
                        }
                    }
                    
                    substarts.append(substartNode);
                });  
                // << adding substarts
            }
        });
    }
    
    function eraseHighlightFromElementChildren (element)
    {
        let elementChildren = element.parentElement.children;
        
        for (let item of elementChildren) 
        {
            if (item.classList.contains("highlighted"))
            {
                item.classList.remove("highlighted");
                item.classList.add("box");
            }
        }
    }
});