$(document).ready(function () {
    let form = document.getElementById('product_form');

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        $.ajax({
            url: '/admin/ajax/newProduct.php',
            type: 'post',
            data: { 
                typeID: document.getElementById('prudct_type').value,
                name: document.getElementById('product_name').value,
                manufacturer: document.getElementById('product_manufacturer').value,
                buyprice: document.getElementById('product_buy_price').value,
                sellprice: document.getElementById('product_sell_price').value
             },
            success: function (result) {
                if(JSON.parse(result).success){
                    alert('Product aangemaakt!');
                }
            }
        });

    });

});
