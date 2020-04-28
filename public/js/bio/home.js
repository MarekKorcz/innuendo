document.addEventListener("DOMContentLoaded", function(){
    
    document.getElementById("contact-message").addEventListener("submit", function(event) {
        
        event.preventDefault()
        
        let name = document.querySelector("[name='name']")
        let email = document.querySelector("[name='email']")
        let topic = document.querySelector("[name='topic']")
        let description = document.querySelector("[name='description']")
        
        if (name.value == '') {
            
            if(!name.classList.contains("input-error")) {
                
                name.classList.add("input-error")
            }
            
        } else {
            
            if(name.classList.contains("input-error")) {
                
                name.classList.remove("input-error")
            }
        }
        
        if (email.value == '') {
            
            if(!email.classList.contains("input-error")) {
                
                email.classList.add("input-error")
            }
            
        } else if (email.value !== '') {
            
            if (validateEmail(email.value)) {
                
                if(email.classList.contains("input-error")) {
                
                    email.classList.remove("input-error")
                }
                
            } else {
                
                if(!email.classList.contains("input-error")) {
                
                    email.classList.add("input-error")
                }
            }
        }
        
        if (topic.value == '') {
            
            if(!topic.classList.contains("input-error")) {
                
                topic.classList.add("input-error")
            }
            
        } else {
            
            if(topic.classList.contains("input-error")) {
                
                topic.classList.remove("input-error")
            }
        }
        
        if (description.value == '') {
            
            if(!description.classList.contains("input-error")) {
                
                description.classList.add("input-error")
            }
            
        } else {
            
            if(description.classList.contains("input-error")) {
                
                description.classList.remove("input-error")
            }
        }
        
        
        // check if can send contact message and if an answer is 'yes' - just do it
        let inputsWithSetError = document.getElementsByClassName('input-error')
        
        if (inputsWithSetError.length == 0) {
            
            bioContactMessage(name.value, email.value, topic.value, description.value)
        }
    });
    
    function validateEmail(email) 
    {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        
        return re.test(String(email).toLowerCase());
    }
    
    
    
    
    
    let confirmBanner = document.getElementById('confirm')
            
            
            
//            KI CHUJ????
            
            
    
    
    
    
    // send contact message
    function bioContactMessage(name, email, topic, description)
    {
        return fetch('http://localhost:8000/contact/message', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                name: name,
                email: email,
                topic: topic,
                description: description
            })
        })
        .then((res) => res.json())
        .then((data) => {
            
            // check if banner has already been used, if an answer is 'yes', clear it
            let confirmBanner = document.getElementById('confirm')
            confirmBanner.innerHTML = ""
    
            if (confirmBanner.classList.contains("confirm-positive")) {
                
                confirmBanner.classList.remove("confirm-positive")
            }
            
            if (confirmBanner.classList.contains("confirm-negative")) {
                
                confirmBanner.classList.remove("confirm-negative")
            }
            
            // display new response to user
            confirmBanner.innerHTML = data.message
            
            if (data.type === "success") {
                
                confirmBanner.classList.add("confirm-positive")
                
            } else if (data.type === "error") {
                
                confirmBanner.classList.add("confirm-negative")
            }
            
            confirmBanner.style.visibility = "visible"
        });
    }
})