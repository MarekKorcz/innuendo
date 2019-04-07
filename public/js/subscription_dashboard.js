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
            
            getSubscriptionWorkers(propertyId, element.dataset.subscription_id)
        }
    });
    
    function getPropertySubscriptions(propertyId)
    {
        return fetch('http://localhost:8000/boss/get/property/subscription', {
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
                console.log(data.message);
                
                let subscriptions = $("#subscriptions");
                subscriptions.empty();
                
                let workers = $("#workers");
                workers.empty();
                
                $.each(data.propertySubscriptions, function (index, subscription) 
                {                    
                    let subscriptionNode = `
                        <div class="box text-center" data-subscription_id="` + subscription.id + `">
                            <div class="data">
                                <p>Nazwa: <strong>` + subscription.name + `</strong></p>
                                ` + subscription.description + `
                                <p>Cena regularna: <strong>` + subscription.old_price + `</strong></p>
                                <p>Cena z subskrypcją: <strong>` + subscription.new_price + `</strong></p>
                                <p>Ilość zabiegów w miesiącu: <strong>` + subscription.quantity + `</strong></p>
                                <p>Czas subskrypcji (w miesiącach): <strong>` + subscription.duration + `</strong></p>
                            </div>
                        </div>
                    `;
                    
                    subscriptions.append(subscriptionNode);
                });
            }
        });
    }
    
    function getSubscriptionWorkers(propertyId, subscriptionId)
    {
        return fetch('http://localhost:8000/boss/get/subscription/workers', {
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
            if (data.type === "success")
            {
                console.log(data.message);
                
                let workers = $("#workers");
                workers.empty();
                
                let workersTab = `
                    <div class="text-center">
                        <h2>Pracownicy przypisani do danej subskrypcji:</h2>
                    </div>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>                
                                <td>Imie</td>
                                <td>Nazwisko</td>
                                <td>Email</td>
                                <td>Telefon</td>
                                <td>Akcja</td>
                            </tr>
                        </thead>
                        <tbody id="workersTable"></tbody>
                    </table>
                `;
                
                workers.append(workersTab);
                
                let workersTable = $("tbody#workersTable");                
                
                $.each(data.workers, function (index, worker) 
                {
                    let workerNode = `
                        <tr>
                            <td>` + worker.name + `</td>
                            <td>` + worker.surname + `</td>
                            <td>` + worker.email + `</td>
                            <td>` + worker.phone_number + `</td>
                            <td>` + worker.name + `</td>
                        </tr>
                    `;
                    
                    workersTable.append(workerNode);
                });
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