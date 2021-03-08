$(document).ready(function () {
    let locationBox = document.getElementById('delivery_order_location_box');
    let toolBox = document.getElementById('delivery_order_product_box');
    let toolDeliveryWrapper = document.getElementById('tool_delivery_order_wrapper');

    let submitButton = document.getElementById('submit_button');

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
                        option.innerHTML = tool.toolName;
                        toolBox.appendChild(option);
                    }
                }
            }
        });
    });

    toolBox.addEventListener('change', () => {
        if(!ChosenContains(tools[toolBox.value].toolID, tools[toolBox.value].locationID)){
            chosenTools.push(tools[toolBox.value]);
        }
        
        PrintChosenTools()
    });

    function ChosenContains(toolID, locationID){
        for(let tool of chosenTools){
            if(tool.toolID == toolID && tool.locationID == locationID) return true;
        }
        return false;
    }

    function PrintChosenTools(){
        toolDeliveryWrapper.innerHTML = '';

        for(let tool of chosenTools){
            let deliveryBox = document.createElement('div');
            deliveryBox.className = 'tool-delivery-order-box';

            let deliveryBoxName = document.createElement('div');
            deliveryBoxName.className = 'tool-delivery-order-box-name';
            deliveryBoxName.innerHTML = tool.locationName + ' - ' +tool.toolName;

            let deliveryBoxAmount = document.createElement('div');
            deliveryBoxAmount.className = 'tool-delivery-order-box-amount';

            let deliveryBoxAmountInput = document.createElement('input');
            deliveryBoxAmountInput.min = 0;
            deliveryBoxAmountInput.max = tool.stockAmount;
            deliveryBoxAmountInput.type = 'number';
            deliveryBoxAmountInput.id = 'tool_amount_' + tool.toolID;

            deliveryBoxAmount.appendChild(deliveryBoxAmountInput);
            deliveryBoxAmount.innerHTML += 'voorraad: ' + tool.stockAmount;

            deliveryBox.appendChild(deliveryBoxName);
            deliveryBox.appendChild(deliveryBoxAmount);

            toolDeliveryWrapper.appendChild(deliveryBox);
        }
    }


    //making the submit button work
    submitButton.addEventListener('click', () => {
        let data = [];
        for(let tool of chosenTools){
            let tempDataOBJ = {};

            tempDataOBJ.toolID = tool.toolID;
            tempDataOBJ.locationID = tool.locationID;
            tempDataOBJ.deliveryAmount = document.getElementById('tool_amount_' + tool.toolID).value == '' ? 0 : parseInt(document.getElementById('tool_amount_' + tool.toolID).value) <= tool.stockAmount ? parseInt(document.getElementById('tool_amount_' + tool.toolID).value) : tool.stockAmount;

            data.push(tempDataOBJ);
        }

        $.ajax({
            url: '/admin/ajax/registerOrder.php',
            type: 'post',
            data: { tools: data },
            success: function (result) {
                if(JSON.parse(result).success){
                    chosenTools = [];
                    toolDeliveryWrapper.innerHTML = '';
                    toolDeliveryWrapper.innerHTML = '<h1>Succesvol bestelling geregistreerd</h1>';
                }
            }
        });
    });
});
