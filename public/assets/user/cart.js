var t;

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
addToCart = (product_id, product_min_order) => {
    clearTimeout(t);
    t = setTimeout(function () {
        let product_quantity = null;
        if ($('#product_quantity').length == 0) {
            product_quantity = product_min_order
        } else {
            if ($('#product_quantity').val() == 0) {
                product_quantity = product_min_order
            } else {
                product_quantity = $('#product_quantity').val()
            }
        }
        $.ajax({
            type: 'POST',
            url: '/ajax/cart-add',
            data: {
                id: product_id,
                quantity: product_quantity
            },
            success: function (obj) {
                $('#cart_logo').css('background', 'url(/assets/img/icons/full_cart.svg) #fbb03b 50% 50% no-repeat')
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })
                Toast.fire({
                    icon: 'success',
                    title: 'товар добавлен в корзину'
                })

            }
        })
    }, 400);
}


deleteProductFromCart = (product_id) => {
    $('#product_cart_' + product_id).hide()
    clearTimeout(t);
    t = setTimeout(function () {
        $.ajax({
            type: 'post',
            url: '/ajax/cart-delete',
            data: {
                id: product_id
            },
            success: function (obj) {

                switch (obj.status) {
                    case 'delete_p':
                        $('#product_' + product_id).remove();
                        $('#manufacturers_all_products').text(obj.total_products);
                        $('#manufacturer_quantity_' + obj.merchant_id).text(obj.total_merchant_quantity);
                        $('#manufacturer_price_' + obj.merchant_id).text(obj.total_merchant_price);
                        break;
                    case 'delete_m':
                        $('#manufacturer_tag_' + obj.merchant_id).remove();
                        $('#manufacturers_all_products').text(obj.total_products);
                        $('#manufacturer_quantity_' + obj.merchant_id).text(obj.total_merchant_quantity);
                        $('#manufacturer_price_' + obj.merchant_id).text(obj.total_merchant_price);
                        break;
                    case 'refresh':
                        window.location.reload();
                        break;

                }
            }
        });
    }, 400);

}


changeQuantityProduct = (product_id, value, status) => {
    let quantity = $('#amount_' + product_id).val();

    if (quantity > 100) {
        $('#amount_' + product_id).val(100);
    }

    value = quantity;
    if (quantity != 0 && quantity <= 100) {
        $('#amount_' + product_id).prop("disabled", true)
        if (status) {

            $.ajax({
                type: 'post',
                url: '/ajax/cart-change-quantity',
                data: {
                    id: product_id,
                    quantity: value
                },
                success: function (obj) {
                    $('#manufacturers_all_products').text(obj.total_products);
                    $('#manufacturer_quantity_' + obj.merchant_id).text(obj.total_merchant_quantity);
                    $('#manufacturer_price_' + obj.merchant_id).text(obj.total_merchant_price);
                    $('#summa_' + product_id).text(obj.total_product_price);
                    $('#amount_' + product_id).val(obj.total_product_quantity)
                    $('#amount_' + product_id).prop("disabled", false)
                    $('#amount_' + product_id).focus()

                }
            });
        } else {

            clearTimeout(t);
            t = setTimeout(function () {

                $.ajax({
                    type: 'post',
                    url: '/ajax/cart-change-quantity',
                    data: {
                        id: product_id,
                        quantity: value
                    },
                    success: function (obj) {
                        $('#manufacturers_all_products').text(obj.total_products);
                        $('#manufacturer_quantity_' + obj.merchant_id).text(obj.total_merchant_quantity);
                        $('#manufacturer_price_' + obj.merchant_id).text(obj.total_merchant_price);
                        $('#summa_' + product_id).text(obj.total_product_price);
                        $('#amount_' + product_id).val(obj.total_product_quantity)
                        $('#amount_' + product_id).prop("disabled", false)
                        $('#amount_' + product_id).focus()

                    }
                });
            }, 400);
        }
    } else {
        $('#amount_' + product_id).val(value)
    }

}








