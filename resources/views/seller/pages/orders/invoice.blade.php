@extends('seller.layout.seller-layout')
@section('content')
    <div class="main_info user_panel">
        <div class="title">{{__('user_site_bar_title')}}</div>
        @if($order->address_id!=0)
            <input type="hidden" value="{{$order->address->region->id}}" id="region_id">
            <input type="hidden" value="{{$order->address_id}}" id="address_id">
            @if(isset($order->address->a_district))

                <input type="hidden" value="{{$order->address->a_district->id}}" id="a_district_id_val">
            @else
                <input type="hidden" value="0" id="a_district_id_val">
            @endif
        @endif
        @if(count($invoice_items)>0)
            <div class="order_view">
                <div class="clear"></div>
                <div class="table_block">
                    <table>
                        <tr style="font-weight: bold">
                            <th class="row4">{{__("image")}}</th>
                            <th class="row6">{{__('setting_price_table_1')}}</th>
                            <th class="">{{__('quantity')}}</th>
                            <th class="row8">{{__('price')}}</th>
                        </tr>
                        @foreach($invoice_items as $item)
                            <tr>
                                <th>
                                    <img src="{{Storage::url($item->product->image)}}">
                                </th>
                                <th>

                                    <div class="pr">{{$item->product->name_uz}}</div>
                                    <div class="pr">{{__('invoice_order_date')}}: <span>{{$order->created_at}}</span>
                                    </div>
                                    <div class="pr">{{__('price')}}:
                                        <span>{{number_format($item->product->price, null, null, ' ')}} {{__('sum')}}/{{__('pre_product')}}</span>
                                    </div>
                                    @if(!is_null($item->product->maker_phone))
                                        <div class="pr">{{__('owner_product')}}:
                                            <span>{{$item->product->maker_name}}</span>
                                        </div>
                                        <div class="pr">{{__('owner_phone')}}:
                                            <span>{{$item->product->maker_phone}}</span>
                                        </div>
                                    @endif
                                </th>
                                <th>
                                    {{$item->product_quantity}} {{__('quantity_abbr')}}
                                </th>
                                <th>
                                    {{number_format((int)$item->product->price* (int)$item->product_quantity, null, null, ' ')}} {{__('sum')}}
                                </th>
                            </tr>
                        @endforeach

                    </table>

                    @if($order->mertchant && $order->mertchant->extraInfo)
                        <div class="b6" style="float: left!important;">
                            <img style="border-radius: 20%"
                                 src="{{Storage::url($order->mertchant->extraInfo->image_logo)}}">
                        </div>
                    @endif
                    <div class="b6">
                        <div class="b7">
                            <div class="t5">{{__('total')}}:</div>
                            <div class="t5">{{__('delivery_p_2')}}:</div>
                            <div class="t5">{{__('total')}}:</div>
                        </div>
                        <div class="b7">
                            @if($order->payment_method=="paycom")
                                @if($order->address_id!=0)
                                    <div class="t5">
                                        {{number_format(($order->total_price-$order->deliveryTable->
                                                         where('region_id',$order->address->region_id)->
                                                         first()->price), null, null, ' ')}} {{__('sum')}}
                                    </div>
                                @else
                                    <div class="t5">
                                        {{number_format(($order->total_price), null, null, ' ')}} {{__('sum')}}

                                    </div>
                                @endif
                            @else
                                <div class="t5">
                                    {{number_format(($order->total_price), null, null, ' ')}} {{__('sum')}}
                                </div>
                            @endif

                            @if($order->address_id!=0)
                                <div
                                        class="t5">{{number_format($order->deliveryTable->where('region_id',$order->address->region_id)->first()->price,null,null," ")}} {{__('sum')}}</div>
                            @else
                                <div class="t5">0</div>
                            @endif

                            <div class="t5">
                                @if($order->payment_method=="paycom")
                                    <span>
                                            {{number_format(($order->total_price), null, null, ' ')}} {{__('sum')}}
                                    </span>
                                @else
                                    @if($order->address_id!=0)
                                        <span>{{number_format(($order->total_price +$order->deliveryTable->
                                                             where('region_id',$order->address->region_id)->
                                                             first()->price), null, null, ' ')}} {{__('sum')}}</span>
                                    @else
                                        <span>{{number_format(($order->total_price ), null, null, ' ')}} {{__('sum')}}</span>
                                    @endif
                                @endif
                            </div>


                        </div>
                    </div>
                </div>


                <div class="b1">
                    <div class="b2">

                        <div class="t1">{{__('setting_price_table_1')}}</div>
                        <div class="t2">{{__('user_order_table_order_list_user')}}:
                            <span>{{$order->orderer->name}} ({{$order->orderer->phone_number}})</span></div>
                        @if($order->address)
                            <div class="t2">{{__('user_order_table_order_list_delivery_region')}}:
                                <span>{{ $order->address->region['name_'.app()->getLocale()]}}</span>
                                @if(isset($order->address->a_district))
                                    <span id="district">{{$order->address->a_district['name_'.app()->getLocale()]}}</span>

                                @endif
                                <img onclick="showModal('model_district')" src="/assets/img/admin/pen2.png"
                                     alt="pen"
                                     style="width: 24px; height: 20px; margin: 0 0 0 10px;border: none; cursor: pointer">
                            </div>
                        @endif
                        @if($order->address)
                            <div class="t2">{{__('user_order_table_order_list_delivery')}}:
                                <span id="street_text">{{$order->address->street}}</span>
                                <span id="house_text">{{$order->address->house}}</span>
                                <img onclick="showModal('model_street')" src="/assets/img/admin/pen2.png" alt="pen"
                                     style="width: 24px; height: 20px; margin: 0 0 0 10px;border: none; cursor: pointer">
                            </div>
                        @endif
                        @if($order->address)
                            <div class="t2">{{__('reference_point')}}:
                                <span>{{$order->address->reference_point}}</span></div>
                            <div class="t2">{{__('user_profile_info_address_postcode')}}:
                                <span>{{$order->address->postcode}}</span></div>
                        @endif

                    </div>
                    <div class="b3">
                        <div class="t1">{{__('invoice_payment_type_2')}}</div>
                        <div class="t2">{{__('invoice_payment_type')}}: <span>  {{__($order->payment_method)}}</span>
                        </div>

                        {{--                        @if($order->payment_way=="bank")--}}
                        {{--                            <div class="t2">Тип оплаты: <span>Банковский расчёт</span></div>--}}
                        {{--                        @elseif($order->payment_way=="cash")--}}
                        {{--                            <div class="t2">Тип оплаты: <span>Наличный расчёт</span></div>--}}
                        {{--                        @elseif($order->payment_way=="paycom")--}}
                        {{--                            <div class="t2">Тип оплаты: <span>Оплата через Paycom</span></div>--}}
                        {{--                        @endif--}}
                        @if($order->address_id!=0)
                            <div class="t2">{{__('delivery_t_type')}}: <span>
                                 @if($order->deliveryTable->where('region_id',$order->address->region_id)->first()->price>0)
                                        {{__('delivery_p_n_f')}}
                                    @else
                                        {{__('delivery_p_f')}}
                                    @endif
                            </span></div>
                        @else
                            <div class="t2">
                                {{__('delivery_t_type')}}: <span>{{__('delivery_type_1')}}</span>
                            </div>
                        @endif

                    </div>

                </div>


                @if($order->is_active)
                    <div class="b5" style="width: auto !important;">

                        @if($order->state == "new")
                            <form action="{{route('seller.order.post.invoice',app()->getLocale())}}" method="post">
                                @csrf
                                <input type="hidden" name="order_id" value="{{$order->id}}">
                                <button style="border: none;padding: 0;margin-left: 5px;" type="submit"
                                        class="btn buyer_save">{{__('accept')}}</button>
                            </form>
                        @endif
                        <form action="{{route('seller.order.cancel.invoice',app()->getLocale())}}" method="post">
                            @csrf
                            <input type="hidden" name="order_id" value="{{$order->id}}">
                            <button style="border: none;padding: 0;margin-left: 5px;" type="submit"
                                    class="btn buyer_save">{{__('cancel')}}</button>
                        </form>
                        <a href="{{route('seller.order.index',app()->getLocale())}}" style="text-decoration: none;
                            padding: 0px 56px; margin-left: 5px;"
                           class="btn back_b_list">{{__('back')}}</a>

                        <a href="{{route('print',['lang'=>app()->getLocale(),'order_id'=>$order->id])}}"
                           style="text-decoration: none;margin-left: 5px;" rel="noopener" target="_blank"
                           class="btn back_b_list">{{__('print')}}</a>
                    </div>
                @else
                    <div class="b5">

                        <a href="{{route('seller.order.archive',app()->getLocale())}}"
                           style="text-decoration: none;margin-left: 5px;"
                           class="btn back_b_list">{{__('back')}}</a>

                        <a href="{{route('print',['lang'=>app()->getLocale(),'order_id'=>$order->id])}}"
                           style="text-decoration: none;margin-left: 5px;" rel="noopener" target="_blank"
                           class="btn back_b_list">{{__('print')}}</a>

                    </div>
                @endif

            </div>
        @endif
    </div>
    <div class="popup" id="model_district" style="display:none;">
        <div class="fade"></div>
        <div style="width: 450px;
    background-color: #FFF;
    border-radius: 20px;
    margin: 0 auto;
    margin-top: -200px;
    position: relative;
    top: 50%;
    z-index: 3;
    padding: 55px;
    text-align: center;">
            <div class="t1" style="color: rgba(239,78,78,0.74);margin-bottom: 10px;">
                {{__('user_profile_info_address_district_2')}}
            </div>
            <div class="t1" style="color: rgba(239,78,78,0.74);margin-bottom: 10px;padding: 10px!important;">
                <select name="a_district" id="select_a_district"
                        style="width: 95%; padding: 8px; border: 2px solid #5EAFF0; border-radius: 10px;font-size: 20px">

                </select>
            </div>
            <button id="close_model_district" class="btn save" style="background-color: rgba(239,78,78,0.74);
                    border: 2px solid #5eaff0; color: #000;height: 52px">{{__('cancel')}}
            </button>
            <button type="submit" onclick="updateDistrict()" style="background-color:#FFFFFF;
                    border: 2px solid #5eaff0; color: #000;height: 52px" class="btn save">{{__('accept')}}
            </button>
        </div>
    </div>



    <div class="popup" id="model_street" style="display:none;">
        <div class="fade"></div>
        <div style="width: 450px;
        background-color: #FFF;
        border-radius: 20px;
        margin: 0 auto;
        margin-top: -200px;
        position: relative;
        top: 50%;
        z-index: 3;
        padding: 55px;
        text-align: center;">
            <div class="t1" style="color: rgba(239,78,78,0.74);margin-bottom: 10px;padding: 10px!important;">
                {{__('popup_title')}}
            </div>

            <div class="t1" style="margin-bottom: 10px;padding: 10px!important;">

                <input type="text" id="street_input"
                       style=" width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #008eff; border-radius: 5px; font-size: 16px">

                <input type="text" id="house_input"
                       style=" width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #008eff; border-radius: 5px;font-size: 16px">

            </div>
            <button id="close_model_street" class="btn save"
                    style="background-color: rgba(239,78,78,0.74);border: 2px solid #5eaff0; color: #000;height: 52px">
                {{__('cancel')}}
            </button>
            <button type="submit" onclick="updateStreet()" style="background-color:#FFFFFF;
                        border: 2px solid #5eaff0; color: #000;height: 52px" class="btn save">{{__('accept')}}
            </button>
        </div>
    </div>
@endsection

@section('script')

    <script>


        function popUp() {
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
            if ($('#check_success_1').val() == 1) {
                Toast.fire({
                    icon: 'success',
                    title: '{{__('data_success')}}'
                })
            }
        }

        function showModal(id) {
            if (id == "model_street") {

                $('#street_input').val($('#street_text').text())
                $('#house_input').val($('#house_text').text())

                $('#' + id).show();
            } else {
                let region = $('#region_id').val();
                $('#select_a_district').html(' ')
                clearTimeout(time);
                time = setTimeout(function () {
                    $.ajax({
                        type: 'post',
                        url: '/ajax/districts',
                        data: {
                            id: region
                        },
                        success: function (obj) {
                            obj.data.forEach(function (element, key) {

                                if (parseInt($('#a_district_id_val').val()) == parseInt(element.id)) {
                                    $('#select_a_district').append(new Option(element['name_{{app()->getLocale()}}'], element.id, false, true))
                                } else {
                                    $('#select_a_district').append(new Option(element['name_{{app()->getLocale()}}'], element.id))
                                }

                            })
                        }
                    });
                    $('#' + id).show();
                }, 350);

            }
        }

        $('#close_model_district').click(function () {
            $('#model_district').hide();
        })

        $('#close_model_street').click(function () {
            $('#model_street').hide();
        })

        function updateDistrict() {
            let id = $('#address_id').val();
            let district = $('#select_a_district').val();

            clearTimeout(time);
            time = setTimeout(function () {
                $.ajax({
                    type: 'post',
                    url: '/ajax/get/address/districts',
                    data: {
                        address_id: id,
                        district_id: district
                    },
                    success: function (obj) {
                        window.location.reload()
                    }
                });
            }, 350);
        }

        function updateStreet() {
            let id = $('#address_id').val();

            let street = $('#street_input').val();
            let house = $('#house_input').val();

            clearTimeout(time);
            time = setTimeout(function () {
                $.ajax({
                    type: 'post',
                    url: '/ajax/get/address/street',
                    data: {
                        address_id: id,
                        street: street,
                        house: house
                    },
                    success: function (obj) {
                        window.location.reload()
                    }
                });
            }, 350);
        }

    </script>


@endsection
