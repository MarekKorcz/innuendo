document.addEventListener("DOMContentLoaded", function(){
    
    document.getElementById("contact-message").addEventListener("submit", function(event) {
        
        let name = document.querySelector("[name='name']")
        let email = document.querySelector("[name='email']")
        let topic = document.querySelector("[name='topic']")
        let description = document.querySelector("[name='description']")
        
        if (name.value == '') {
            
            if(!name.classList.contains("input-error")) {
                
                name.classList.add("input-error")
            }
            
            event.preventDefault()
            
        } else {
            
            if(name.classList.contains("input-error")) {
                
                name.classList.remove("input-error")
            }
        }
        
        if (email.value == '') {
            
            if(!email.classList.contains("input-error")) {
                
                email.classList.add("input-error")
            }
            
            event.preventDefault()
            
        } else if (email.value !== '') {
            
            if (validateEmail(email.value)) {
                
                if(email.classList.contains("input-error")) {
                
                    email.classList.remove("input-error")
                }
                
            } else {
                
                if(!email.classList.contains("input-error")) {
                
                    email.classList.add("input-error")
                }

                event.preventDefault()
            }
        }
        
        if (topic.value == '') {
            
            if(!topic.classList.contains("input-error")) {
                
                topic.classList.add("input-error")
            }
            
            event.preventDefault()
            
        } else {
            
            if(topic.classList.contains("input-error")) {
                
                topic.classList.remove("input-error")
            }
        }
        
        if (description.value == '') {
            
            if(!description.classList.contains("input-error")) {
                
                description.classList.add("input-error")
            }
            
            event.preventDefault()
            
        } else {
            
            if(description.classList.contains("input-error")) {
                
                description.classList.remove("input-error")
            }
        }
    });
    
    function validateEmail(email) 
    {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        
        return re.test(String(email).toLowerCase());
    }
})