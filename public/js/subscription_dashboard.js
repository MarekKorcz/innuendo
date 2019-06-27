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
            
            getSubscriptionSubstarts(propertyId, element.dataset.subscription_id)
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
                let subscriptions = $("#subscriptions");
                subscriptions.empty();
                
                let substartsHeader = $("#substarts-header");
                substartsHeader.empty();
                
                let substarts = $("#substarts");
                substarts.empty();
                
                let workers = $("#workers");
                workers.empty();
                
                $.each(data.propertySubscriptions, function (index, subscription) 
                {       
                    let subscriptionNode = null;
                    
                    if (subscription.isPurchased === false)
                    {
                        subscriptionNode = `
                            <div class="box text-center" data-subscription_id="` + subscription.id + `">
                                <div class="data">
                                    <p>Nazwa: <strong>` + subscription.name + `</strong></p>
                                    ` + subscription.description + `
                                    <p>Cena regularna: <strong>` + subscription.old_price + `</strong></p>
                                    <p>Cena z subskrypcją: <strong>` + subscription.new_price + `</strong></p>
                                    <p>Ilość zabiegów w miesiącu: <strong>` + subscription.quantity + `</strong></p>
                                    <p>Czas subskrypcji (w miesiącach): <strong>` + subscription.duration + `</strong></p>
                                </div>
                                <a class="btn btn-primary" href="http://localhost:8000/boss/subscription/purchase/` + subscription.property_id + `/` + subscription.id + `">
                                    Kup subskrypcje
                                </a>
                            </div>
                        `;
                        
                    } else {
                        
                        subscriptionNode = `
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
                    }
                    
                    subscriptions.append(subscriptionNode);
                });
            }
        });
    }
    
    function getSubscriptionSubstarts(propertyId, subscriptionId)
    {
        return fetch('http://localhost:8000/boss/get/subscription/substarts', {
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
            
            let workers = $("#workers");
            workers.empty();
            
            if (data.type === "success")
            {
                substartsHeader.append(`
                    <h2>Okres trwania subskrypcji:</h2>
                `);
                
                // >> adding substarts
                $.each(data.substarts, function (index, substart) 
                {
                    let substartNode = ``;
                    
                    if (substart.id == data.newestSubstart[0].id)
                    {
                        substartNode = `
                            <div class="substart text-center highlighted" data-substart_id="` + data.newestSubstart[0].id +`">
                                <div class="data">
                                    <p>
                                        Od: <strong>` + substart.start_date + `</strong> 
                                        do: <strong>` + substart.end_date + `</strong>
                                    </p>                    
                                    <p>` + substart.isActiveMessage + `</p>
                                </div>
                                <a class="btn btn-primary" href="http://localhost:8000/boss/subscription/invoices/` + substart.id + `">
                                    Rozliczenia
                                </a>
                            </div>
                        `;
                        
                    } else {
                        
                        substartNode = `
                            <div class="substart text-center" data-substart_id="` + substart.id +`">
                                <div class="data">
                                    <p>
                                        Od: <strong>` + substart.start_date + `</strong> 
                                        do: <strong>` + substart.end_date + `</strong>
                                    </p>                    
                                    <p>` + substart.isActiveMessage + `</p>
                                </div>
                                <a class="btn btn-primary" href="http://localhost:8000/boss/subscription/invoices/` + substart.id + `">
                                    Rozliczenia
                                </a>
                            </div>
                        `;
                    }
                    
                    substarts.append(substartNode);
                });  
                // << adding substarts
                
                // >> adding workers
                if (data.workers.length > 0)
                {
                    let workersTab = `
                        <div class="text-center">                        
                            <p>
                                <h2>Osoby przypisane do danej subskrypcji:</h2>
                                <a class="btn btn-primary" href="http://localhost:8000/boss/worker/appointment/list/` + data.lastSubstartId + `/0">
                                    Wszystkie wizyty
                                </a>
                            </p>
                        </div>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>                
                                    <td>Imie</td>
                                    <td>Nazwisko</td>
                                    <td>Email</td>
                                    <td>Telefon</td>
                                    <td>Wizyty</td>
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
                                <td>
                                    <a class="btn btn-primary" href="http://localhost:8000/boss/worker/appointment/list/` + data.lastSubstartId + `/` + worker.id + `">
                                        Pokaż
                                    </a>
                                </td>
                            </tr>
                        `;

                        workersTable.append(workerNode);
                    });

                } else if (data.workers.length == 0) {

                    let workersTab = `
                        <div class="text-center">                        
                            <p>
                                <h2>Brak osób przypisanych do danej subskrypcji</h2>
                            </p>
                        </div>
                    `;

                    workers.append(workersTab);
                }
                // << adding workers
            }
        });
    }
    
    function getSubscriptionWorkers(substartId)
    {
        return fetch('http://localhost:8000/boss/get/subscription/workers', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                substartId: parseInt(substartId)
            })
        })
        .then((res) => res.json())
        .then((data) => {
            let workers = $("#workers");
            workers.empty();
                
            if (data.type === "success")
            {
                let workersTab = `
                    <div class="text-center">                        
                        <p>
                            <h2>Osoby przypisane do danej subskrypcji:</h2>
                            <a class="btn btn-primary" href="http://localhost:8000/boss/worker/appointment/list/` + data.substartId + `/0">
                                Wszystkie wizyty
                            </a>
                        </p>
                    </div>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>                
                                <td>Imie</td>
                                <td>Nazwisko</td>
                                <td>Email</td>
                                <td>Telefon</td>
                                <td>Wizyty</td>
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
                            <td>
                                <a class="btn btn-primary" href="http://localhost:8000/boss/worker/appointment/list/` + data.substartId + `/` + worker.id + `">
                                    Pokaż
                                </a>
                            </td>
                        </tr>
                    `;
                    
                    workersTable.append(workerNode);
                });
                
            } else if (data.type === "error") {
                
                let workersTab = `
                    <div class="text-center">                        
                        <p>
                            <h2>Brak osób przypisanych do danej subskrypcji</h2>
                        </p>
                    </div>
                `;
                
                workers.append(workersTab);
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