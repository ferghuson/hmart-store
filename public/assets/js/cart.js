/**
 * Created by Marshall.D.Teach on 30/03/2020.
 */

$(document).ready(function(){
    // ADD TO CART
    $('#add-to-cart').click(function(e) {
        e.preventDefault();
        var id = +$('#product').val();
        var quantity = +$('#quantity').val();

        var url = document.location.origin;
        url = url+'/cart/add/'+id+'/'+quantity;

        $.getJSON(url, function(data){
            $('.js-cart-items').text(data.items);
        });
    });

    // PURCHASING
    $('#purchasing').click(function(e) {

        e.preventDefault();
        var id = +$('#product').val();
        var quantity = +$('#quantity').val();

        var url = document.location.origin;
        url = url+'/cart/add/'+id+'/'+quantity;

        $.getJSON(url, function(){
            window.location.href = document.location.origin+'/cart';
        });
    });

    // UPDATE CART
    var quantity = $('.quantity');
    var product, price, newQuantity, total;

    $(quantity).each(function() {
        $(this).change(function () {
            product = $(this).siblings('input').val();
            price = +$('#js-'+product+'-price').text();
            newQuantity = $(this).val();
            total = price * newQuantity;

            $('#js-'+product+'-total').text(total);
            var url = document.location.origin;
            url = url+'/cart/update/'+product+'/'+newQuantity;
            $.getJSON(url, function(data){
                $('.js-cart-items').text(data.items);
                $('.js-cart-total').text(data.total);
            });
        });
    })
});