@extends('user.layouts.home-layout')
@section('content')
    <input type="hidden" value="{{$catalog_page}}" id="catalog_number_id">
    <input type="hidden" value="{{$product_page}}" id="popular_number_id">
    <input type="hidden" value="{{$product_page}}" id="new_number_id">


    <div class="p3">
        <div class="content">
            <div class="t1 t1_pop">{{ucfirst(__('user_index_new_title'))}}
                <div class="bg_pattern"></div>
            </div>
            <div class="b1" id="new_tag_id">

                @foreach($news as $new)
                    @component('user.components.product',['product'=>$new])
                    @endcomponent
                @endforeach
            </div>

            @if(count($news)>0)
                <div class="btn" id="new_button_id"
                     onclick="productPrePages('new')">{{ucfirst(__('user_index_show_more'))}}</div>
            @endif
        </div>
    </div>
    <div class="p3">
        <div class="content">
            <div class="t1 t1_pop">{{ucfirst(__('user_index_popular_title'))}}
                <div class="bg_pattern"></div>
            </div>
            <div class="t2">{{ucfirst(__('user_index_popular_text'))}}</div>
            <div class="b1" id="popular_tag_id">
                @foreach($populars as $popular)
                    @component('user.components.product',['product'=>$popular])
                    @endcomponent
                @endforeach

            </div>
            @if(count($populars)>0 )
                <div class="btn" id="popular_button_id"
                     onclick="productPrePages('popular')">{{ucfirst(__('user_index_show_more'))}}</div>
            @endif

        </div>
    </div>

    @if(count($catalogs)>0)
        @component('user.components.catalog',['catalogs'=>$catalogs])
        @endcomponent
    @endif

    @component('user.components.extra-info')
    @endcomponent
@endsection
@section('my_script')
    <script>

        function productPrePages(state) {
            let number = $('#' + state + '_number_id').val();

            clearTimeout(t);
            t = setTimeout(function () {
                $.ajax({
                    type: 'POST',
                    url: '/ajax/get-products/item',
                    data: {
                        number: number,
                        state: state
                    },
                    success: function (obj) {

                        if (obj.product.length > 0) {

                            $('#' + state + '_number_id').val(parseInt(number) + parseInt(obj.number));
                        } else {
                            $('#' + state + '_button_id').remove();
                        }

                        $.each(obj.product, function (index, element) {
                            $('#' + state + '_tag_id').append(productCatalogHtml(element));
                        })
                    }
                });
            }, 500);
        }

        function productCatalogHtml(data) {

            let text = '';
            let url = "{{route('user.product_view',[':id','lang'=>app()->getLocale()])}}".replace(':id', data.id);

            let image_url = `/storage/${data.image}`

            text = '<div class="product_cont">\n' +
                '    <div class="product">\n' +
                '        <img src="' + image_url + '" class="product_img">' +
                '        <div class="b2">\n' +
                '            <a href="' + url + '" class="product_name">\n' + data.name_{{app()->getLocale()}} +
                '            </a>\n' +
                '            <div class="in_stock">{{__('In_stock')}}:<span> ' + data.quantity + ' {{__('quantity_abbr')}}</span></div>\n' +
                '            <div class="in_stock">{{__('price')}}:<span> ' + number_format(data.price, null, null, " ") + ' {{__('sum')}}</span></div>\n' +
                '        </div>\n' +
                '        <div class="b3">\n' +
                '            <div onclick="addToCart(' + data.id + ',' + data.min + ',' + data.quantity + ')" class="action tcart"></div>\n' +
                '            <div class="action phone" id="contact_' + data.id + '"></div>\n' +
                '\n' +
                '\n'
            if (data.wish_list != null && $('#auth_check_id').val()) {
                text += '                <div id="wish_list_' + data.id + '"' +
                    '                     style="background-image: url("/assets/img/icons/heart.png");"' +
                    '                     onclick="addProductToWishList(' + data.id + ')" class="action wishlist"></div>\n'
            } else {
                text += '                <div id="wish_list_' + data.id + '" onclick="addProductToWishList(' + data.id + ')"' +
                    '                     class="action wishlist"></div>\n'
            }
            text += '</div>\n' +
                '    </div>\n' +
                '</div>'
            return text;

        }


        function catalogPrePages() {
            let number = $('#catalog_number_id').val();
           
            clearTimeout(t);
            t = setTimeout(function () {
                $.ajax({
                    type: 'POST',
                    url: '/ajax/get-catalogs/item',
                    data: {
                        number: number
                    },
                    success: function (obj) {
                        if (obj.catalogs.length > 0) {
                            $('#catalog_number_id').val(parseInt(number) + parseInt(obj.number));
                        } else {
                            $('#catalog_button_id').remove();
                        }


                        $.each(obj.catalogs, function (index, element) {
                            $('#catalog_tag_id').append(createCatalogHtml(element));
                        })


                    }
                });
            }, 500);
        }

        function createCatalogHtml(data) {
            let text = '';
            let url = "{{route('user.products_by_catalog',[':catalog_id','lang'=>app()->getLocale()])}}".replace(':catalog_id', data.id);
            text = '<a href="' + url + '"' +
                '                       class="b3"\n' +
                '                       style=" color: #FFFFFF; background:url(/storage/' + data.image + ') no-repeat;width: 302px;\n' +
                '                           height: 373px; margin-top: 10px; background-size: 302px 373px;">\n' +
                '                        <div class="overlay2"></div>\n' +
                '                        <div class="t2 t2_1">' + data.name_{{app()->getLocale()}} + '</div>\n' +
                '                    </a>'
            return text;
        }


        number_format = (number, decimals, dec_point, thousands_sep) => {

            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function (n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }
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
            } else {
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



@endsection
