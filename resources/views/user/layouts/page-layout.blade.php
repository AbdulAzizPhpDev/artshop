<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Artshop</title>
    <meta name="description" content="Artshop">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="shortcut icon" href="/assets/img/artshop.png">
    <link rel="stylesheet" href="/assets/user/css/style.css" type="text/css"/>

    <link rel="stylesheet" href="/assets/user/css/jquery-ui.css" type="text/css"/>
    {{--    <link rel="stylesheet" href="/assets/user/css/font-awesome.min.css" type="text/css"/>--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css"
          type="text/css"/>
    <link rel="stylesheet" href="/assets/user/css/normalize.css" type="text/css"/>
    {{--    <link rel="stylesheet" href="/assets/user/css/normalize.min.css" type="text/css"/>--}}
    <link rel="stylesheet" href="/assets/user/css/reset.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/user/css/rating.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/user/css/slick.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/user/css/slick-theme.css" type="text/css"/>
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css"
          type="text/css"/>
    <link rel="stylesheet" href="/assets/user/css/font-awesome.min.css" type="text/css"/>
    {{--    <link rel="stylesheet" href="/assets/user/css/font-awesome.min.css" type="text/css"/>--}}

    <script src="https://api-maps.yandex.ru/2.1/?apikey=4446c929-6dcd-441a-9315-0a7af8f79114&lang=en_US"
            type="text/javascript">
    </script>


    <style>
        .new_profile {
            width: 80px;
            padding: 10px;
            background-color: #f9f9f9;
            display: none;
            position: absolute;
            right: 250px;
            top: 80px;
            border-radius: 10px;
            box-shadow: 0px 0px 20px #f9f9f9;
            z-index: 9999;
        }


    </style>
    @yield('my_style')

</head>

<body>

@include('user.templates.header_page')
<input type="hidden" id="auth_check_id" value="{{auth()->check()}}">
<input type="hidden" id="login_id" value="{{route('user.login',app()->getLocale())}}">
@yield('content')
@component('user.components.news-letter')
@endcomponent
@include('user.templates.footer')

</body>

<script type="text/javascript" src="/assets/user/js/jquery.min.js"></script>
<script type="text/javascript" src="/assets/user/js/jquery.ui.min.js"></script>
<script type="text/javascript" src="/assets/user/js/sweetalert.js"></script>
<script type="text/javascript" src="/assets/user/js/slick.js"></script>
<script type="text/javascript" src="/assets/user/js/script.js"></script>

<script type="text/javascript" src="/assets/user/user.js"></script>
<script type="text/javascript" src="/assets/user/js/app.js"></script>
<script type="text/javascript" src="/assets/user/cart.js"></script>
<script type="text/javascript" src="/assets/user/video.js"></script>
<script src="https://kit.fontawesome.com/623c56bddb.js" crossorigin="anonymous"></script>
<script>
    addToCart = (product_id, product_min_order, quantity = -1) => {

        if (quantity == 0) {
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
                icon: 'warning',
                title: '{{__('product_not')}}'
            })
        }
        else
        {
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

                        if (obj.status == true) {
                            $('#cart_logo').css('background', 'url(/assets/img/icons/full_cart.svg) #fbb03b 50% 50% no-repeat')

                            Toast.fire({
                                icon: 'success',
                                title: '{{__('cart_add')}}'
                            })
                        } else {
                            Toast.fire({
                                icon: 'warning',
                                title: '{{__('product_not')}}'
                            })
                        }

                    }
                })
            }, 400);
        }

    }

    addProductToWishList = (id) => {
        clearTimeout(t);
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        $('#wish_list_svg_id').toggleClass("unclickable");
        if ($('#auth_check_id').val()) {
            t = setTimeout(function () {
                $.ajax({
                    type: 'post',
                    url: '/ajax/wish-list-add',
                    data: {
                        wish_list_id: id
                    },
                    success: function (obj) {

                        if (obj.status != false) {
                            $('#wish_list_' + id).css("background-image", "url('/assets/img/icons/heart.png')");
                            Toast.fire({
                                icon: 'success',
                                title: '{{__('wishlist_add')}}'
                            })

                        } else {
                            $('#wish_list_' + id).removeAttr("style");
                            Toast.fire({
                                icon: 'success',
                                title: '{{__('wishlist_delete')}}'
                            })

                        }

                    }
                });
            }, 350);
        } else {
            window.location = $('#login_id').val()
        }

    }

    removeProductToWishList = (id) => {
        if ($('#auth_check_id').val()) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
            t = setTimeout(function () {
                $.ajax({
                    type: 'post',
                    url: '/ajax/wish-list-remove',
                    data: {
                        wish_list_id: id
                    },
                    success: function (obj) {
                        Toast.fire({
                            icon: 'success',
                            title: '{{__('wishlist_delete')}}'
                        })
                        $('#wish_list_' + id).remove()
                    }
                });
            }, 350);
        } else {
            window.location = "/login"
        }

    }
</script>
@yield('my_script')

</html>
