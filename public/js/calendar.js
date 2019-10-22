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
});