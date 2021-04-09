$(document).ready(function () {
    let form = document.getElementById('location_form');

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        $.ajax({
            url: '/admin/ajax/addProductToStore.php',
            type: 'post',
            data: { 
                locationID: document.getElementById('location_id').value,
                toolID: document.getElementById('prudct_name').value,
                stockAmount: document.getElementById('product_amount').value,
                minStockAmount: document.getElementById('min_product_amount').value
             },
            success: function (result) {
                console.log(result);

                if(JSON.parse(result).success){
                    location.reload();
                }
            }
        });

    });

});

function removeFromLocation(locationID, productID){
    if(confirm('Weet je zeker dat je dit product wilt verwijderen uit deze winkel?')){
        $.ajax({
            url: '/admin/ajax/removeStoreProduct.php',
            type: 'post',
            data: { 
                locationID: locationID,
                toolID: productID
             },
            success: function (result) {
    
                if(JSON.parse(result).success){
                    location.reload();
                }
            }
        }); 
    }
}