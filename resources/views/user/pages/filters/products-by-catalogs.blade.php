@extends('user.layouts.page-layout')
@section('content')
    <div class="catalog">
        <div class="content">
            <div class="navi">
                <a href="{{route('user.index',app()->getLocale())}}" class="navi_point">{{__('main')}}</a>
                <a  class="navi_point">{{__('catalog')}}</a>

            </div>
            <div class="page_title">{{__('catalog')}}</div>
            <div class="b5">


                <div class="filter">
                    <div class="t1">{{__('filter')}}</div>
{{--                    <div class="filter_sect" id="selling_types">--}}
{{--                        <div class="t2">{{__('selling_type_all')}}</div>--}}
{{--                        <div class="check_cnt">--}}
{{--                            <input type="checkbox" id="roznica" value="retail">--}}
{{--                            <label--}}
{{--                                    for="roznica">{{__('selling_type_1')}}</label>--}}
{{--                        </div>--}}
{{--                        <div class="check_cnt">--}}
{{--                            <input type="checkbox" id="optom" value="wholesale">--}}
{{--                            <label--}}
{{--                                    for="optom">{{__('selling_type_2')}}</label>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="filter_sect" id="region">
                        <div class="t2">{{__('user_profile_info_address_region_2')}}</div>
                        @if(count($regions)>0)
                            @foreach($regions as $region)
                                <div class="check_cnt">
                                    <input id="region_{{$region->id}}" type="checkbox" name="region[]"
                                           value="{{$region->id}}">
                                    <label for="region_{{$region->id}}">{{$region['name_'.app()->getLocale()]}}</label>
                                </div>
                            @endforeach
                        @endif

                    </div>

                    <div class="filter_sect">
                        <div class="t2">{{__('user_profile_info_address_district_2')}}</div>
                        <div id="districts">

                        </div>

                    </div>
                    <div class="filter_sect" id="payment_way">
                        <div class="t2">{{__('invoice_payment_type')}}</div>
                        <div class="check_cnt">
                            <input type="checkbox" id="payment_cash" name="payment[]"
                                   value="cash"><label for="payment_cash">{{__('payment_way_cash')}}</label>
                        </div>
                        <div class="check_cnt">
                            <input type="checkbox" name="payment[]" id="payment_bank"
                                   value="bank"><label for="payment_bank">{{__('payment_way_bank')}}</label>
                        </div>
                        <div class="check_cnt">
                            <input type="checkbox" name="payment[]" id="payment_online"
                                   value="online"><label for="payment_online">{{__('payment_way_online')}}</label>
                        </div>

                    </div>
                    <div class="filter_sect">
                        <div class="t2">{{__('price')}}</div>
                        <div id="price-slider">

                            <div id="slider-range"></div>
                            <div class="box">

                                <input type="text" id="amount-min" name="min"
                                       readonly="">
                                <input type="text" id="amount-max" name="max"
                                       readonly="">
                            </div>
                        </div>
                        <button type="button" onclick="searchProducts()" style="display: block"
                                class="btn">{{__('accept')}}</button>
                        <a href="{{route('user.catalog',['lang'=>app()->getLocale()])}}"
                           class="btn clear" style="display: block">{{__('reset')}}</a>

                    </div>
                </div>

                <div class="b6">
                    @if(count($products)>0)
                        <div class="b4" id="products">
                            @foreach($products as $product)
                                @component('user.components.product_catalog',['product'=>$product])
                                @endcomponent
                            @endforeach
                        </div>
                        {{--                        @component('admin.components.pagination',['pagination'=>$products])--}}
                        {{--                        @endcomponent--}}
                    @endif
                </div>

            </div>

        </div>
    </div>

@endsection
@section('my_script')
    <script>

        $(function () {
            $("#slider-range").slider({
                range: true,
                min: {{$min}},
                max: {{$max}},
                values: [{{$min}}, {{$max}}],
                animate: true,
                step: 1,
                slide: function (event, ui) {
                    $("#amount-min").val(ui.values[0]);
                    $("#amount-max").val(ui.values[1]);
                }
            });


            $("#amount-min").val($("#slider-range").slider("values", 0));
            $("#amount-max").val($("#slider-range").slider("values", 1));
        });

        $('#region input[type="checkbox"]').on('change', function (event) {


            let array_data = [];
            let categories = $('#region input:checked');
            if (categories.length > 0) {


                for (let i = 0; i < categories.length; i++) {
                    array_data.push($(categories[i]).attr('value'));
                }

                clearTimeout(t)
                t = setTimeout(function () {
                    $.ajax({
                        type: 'POST',
                        url: '/ajax/district/search',
                        data: {
                            ids: array_data
                        },

                        success: function (obj) {
                            $('#districts').html(" ")
                            $.each(obj.data, function (index, value) {
                                let name = 'name_' + '{{app()->getLocale()}}'

                                $('#districts').append(
                                    '<div class="check_cnt">' +
                                    '<input type="checkbox" name="district[]" id="district_' + value.id + '" value="' + value.id + '"><label for="district_' + value.id + '">' + value[name] + '</label>' +
                                    '</div>'
                                )
                            })
                        }
                    });
                }, 350);
            } else {
                $('#districts').html(" ")
            }

        })


        function searchProducts() {
            let array_selling_type = []
            let array_region = []
            let array_district = []
            let array_payment = []
            clearTimeout(t)
            t = setTimeout(function () {
                let regions = $('#region input:checked');
                let districts = $('#districts input:checked');
                let selling_types = $('#selling_types input:checked');
                let payments = $('#payment_way input:checked');


                for (let i = 0; i < regions.length; i++) {
                    array_region.push($(regions[i]).attr('value'));
                }
                for (let i = 0; i < districts.length; i++) {
                    array_district.push($(districts[i]).attr('value'));
                }
                for (let i = 0; i < selling_types.length; i++) {
                    array_selling_type.push($(selling_types[i]).attr('value'));
                }
                for (let i = 0; i < payments.length; i++) {
                    array_payment.push($(payments[i]).attr('value'));
                }
                $.ajax({
                    type: 'POST',
                    url: '/ajax/search-in-catalogs',
                    data: {
                        catalog_id: $('#catalog').val(),
                        selling_type: array_selling_type,
                        region: array_region,
                        district: array_district,
                        max: $("#amount-max").val(),
                        min: $("#amount-min").val(),
                        payment: array_payment,

                    },
                    success: function (obj) {
                        $('#products').html(" ")
                        $.each(obj.products, function (index, value) {
                            $('#products').append(createProductHtml(value))
                        })
                    }
                });
            }, 350);
        }

        function createProductHtml(data) {
            let html = ''
            let url = "{{route('user.product_view',[':id','lang'=>app()->getLocale()])}}".replace(':id', data.id);
            html = '<div class="prod_cont_cat">' +
                '    <div class="product">' +
                '        <img src="/storage/' + data.image + '" class="product_img">\n' +
                '        <div class="b2">\n' +
                '            <a  href="' + url + '"  class="product_name">' + data.name_{{app()->getLocale()}}+ '</a>' +
                '            <div class="in_stock">{{__('In_stock')}}:<span> ' + data.quantity + ' {{__('quantity_abbr')}}</span></div>\n' +
                '            <div class="in_stock">{{__('price')}}:<span> ' + number_format(data.price, null, null, " ") + ' {{__('sum')}}</span></div>\n' +
                '        </div>\n' +
                '        <div class="b3">\n' +
                '            <div onclick="addToCart(' + data.id + ',' + data.min + ')" class="action tcart"></div>\n' +
                '            <div class="action phone"></div>\n'
            if (data.wish_list != null && $('#auth_check_id').val()) {
                html += '                <div id="wish_list_' + data.id + '"' +
                    '                     style="background-image: url("/assets/img/icons/heart.png");"\n' +
                    '                     onclick="addProductToWishList(' + data.id + ')" class="action wishlist"></div>'
            } else {
                html += '                <div id="wish_list_' + data.id + '" onclick="addProductToWishList(' + data.id + ')"' +
                    '                     class="action wishlist"></div>\n'
            }
            html += '        </div>\n' +
                '    </div>\n' +
                '</div>\n'
            return html;
        }

    </script>




@endsection