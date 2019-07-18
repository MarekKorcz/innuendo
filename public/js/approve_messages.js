$(document).ready(function() 
{
    let scrollTime = 1000;
    
    scrollToLastMessage();
    
    $("#send-message").submit(function(event) 
    {        
        let textInput = $("input#text");
        
        if (textInput.length == 1)
        {
            if (textInput.val() === "") 
            {
                event.preventDefault();
            }
        }
    });
    
    function scrollToLastMessage()
    {
        let lastMessage = $("div[data-last]");
        
        if (lastMessage.length == 1)
        {
            $('html, body').animate({
                scrollTop: $(lastMessage).offset().top - 210
            }, scrollTime);
        }
    }
});