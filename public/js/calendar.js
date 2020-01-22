$(document).ready(function() 
{
    $(document).on("click", ".appointment-term", function (event) 
    {
        var id = $(event.target).data('id');
        
        if (id !== undefined)
        {
            $(".modal-body #appointmentTerm").val(id);
            $( "label[name='appointmentTerm']" ).text("Godzina wizyty: " + id);
        }
    });
   
    document.addEventListener("click", function(event) 
    {
        if (event.target.getAttribute('data-graphic_id') !== null)
        {
            let clicedElement = event.target;
            let clickedElementGraphicId = clicedElement.getAttribute('data-graphic_id');
            let currentDayId = document.getElementById('graphic-employees-buttons').getAttribute('data-current_day_id');
            
            // get graphic
            getEmployeeGraphic(clickedElementGraphicId, currentDayId);
            
            // remove btn-success from not clicked button
            let buttonsElement = document.getElementsByClassName('btn-group');
            
            for (let button of buttonsElement[0].children)
            {
                if (button.classList.contains('btn-success'))
                {
                    button.classList.remove('btn-success');
                    button.classList.add('btn-info');
                }
            }
            
            // add btn-success to clicked button
            clicedElement.classList.remove('btn-info')
            clicedElement.classList.add('btn-success')
            
            // change hidden graphicId input value
            document.getElementsByName("graphicId")[0].value = clickedElementGraphicId
        }
    });
    
    function getEmployeeGraphic(graphicId, currentDayId)
    {
        return fetch('http://localhost:8000/user/employee/get-graphic', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                graphicId: graphicId,
                currentDayId: currentDayId
            })
        })
        .then((res) => res.json())
        .then((data) => {
            if (data.type === "success" && data.graphic.length > 0)
            {
                let graphicElement = $("div#graphic");
                graphicElement.html("");
                
                let graphic = data.graphic;
                
                for (let i = 0; i < graphic.length; i++)
                {
                    var element = null;
                            
                    if (graphic[i]['appointmentLimit'] == 0)
                    {
                        if (graphic[i]['canMakeAnAppointment'])
                        {
                            element = `
                                <div class="appointment">
                                    <div class="box">` + graphic[i]['time'] + `</div>
                                    <div class="appointment-term box-1 pallet-1-3">
                                        <div class="appointment-info">
                                            <a style="color: white;" href="#makeAnAppointment" data-toggle="modal" data-id="` + graphic[i]['time'] + `" title="` + data.clickToMakeReservationDescription + `">
                                                ` + data.availableDescription + `
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            `;
                    
                        } else {
                            
                            element = `
                                <div class="appointment">
                                    <div class="box">` + graphic[i]['time'] + `</div>
                                    <div class="appointment-term box-1 pallet-1-4">
                                        <div class="appointment-info">
                                            ` + data.availableDescription + `
                                        </div>
                                    </div>
                                </div>
                            `;
                        }
                        
                    } else if (graphic[i]['appointmentLimit'] == 1) {
                        
                        if (graphic[i]['appointmentId'] !== 0)
                        {
                            if (graphic[i]['ownAppointment']) {
                                
                                element = `
                                    <div class="appointment">
                                        <div class="box">` + graphic[i]['time'] + `</div>
                                        <div class="appointment-term box-1 pallet-1-2">
                                            <div class="appointment-info">
                                                <a style="color: white;" href="` + graphic[i]['ownAppointmentHref'] + `" target="_blank">
                                                    ` + data.appointmentDetailsDescription + `
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                
                            } else {
                                
                                element = `
                                    <div class="appointment">
                                        <div class="box">` + graphic[i]['time'] + `</div>
                                        <div class="appointment-term box-1 pallet-2-2">
                                            <div class="appointment-info">
                                                ` + data.appointmentBookedDescription + `
                                            </div>
                                        </div>
                                    </div>
                                `;
                            }
                        }
                        
                    } else if (graphic[i]['appointmentLimit'] == 2) {
                        
                        if (graphic[i]['appointmentId'] !== 0)
                        {
                            if (graphic[i]['ownAppointment']) {
                                
                                element = `
                                    <div class="appointment">
                                        <div class="box">` + graphic[i]['time'][0] + `</div>
                                        <div class="box">` + graphic[i]['time'][1] + `</div>
                                        <div class="appointment-term box-2 pallet-1-2">
                                            <div class="appointment-info">
                                                <a style="color: white;" href="` + graphic[i]['ownAppointmentHref'] + `" target="_blank">
                                                    ` + data.appointmentDetailsDescription + `
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                
                            } else {
                                
                                element = `
                                    <div class="appointment">
                                        <div class="box">` + graphic[i]['time'][0] + `</div>
                                        <div class="box">` + graphic[i]['time'][1] + `</div>
                                        <div class="appointment-term box-2 pallet-2-2">
                                            <div class="appointment-info">
                                                ` + data.appointmentBookedDescription + `
                                            </div>
                                        </div>
                                    </div>
                                `;
                            }
                        }
                        
                    } else if (graphic[i]['appointmentLimit'] == 3) {
                        
                        if (graphic[i]['appointmentId'] !== 0)
                        {
                            if (graphic[i]['ownAppointment']) {
                                
                                element = `
                                    <div class="appointment">
                                        <div class="box">` + graphic[i]['time'][0] + `</div>
                                        <div class="box">` + graphic[i]['time'][1] + `</div>
                                        <div class="box">` + graphic[i]['time'][2] + `</div>
                                        <div class="appointment-term box-3 pallet-1-2">
                                            <div class="appointment-info">
                                                <a style="color: white;" href="` + graphic[i]['ownAppointmentHref'] + `" target="_blank">
                                                    ` + data.appointmentDetailsDescription + `
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                
                            } else {
                                
                                element = `
                                    <div class="appointment">
                                        <div class="box">` + graphic[i]['time'][0] + `</div>
                                        <div class="box">` + graphic[i]['time'][1] + `</div>
                                        <div class="box">` + graphic[i]['time'][2] + `</div>
                                        <div class="appointment-term box-3 pallet-2-2">
                                            <div class="appointment-info">
                                                ` + data.appointmentBookedDescription + `
                                            </div>
                                        </div>
                                    </div>
                                `;
                            }
                        }
                    }
                    
                    if (element !== null)
                    {
                        graphicElement.append(element);
                    }
                }
            }
        });
    }
});