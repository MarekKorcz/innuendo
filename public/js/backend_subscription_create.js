$(document).ready(function() {
    $("li.form-control").click(function (event) {
        
        let element = $(event.target);
                
        let items = $("#items");
        let itemId = element.val();
        
        let input = `
            <input type="hidden" value="` + itemId + `" name="items[]">
        `;

        if (element.attr("data-active") == "true")
        {
            element.css("background-color", "").attr("data-active", "false");
            $("input[value='" + itemId + "']").remove();

        } else {

            element.css("background-color", "lightgreen").attr("data-active", "true");
            items.append(input);
        }
    });
});