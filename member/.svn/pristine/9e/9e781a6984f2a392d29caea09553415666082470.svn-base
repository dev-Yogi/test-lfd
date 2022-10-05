$(function () {
    updatePriceTable();
});

function updatePriceTable() {
    console.log('updatePriceTable');
    var product_id = $('#product_id').val();
    if (product_id) {
        var product_type = $("#type:checked").val();
        product_type = product_type.replace('-', '_');
        var product = getProduct(product_id);
        var price = Number(product['price_' + product_type]).toFixed(2).toLocaleString();
        
        $('#product_label').text(product.label);
        $('#product_type').text(capitalize(product_type));
        $('#product_price').text(price);
        $('#product_total').text('$' + price);
    }
}

function updateForm() {
    console.log('updateForm');
    var options = $('select#member_id')[0].selectize.options;
    var value = $('select#member_id').val();

    $('[name=first_name]').val(options[value].first_name);
    $('[name=last_name]').val(options[value].last_name);
    $('[name=email]').val(options[value].email);
}

function getProduct(product_id) {
    for (let index = 0; index < products.length; index++) {
        const product = products[index];
        if (product.id == product_id) {
            return product;
        }
    }
}
function capitalize(str) {
    strVal = '';
    str = str.split(' ');
    for (var chr = 0; chr < str.length; chr++) {
        strVal += str[chr].substring(0, 1).toUpperCase() + str[chr].substring(1, str[chr].length) + ' '
    }
    return strVal
}