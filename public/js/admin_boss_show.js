$(document).ready(function() {
    
    $(document).on("change", "#is-boss", function () 
    {
        let selectElement = document.getElementById('is-boss')
        let selectedValue = selectElement.options[selectElement.selectedIndex].value
        
        if (selectedValue == 'false')
        {
            let bossId = selectElement.dataset.boss_id
            
            getPotentiallyNewBosses(bossId)
            
        } else if (selectedValue == 'true') {
            
            // grab 'new boss element' and remove everything from it
            let newBossElement = document.getElementById('new-boss-element')
            newBossElement.innerText = ''
        }
    });
    
    // function for insterting boss option elements to choose in order to 
    // give admin a chance to change boss_id for its employees
    function getPotentiallyNewBosses(bossId)
    {
        return fetch('http://localhost:8000/admin/get-potentially-new-bosses', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                bossId: bossId
            })
        })
        .then((res) => res.json())
        .then((data) => {
            if (data.type === "success")
            {
                if (data.bosses.length > 0)
                {
                    // grab 'new boss element'
                    let newBossElement = document.getElementById('new-boss-element')
                    
                    let labelElement = document.createElement('label')
                    labelElement.setAttribute('for', 'new_boss')
                    labelElement.innerText = data.label_description
                    
                    newBossElement.append(labelElement)
                    
                    // creates 'select element'
                    let newBossSelectElement = document.createElement('select')
                    newBossSelectElement.setAttribute('id', 'new-boss')
                    newBossSelectElement.setAttribute('class', 'form-control')
                    newBossSelectElement.setAttribute('name', 'new_boss')

                    // creates 'select option elements'
                    data.bosses.forEach((boss) => {

                        // creates 'select option element'
                        let newBossOptionElement = document.createElement('option')
                        newBossOptionElement.setAttribute('value', boss.id)
                        newBossOptionElement.setAttribute('selected', false)
                        newBossOptionElement.innerText = boss.name
                        
                        // appends 'select option element' to 'select element'
                        newBossSelectElement.append(newBossOptionElement)
                    })

                    // appends 'select element' to 'new boss element'
                    newBossElement.append(newBossSelectElement)
                }
            }
        });
    }
});