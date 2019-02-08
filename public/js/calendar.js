$(document).ready(function() {
    $(document).on("click", ".appointment-term", function () {
        var myAppointmentTerm = $(this).data('id');
        $(".modal-body #appointmentTerm").val(myAppointmentTerm);
        $( "label[name='appointmentTerm']" ).text("Godzina wizyty: " + myAppointmentTerm);
   });
});