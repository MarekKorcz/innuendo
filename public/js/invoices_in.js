$(document).ready(function() 
{
    $(window).click(function(event) 
    {
        let element = $(event.target);
        
        if (element.hasClass('list-group-item'))
        {            
            if (!element.hasClass('clicked') && element.hasClass('existing-invoice'))
            {
                $("ul.list-group").children().each(function() 
                {
                    if (this.nodeName === "A")
                    {
                        if ($(this).children().hasClass('clicked') == true)
                        {
                            $(this).children().removeClass('clicked');
                        }
                    }
                });
                
                element.addClass('clicked');
            }
        }
    });
});
