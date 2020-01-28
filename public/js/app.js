$(function() {
    
    let bannerElement = document.getElementById('banner');
    
    if (bannerElement !== null)
    {
        setBannerHeight(bannerElement);
        
    }   
    
    window.addEventListener('resize', function() 
    {
        if (bannerElement !== null)
        {
            setBannerHeight(bannerElement);
        }
    });
    
    let acceptButton = document.getElementById('understand');
    
    if (acceptButton)
    {
        acceptButton.addEventListener("click", function() 
        {
            acceptTerms();
            
            $("#banner").remove();
        });
    }
    
    function acceptTerms()
    {
        return fetch('http://localhost:8000/accept-terms', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            mode: 'cors'
        })
        .then((res) => res.json())
        .then((data) => {
            
        });
    }
    
    function setBannerHeight(bannerElement) {
                
        let windowWidth = window.innerWidth;
        
        if (windowWidth > 1200)
        {
            bannerElement.style.top = "85%";
            bannerElement.style.height = "15%";
            
        } else if (windowWidth > 900) {
            
            bannerElement.style.top = "80%";
            bannerElement.style.height = "20%";
            
        } else if (windowWidth > 400) {
            
            bannerElement.style.top = "75%";
            bannerElement.style.height = "25%";
            
        } else if (windowWidth > 250) {
            
            bannerElement.style.top = "65%";
            bannerElement.style.height = "35%";
        }
    }
});