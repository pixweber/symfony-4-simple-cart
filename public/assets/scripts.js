// Bind data
$('.cart-item [type="number"]').change(function(){
    // Get quantity
    var quantity = $(this).val();

    // Get data-price
    var price = $(this).closest('.cart-item').attr('data-price');

    // Update data-quantity
    $(this).closest('.cart-item').attr('data-quantity', quantity);

    // Update data-total
    $(this).closest('.cart-item').attr('data-total', price * quantity);
});

$('#update-cart-form [type="submit"]').click(function(event){
    var cart_items_object = [];

    $('.cart-item').each(function(key, item){
        cart_items_object.push({
            productId: $(this).attr('data-product-id'),
            quantity: $(this).attr('data-quantity'),
            price: $(this).attr('data-price'),
            total: $(this).attr('data-total')
        });
    });

    // Update input value
    $('#cart-items-json').val(JSON.stringify(cart_items_object));
});