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
            let clickedElementGraphicId = event.target.getAttribute('data-graphic_id');
            
        console.log(clickedElementGraphicId);
        }
        
        
//        if (clickedElement.classList.contains('modal-open') && clickedElement.classList.contains('show'))
//        {
//            clickedElement.classList.remove("modal-open");
//            clickedElement.classList.remove("show");
//
//            backgroundDiv.classList.remove("dark");
//        }
    });
    
    function getEmployeeGraphic(graphicId)
    {
        return fetch('http://localhost:8000/employee/get-graphic', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                graphicId: graphicId
            })
        })
        .then((res) => res.json())
        .then((data) => {
            if (data.type === "success")
            {
                console.log(data.graphic);
            }
        });
    }
});