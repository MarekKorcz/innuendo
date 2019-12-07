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
                                    <p>` + subscription.name_description + `: <strong>` + subscription.name + `</strong></p>
                                    <p>` + subscription.description_description + `: <strong>` + subscription.description + `</strong></p> 
                                    <p>` + subscription.old_price_description + `: <strong>` + subscription.old_price + `</strong></p>
                                    <p>` + subscription.new_price_description + `: <strong>` + subscription.new_price + `</strong></p>
                                    <p>` + subscription.quantity_description + `: <strong>` + subscription.quantity + `</strong></p>
                                    <p>` + subscription.duration_description + `: <strong>` + subscription.duration + `</strong></p>
                                </div>
                                <a class="btn btn-primary" href="` + subscription.button + `">
                                    ` + subscription.button_description + `
                                </a>
                            </div>
                        `;
                        
                    } else {
                        
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
                                    <a class="btn btn-primary" href="` + substart.button + `">
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
                                    <a class="btn btn-primary" href="` + substart.button + `">
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
                
                // >> adding workers
                if (data.workers.length > 0)
                {
                    let workersTab = `
                        <div class="text-center">                        
                            <div id="button-space" style="padding: 1rem;">
                                <h2> ` + data.header_workers + `:</h2>
                                <a class="btn pallet-1-3" style="color: white;" href="` + data.subscription_workers_edit_button + `">
                                    ` + data.subscription_workers_edit_button_description + `
                                </a>
                                <a class="btn pallet-2-2" style="color: white;" href="` + data.worker_appointment_list_button + `">
                                    ` + data.worker_appointment_list_button_description + `
                                </a>
                            </div>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>                
                                        <td>` + data.name_description + `</td>
                                        <td>` + data.email_description + `</td>
                                        <td>` + data.phone_number_description + `</td>
                                        <td>` + data.appointments_description + `</td>
                                    </tr>
                                </thead>
                                <tbody id="workersTable"></tbody>
                            </table>
                        </div>
                    `;

                    workers.append(workersTab);

                    let workersTable = $("tbody#workersTable");                

                    $.each(data.workers, function (index, worker) 
                    {
                        let workerNode = `
                            <tr>
                                <td>` + worker.name + ` ` + worker.surname + `</td>
                                <td>` + worker.email + `</td>
                                <td>` + worker.phone_number + `</td>
                                <td>
                                    <a class="btn pallet-2-2" style="color: white;" href="` + worker.workers_appointment_show_button + `">
                                        ` + data.show_button_description + `
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
                                <h2>` + data.no_people_assigned_to_subscription + `</h2>
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
                        <div id="button-space" style="padding: 1rem;">
                            <h2>` + data.header_workers + `:</h2>
                            <a class="btn pallet-1-3" style="color: white;" href="` + data.subscription_workers_edit_button + `">
                                ` + data.subscription_workers_edit_button_description + `
                            </a>
                            <a class="btn pallet-2-2" style="color: white;" href="` + data.worker_appointment_list_button + `">
                                ` + data.worker_appointment_list_button_description + `
                            </a>
                        </div>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>                
                                    <td>` + data.name_description + `</td>
                                    <td>` + data.email_description + `</td>
                                    <td>` + data.phone_number_description + `</td>
                                    <td>` + data.appointments_description + `</td>
                                </tr>
                            </thead>
                            <tbody id="workersTable"></tbody>
                        </table>
                    </div>
                `;
                
                workers.append(workersTab);
                
                let workersTable = $("tbody#workersTable");
                
                $.each(data.workers, function (index, worker) 
                {
                    let workerNode = `
                        <tr>
                            <td>` + worker.name + ` ` + worker.surname + `</td>
                            <td>` + worker.email + `</td>
                            <td>` + worker.phone_number + `</td>
                            <td>
                                <a class="btn pallet-2-2" style="color: white;" href="` + worker.workers_appointment_show_button + `">
                                    ` + data.show_button_description + `
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
                            <h2>` + data.no_people_assigned_to_subscription + `</h2>
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