@extends('user.layouts.page-layout')
@section('content')
    <div class="checkout" style="margin-bottom: 50px">
        <div class="content">
            <div class="page_title">{{__('make_order')}}</div>


            <form id="form_id" action="{{route('user.order.buyProduct',app()->getLocale())}}" method="post" class="b1">
                @csrf
                <div class="b2">
                    @if(!auth()->check())

                        {{session()->put('redirect_url',Request::url())}}
                        <div class="b4">
                            <div class="b5">
                                <div class="t1">{{__('fast_r')}}</div>
                                <a style="display: block" href="{{route('user.register',app()->getLocale())}}"
                                   class="btn btn1">{{__('user_login_register')}}</a>
                            </div>
                            <div class="b5">
                                <div class="t1">
                                    {{__('fast_r_q')}}
                                </div>
                                <a href="{{route('user.login',app()->getLocale())}}" style="display: block"
                                   class="btn btn2">{{__('user_login_button')}}</a>
                            </div>
                        </div>
                    @endif
                    <div class="b6">
                        <div class="b5">
                            <div class="t2">{{__('contact_info')}}</div>
                        </div>
                    </div>

                    <div class="b6">
                        <div class="b5">
                            <div class="t3">{{__('name')}}</div>
                            <input type="text" class="inp" disabled value="{{auth()->check() ? $user->name : ""}}">
                        </div>

                        <div class="b5">
                            <div class="t3">{{__('phone')}}</div>

                            <input type="text" class="inp" value="{{$new_office}}" disabled>

                            <input type="hidden" id="seller_id" value="{{$merchant->id}}"
                                   name="merchant_id">

                        </div>
                    </div>


                    <div class="b6">
                        <div class="b5">
                            <div class="t2">{{__('delivery_t_check')}}</div>
                        </div>
                    </div>

                    <div class="b6" id="delivery_type_id">
                        <div class="b5">
                            <input type="radio" {{$regions ? "CHECKED": "disabled"}}  id="kuda" name="delivery_type"
                                   value="active">
                            <label for="kuda">
                                {{__('delivery_type_2')}}
                            </label>
                        </div>
                        <div class="b5">
                            <input type="radio" {{is_null($regions) ? "CHECKED": ""}} name="delivery_type" id="kurer"
                                   value="passive">
                            <label for="kurer">
                                {{__('delivery_type_1')}}
                            </label>
                        </div>
                    </div>
                    @if($regions)
                        <div class="b6" id="company_id" style="display: none">
                            <div class="b5">
                                <div class="t3">{{__('phone')}}</div>
                                <input type="text" class="inp" value="{{$merchant->phone_number}}" disabled>
                                <input type="hidden" id="seller_id" value="{{$merchant->id}}"
                                       name="merchant_id">
                            </div>
                        </div>
                        <div id="active_tag" class="b6">
                            <div class="b5">
                                <div class="t3">{{__('user_profile_info_address_region')}}</div>
                                <select name="address[region]" class="inp" id="region_id">
                                    <option value="null">{{__('user_profile_info_address_region_2')}}</option>
                                    @if($regions && count($regions)>0)
                                        @foreach($regions as $region)
                                            <option value="{{$region->id}}">{{$region['name_'.app()->getLocale()]}}</option>
                                        @endforeach
                                    @endif
                                </select>
{{--                                <div class="t3">{{__('user_profile_info_address_district')}}</div>--}}
{{--                                <select name="address[district]" class="inp"--}}
{{--                                        id="district_id" disabled>--}}
{{--                                    <option value="null">{{__('user_profile_info_address_district_2')}}</option>--}}

{{--                                </select>--}}
{{--                                <div class="t3">{{__('user_profile_info_address_street')}}</div>--}}
{{--                                <input id="street" type="text" name="address[street]" required class="inp">--}}
{{--                                <div class="t3">{{__('user_profile_info_address_house_number')}}</div>--}}
{{--                                <input id="house" name="address[house]" required type="text" class="inp">--}}
                            </div>
{{--                            <div class="b5">--}}

{{--                                <div class="t3">{{__('user_profile_info_address_entrance')}}</div>--}}
{{--                                <input name="address[entrance]" type="text" class="inp">--}}
{{--                                <div class="t3">{{__('user_profile_info_address_floor')}}</div>--}}
{{--                                <input name="address[floor]" type="text" class="inp">--}}
{{--                                <div class="t3">{{__('user_profile_info_address_apartment')}}</div>--}}
{{--                                <input name="address[apartment]" type="text" class="inp">--}}

{{--                            </div>--}}
                        </div>
                    @endif
                    @if(!$regions)
                        <div class="b6 radio-blocks" id="radio-block-2" style="display:block;margin-top: 30px;    height: 230px;
    width: 350px;">
                            <div id="map" style="width: 350px; height: 250px"></div>
                        </div>
                    @else

                        <div class="b6 radio-blocks" id="radio-block-2" style="display:none;margin-top: 30px;    height: 230px;
    width: 350px;">
                            <div id="map" style="width: 350px; height: 250px"></div>
                        </div>

                    @endif

                    <div class="b6">
                        <div class="b5" id="payment_ways_id">
                            <div class="t2">{{__('payment_ways')}}</div>
                            @if($merchant->requisite)
                                <input type="radio" id="bank" value="bank" name="payment_way">
                                <label for="bank">
                                    {{__('payment_way_bank')}}
                                </label><br>
                            @endif
                            @if($merchant->paymentSystem && $merchant->paymentSystem->is_cash)
                                <input type="radio" id="cash" value="cash" name="payment_way">
                                <label for="cash">
                                    {{__('payment_way_cash')}}
                                </label><br>
                            @endif
                            @if($merchant->paymentSystem && $merchant->paymentSystem->is_active && $merchant->paymentSystem->merchant_id!=null && $merchant->paymentSystem->password!=null)
                                <input type="radio" id="paycom" value="paycom" name="payment_way">
                                <label for="paycom">
                                    {{__('payment_way_online')}}
                                </label>
                            @endif

                        </div>
{{--                        <div id="textarea_id" class="b5">--}}
{{--                            <div class="t3">{{__('leave_comment')}}</div>--}}
{{--                            <textarea name="reference_point" rows="5"--}}
{{--                                      style="margin: 0px; width: 343px; height: 177px;"></textarea>--}}
{{--                        </div>--}}

                    </div>
                </div>

                <div class="b3">
                    <div class="t4">{{__('list_order')}}</div>

                    <div class="b7" style="overflow-y: auto;">


                        <div class="checkout_item">
                            <img src="{{$product->image!=null?Storage::url($product->image):'/1.png'}}">
                            <div class="b8">
                                <div class="t5">{{$product['name_'.app()->getLocale()]}}</div>
                                @if(Session::has('product_quantity'))
                                    <div class="t6">{{Session::get('product_quantity')}} {{__('quantity_abbr')}}</div>
                                @else
                                    <div class="t6">{{1}} {{__('quantity_abbr')}}</div>
                                @endif
                                <div class="t6">{{number_format($product->price, null, null, ' ')}}</div>
                            </div>
                        </div>


                    </div>

                    <div id="delivery_title" class="t4">
                        {{ !is_null($regions) ? __('delivery_e') : __('delivery_n_e')}} </div>
                    @if($regions)
                        <div class="b6">
                            <div class="b5">
                                <div class="t7">{{__('payment_way')}}:</div>
                                <div id="delivery_payment_t_id">

                                </div>
                            </div>
                            <div class="b5">

                                <div class="t7" id="payment_type">--------</div>
                                <div id="delivery_payment_d_id">

                                </div>

                            </div>
                        </div>
                    @endif
                    <div class="b9">
                        <div class="b6">
                            <div class="b5">
                                <div class="t8">{{__('total_p')}}:</div>
                            </div>
                            <div class="b5">
                                <input type="hidden" id="cart_price" value="{{$product->price}}">
                                <div class="t8">
                                    <span>
                                        @if(Session::has('product_quantity'))
                                            <span id="total_price">{{number_format($product->price*(int)Session::get('product_quantity'), null, null, ' ')}}</span>
                                        @else
                                            <span id="total_price">{{number_format($product->price, null, null, ' ')}}</span>
                                        @endif
                                        {{__('sum')}}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(Session::has('product_quantity'))
                        <input type="hidden" id="product_quantity" name="quantity"
                               value="{{(int)Session::get('product_quantity')}}">
                    @else
                        <input type="hidden" id="product_quantity" name="quantity" value="1">
                    @endif
                    <input type="hidden" name="merchant_id" value="{{$merchant->id}}">
                    <input type="hidden" name="product_id" value="{{$product->id}}">
                    <div class="t9">{{__('checkout_title')}} <a
                        >{{__('checkout_title_1')}}</a></div>
                    <button onclick="submitForm()" type="button" style="border: none"
                            class="btn btn3">{{__('accept_order')}}</button>
                </div>
            </form>


            <a href="{{route('user.catalog',app()->getLocale())}}" style="display:block;"
               class="btn btn2">{{__('cancel')}}</a>
        </div>
    </div>
    <input type="hidden" value="{{auth()->check()}}" id="check_auth">
@endsection
@section('my_script')
    <script>
        function submitForm() {
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
            if (!$('#check_auth').val()) {
                Toast.fire({
                    icon: 'warning',
                    title: '{{__('auth_check_text')}}'
                })
            } else if ($('#delivery_type_id input:checked').attr('value') == "passive") {
                if ($('#payment_ways_id input:checked').length == 0) {
                    Toast.fire({
                        icon: 'warning',
                        title: '{{__('choose_type_payment')}}'
                    })
                    $('#payment_ways_id').focus()

                } else {
                    $('#form_id').submit()
                }
            } else {
                if ($('#payment_ways_id input:checked').length == 0) {
                    Toast.fire({
                        icon: 'warning',
                        title: '{{__('choose_type_payment')}}'
                    })
                    $('#payment_ways_id').focus()
                    return false
                }
                else if ($('#region_id').children("option:selected").val() == "null") {
                    Toast.fire({
                        icon: 'warning',
                        title: '{{__('choose_address')}}'
                    })
                }
                {{--    $('#district_id').focus()--}}
                {{--    return false--}}
                {{--} else if ($('#street').val().length == 0) {--}}
                {{--    Toast.fire({--}}
                {{--        icon: 'warning',--}}
                {{--        title: '{{__('choose_address')}}'--}}
                {{--    })--}}
                {{--    $('#street').focus()--}}
                {{--    return false--}}
                {{--} --}}

                else {
                    $('#form_id').submit()
                }
            }


        }


        $('#region_id').on('change', function (event) {
            let id = $(this).find('option:selected').val();
            if (isNaN(id)) {
                $('#total_price').html(number_format(parseInt(parseInt($('#cart_price').val() * $('#product_quantity').val())), null, null, ' '))
                $('#district_id').prop('disabled', true);
                $('#district_id').html('')
                $('#district_id').append(new Option('{{__('user_profile_info_address_region')}}', 'null'))
            } else {
                clearTimeout(t)
                t = setTimeout(function () {
                    $.ajax({
                        type: 'POST',
                        url: '/ajax/districts',
                        data: {
                            id: id,
                            seller_id: $('#seller_id').val()
                        },

                        success: function (obj) {


                            if (obj.price != null) {
                                $('#delivery_payment_d_id').html(" ")
                                $('#delivery_payment_t_id').html(" ")
                                if (obj.price.price == 0) {
                                    $('#delivery_payment_t_id').append('<div class="t7">{{__('delivery_t_type')}}:</div>')
                                    $('#delivery_payment_d_id').append('<div class="t7">{{__('delivery_p_f')}}</div>')
                                } else {
                                    $('#delivery_payment_t_id').append('<div class="t7">{{__('delivery_t_type')}}:</div>')
                                    $('#delivery_payment_d_id').append('<div class="t7">{{__('delivery_p_n_f')}}</div>')

                                }
                                $('#delivery_payment_t_id').append('<div class="t7">{{__('delivery_p_2')}}:</div>')
                                $('#delivery_payment_d_id').append('<div class="t7">' + number_format(obj.price.price, null, null, ' ') + '</div>')
                                $('#total_price').html(number_format(parseInt(obj.price.price) + parseInt($('#cart_price').val() * $('#product_quantity').val())), null, null, ' ')
                            } else {
                                $('#delivery_payment_d_id').html(" ")
                                $('#delivery_payment_t_id').html(" ")
                            }


                            $('#district_id').prop('disabled', false);
                            $('#district_id').html('')
                            $('#district_id').append(new Option('{{__('user_profile_info_address_district')}}', 'null'))
                            $.each(obj.data, function (index, value) {
                                let name = 'name_' + '{{app()->getLocale()}}'

                                $('#district_id').append(new Option(value[name], value.id))
                            })
                        }
                    });
                }, 500);
            }
        })


        $('input[name=delivery_type]').on('change', function (event) {
            let status = event.target.value;
            if (status == "active") {
                $('#active_tag').css('display', 'flex')
                $('#delivery_id').css('display', 'flex')
                $('#delivery_title').css('display', 'none')
                $('#delivery_id').css('display', 'none')
                $('#textarea_id').css('display', 'block')
                $('#total_price').html(parseInt($('#cart_price').val()) * $('#product_quantity').val())
                location.reload()

            } else {
                $('#company_id').css('display', 'flex')
                $('#delivery_payment_d_id').html(" ")
                $('#delivery_payment_t_id').html(" ")
                $('#textarea_id').css('display', 'none')
                $('#active_tag').css('display', 'none')
                $('#delivery_id').css('display', 'block')
                $('#radio-block-2').css('display', 'block')
                $('#delivery_title').html('{{__('delivery_n_e')}}')
                $('#total_price').html(number_format(parseInt($('#cart_price').val()) * $('#product_quantity').val()), null, null, ' ')

            }
        });

        $('input[name=payment_way]').on('change', function (event) {

            let status = event.target.value;
            if (status == "bank") {
                $('#payment_type').html(' {{__('payment_way_bank')}}')
            } else if (status == "cash") {
                $('#payment_type').html(' {{__('payment_way_cash')}}')
            } else if (status == "paycom") {
                $('#payment_type').html(' {{__('payment_way_online')}}')
            }
        });
    </script>

    <script>
        ymaps.ready(function () {
            var myMap = new ymaps.Map('map', {
                    center: [41.311151, 69.279737],
                    zoom: 12
                }),

                // Создаём макет содержимого.
                MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
                    '<div style="color: #FFFFFF; font-weight: bold;">$[properties.iconContent]</div>'
                ),


                myPlacemarkWithContent = new ymaps.Placemark([41.322658, 69.269281], {
                    hintContent: 'Собственный значок метки с контентом',
                    balloonContent: 'А эта — новогодняя',
                    iconContent: 'hello'
                });

            myMap.geoObjects
                .add(myPlacemarkWithContent);
        });
    </script>
@endsection
