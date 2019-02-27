$(document).ready(function() {
    $("#search").on('keyup', function() {
        $("#result").html('');
        let searchField = $("#search").val();
        
        if (searchField !== "") 
        {
            getUserFromDatabase(searchField);
        }
    });
    
    $("#result").on('click', function(event){
        
        let id = event.target.value;
        let name = $(event.target).data('name');
        
        $("#search").val(name);
        $("#userId").val(id);
        
        $("#result").html('');
    });
    
    function getUserFromDatabase(searchField)
    {
        return fetch('http://localhost:8000/employee/backend-appointment/get-user-from-database', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                searchField: searchField
            })
        })
        .then((res) => res.json())
        .then((data) => {            
            if (data.type === "success")
            {
                $.each(data.users, function(key, value){
                    $("#result").append('<li class="list-group-item" data-name="' + value.name + '" value="' + value.id + '">'+ value.name + ' | ' + value.email +'</li>');
                });
            }
        });
    }
});