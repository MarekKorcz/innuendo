$(document).ready(function() 
{
    let scrollTime = 1000;
    
    scrollToChosenMessage();
    handleShowingNewMessages();
    
    function handleShowingNewMessages ()
    {
        let needToBeSeen = false;
        let adminMessages = $(".admin-message");

        if (adminMessages.length > 0)
        {
            adminMessages.each(function() {

                if ($(this).data('status') == 0)
                {
                    needToBeSeen = true;
                }
            });

            if (needToBeSeen)
            {
                let navBarHeight = $("nav").outerHeight();
                let titleDivHeight = $("#title").outerHeight();
                let infoDivHeight = $("#info").outerHeight();
                
                let heightOfElementUpperMessages = navBarHeight + titleDivHeight + infoDivHeight;
                let messagesHeadDivHeight = $("#messages-head").outerHeight();

                heightOfElementUpperMessages = heightOfElementUpperMessages + messagesHeadDivHeight;
                
                let targetMessageElement = null;
                let unseenMessageElements = [];
                
                $("#messages").children().each(function() {
                    
                    if ($(this).length > 0)
                    {
                        $(this).children().each(function() {
                    
                            if ($(this).length > 0)
                            {
                                $(this).children().each(function() {
                    
                                    if ($(this).length > 0)
                                    {
                                        if ($(this).data('status') == 0)
                                        {
                                            if (targetMessageElement == null)
                                            {
                                                targetMessageElement = $(this);
                                            }

                                            unseenMessageElements.push($(this));

                                        } else {

                                            heightOfElementUpperMessages += $(this).outerHeight();
                                        }
                                    }
                                });
                            }
                        });
                    }
                });
                
                if (targetMessageElement.length > 0)
                {
                    let button = `
                        <div class="show-new-message-button">
                            <a id="showNewMessageBtn" class="btn btn-info btn-lg">
                                Pokaż nowe wiadomoścu
                            </a>
                        </div>
                    `;
                    
                    $("#title").append(button);
                    
                    $("#title").on("click", "#showNewMessageBtn", function() {
                        
                        $('html, body').animate({
                            scrollTop: targetMessageElement.offset().top - 150
                        }, scrollTime);
                        
                        $(".show-new-message-button").remove();
                        
                        setTimeout(function(){ 
                            
                            for (let i = 0; i < unseenMessageElements.length; ++i) 
                            {
                                markMessageAsDisplayed(unseenMessageElements[i].data('message_id'));
                                unseenMessageElements[i].fadeOut(333).fadeIn(333);
                            }
                        
                        }, scrollTime * 0.8);
                    });
                    
                    $(window).on("scroll", function() {
                        
                        let showNewMessageButton = $(".show-new-message-button");
                        
                        if ($(window).scrollTop() > heightOfElementUpperMessages - 240 && showNewMessageButton.length > 0)
                        {
                            showNewMessageButton.remove();

                            for (let i = 0; i < unseenMessageElements.length; ++i) 
                            {                                
                                    markMessageAsDisplayed(unseenMessageElements[i].data('message_id'));
                                    unseenMessageElements[i].fadeOut(333).fadeIn(333);
                            }
                        }
                    });
                }
            }
        }
    }
    
    function scrollToChosenMessage ()
    {
        let chosenMessageDiv = $("#chosenMessageId");
        
        if (chosenMessageDiv.length == 1)
        {
            let chosenMessageId = chosenMessageDiv.data('chosen_message_id');
            
            if (chosenMessageId !== 0)
            {
                let messageElement = $("div").find("[data-message_id='" + chosenMessageId + "']"); ;

                if (messageElement.length > 0)
                {
                    $('html, body').animate({
                        scrollTop: $(messageElement).offset().top - 120
                    }, scrollTime);
                    
                    setTimeout(function(){ 
                        messageElement.fadeOut(333).fadeIn(333);
                    }, scrollTime * 0.8);
                }
            }
        }
    }
    
    function markMessageAsDisplayed(messageId)
    {
        return fetch('http://localhost:8000/boss/mark-message-as-displayed', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                messageId: parseInt(messageId)
            })
        })
        .then((res) => res.json())
        .then((data) => {
            
//            console.log(data.type);
        });
    }
});