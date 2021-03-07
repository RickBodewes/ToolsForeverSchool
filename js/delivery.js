$(document).ready(function () {
    let locationBox = document.getElementById('delivery_location_box');
    let toolBox = document.getElementById('delivery_product_box');

    let tools = [];

    let chosenTools = [];

    locationBox.addEventListener('change', () => {
        $.ajax({
            url: '/admin/ajax/fetchToolsOnStore.php',
            type: 'get',
            data: { locationID: locationBox.value },
            success: function (result) {
                result = JSON.parse(result);
                if(result.success){
                    //emptying the tools dropdown
                    tools = [];
                    toolBox.innerHTML = '<option disabled selected hidden>Kies een product</option>';

                    for(let tool of result.data){
                        tools[tool.toolID] = tool;

                        let option = document.createElement('option');
                        option.value = tool.toolID;
                        option.innerHTML = tool.name;
                        toolBox.appendChild(option);
                    }
                }
            }
        });
    });

    toolBox.addEventListener('change', () => {
        if(!ChosenContains(tools[toolBox.value].toolID)){
            chosenTools.push(tools[toolBox.value]);
        }
        
        console.log(chosenTools);
    });

    function ChosenContains(toolID){
        for(let tool of chosenTools){
            if(tool.toolID == toolID) return true;
        }
        return false;
    }

    function PrintChosenTools(){

    }

});
