@extends('seller.layout.seller-layout')
@section('content')
    <div class="main_info seller_panel">
        <div class="title">{{__('delivery_t')}}</div>
        <div class="table_title">{{__('delivery_s')}}</div>
        <div style="justify-content: flex-end;
    display: flex;">


                <input    checked type="hidden" id="switch_on_off" name="is_active"
                       value="checked"}} ><i></i>
            

        </div>
        <div class="delivery">

            <div class="table_block">
                <table>
                    <tbody>
                    <tr style="font-weight: bold">
                        <th class="row6">{{__('region_uzbek')}}</th>
                        <th class="row6">{{__('delivery_p_2')}}</th>
                        <th class="row4">{{__('actions')}}</th>
                    </tr>

                    @if($regions && count($regions)>0)
                        @foreach($regions as $region)
                            <tr>
                                <th>
                                    <input type="checkbox"
                                           {{$price_list>0 ? "" : "disabled"}} onclick="changeStatus({{$region->id}})"
                                           id="checkbox_{{$region->id}}"
                                           {{$region->priceList==null ? "" : ($region->priceList->is_active ? "checked" : " ") }}  value="{{$region->id}}">
                                    <label for="checkbox_{{$region->id}}" id="checkbox_label_{{$region->id}}">{{$region['name_'.app()->getLocale()]}}</label>
                                </th>
                                <th>
                                    <div class="t1" id="region_price_{{$region->id}}"
                                         onclick="showModal('{{$region->id}}')">
                                        {{$region->priceList==null ? __('set_delivery_p') : ($region->priceList->price>0 ? $region->priceList->price : __('delivery_p_f')) }}

                                        <input type="hidden" id="price_value_{{$region->id}}"
                                               value="{{$region->priceList!=null ? $region->priceList->price : null}}">

                                    </div>
                                    <input id="checkbox_check_{{$region->id}}"
                                           type="hidden"
                                           value="{{$region->priceList==null ? 0:1}}">
                                </th>
                                <th>
                                    <div class="pen2" id="show_modal_{{$region->id}}"
                                         onclick='editPriceTable("{{$region->id}}")'></div>
                                </th>
                            </tr>
                        @endforeach
                    @else
                    @endif
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <div class="popup" id="modal_id" style="display: none;">
        <div class="fade"></div>
        <div class="main_block">
            <div class="t1">{{__('delivery_p_r')}}</div>
            <div class="b1">
                <input type="radio" id="payme" name="pay" value="1" class="radio_option" checked="checked">
                <label class="text_label" for="payme">{{__('delivery_p_f_2')}}</label>
                <input type="radio" id="bank" name="pay" value="2" class="radio_option">
                <label class="text_label" for="bank">{{__('delivery_p_m')}}</label>

                <div class="t2"><span>{{__('country')}}:  </span> Узбекистан</div>
                <div class="t2" id="set_region_name"></div>

                <div class="popup_pay_b" id="popup_pay_b">
                    <div class="t2"><span>{{__('delivery_p_2')}}:</span></div>
                    <input type="hidden" id="region_id" value="">
                    <input type="number" min="1000" id="price_id"
                           style="width: 80%;  height: 25px;   border: 2px solid #000000;
                                  border-radius: 5px;margin-bottom: 33px;padding: 5px">
                    <label>сум</label>
                </div>
                <div class="btn1" id="save" onclick="saveData()">{{__('save')}}</div>
                <div class="btn2" id="close">{{__('cancel')}}</div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function showModal(id) {
            console.log(id)
            console.log("dsjfhsjkdhfjkshdkf")
               console.log($('#checkbox_' + id).html())
            if ($('#switch_on_off').prop('checked') && !$('#checkbox_' + id).prop('checked')) {
                $('#modal_id').show();
                $('#price_id').val(' ');
                $('#region_id').val(id);
                $('#set_region_name').html('<span>{{__("admin_region_title")}}:  </span>' + $('#checkbox_label_' + id).text());
            }
        }

        function editPriceTable(id) {

            if ($('#switch_on_off').prop('checked') && $('#checkbox_' + id).prop('checked')) {
                if ($('#price_value_' + id).val() != 0) {
                    $('#payme').prop("checked", false)
                    $('#bank').prop("checked", true)
                    $('#popup_pay_b').addClass('active')
                } else {
                    $('#popup_pay_b').removeClass('active')
                    $('#payme').prop("checked", true)
                    $('#bank').prop("checked", false)

                }
                $('#modal_id').show();
                $('#price_id').val($('#price_value_' + id).val());
                $('#region_id').val(id);
                $('#set_region_name').html('<span>{{__("admin_region_title")}}:  </span>' + $('#checkbox_label_' + id).text());

            }
        }

        $('#close').click(function () {
            $('#modal_id').hide();
        })

        function saveData() {
            let price = 0
            if ($('#modal_id input:checked').val() == 2) {
                price = $('#price_id').val()
            }
            if (price != null) {
                clearTimeout(time)
                time = setTimeout(function () {
                    $.ajax({
                        type: 'POST',
                        url: '/ajax/set-delivery-price',
                        data: {
                            region_id: $('#region_id').val(),
                            seller_id: $('#auth_id').val(),
                            price: price
                        },
                        success: function (obj) {
                            $('#modal_id').hide();
                            if (obj.data.price == 0) {
                                $('#region_price_' + obj.data.region_id).html('{{__('delivery_p_f')}}')
                            } else {
                                $('#region_price_' + obj.data.region_id).html(obj.data.price)
                            }
                            $('#checkbox_' + obj.data.region_id).prop("checked", true)
                            $('#checkbox_check_' + obj.data.region_id).val(1)
                            location.reload()
                        }
                    });
                }, 500);
            }
        }

        function changeStatus(id) {

            if ($('#switch_on_off').prop('checked')) {
                if ($('#checkbox_check_' + id).val() == 0) {
                    console.log(id)
                    $('#checkbox_' + id).prop("checked", true);
                    $('#show_modal_' + id).click();


                } else {
                    clearTimeout(time)
                    time = setTimeout(function () {
                        $.ajax({
                            type: 'post',
                            url: '/ajax/change-status',
                            data: {
                                id: id,
                                is_active: $('#checkbox_' + id).prop('checked')
                            },
                            success: function (obj) {
                                // if (!obj.is_active) {
                                //     let data = $(".table_block input")
                                //     for (let i = 0; i < data.length; i++) {
                                //         $('#' + $(data[i]).attr('id')).prop("checked", false)
                                //         $('#' + $(data[i]).attr('id')).prop("disabled", true);
                                //
                                //     }
                                // }
                                // location.reload()
                                // $('#modal_id').hide();
                                //
                                //
                                // $('#checkbox_' + obj.data.region_id).prop("checked", true)
                            }
                        });
                    }, 500);
                }
            } else {
                $('#checkbox_' + id).prop("checked", false)
            }
        }

        function changeAllStatus() {

            clearTimeout(time)
            time = setTimeout(function () {
                $.ajax({
                    type: 'POST',
                    url: '/ajax/change-delivery-status',
                    data: {
                        seller_id: $('#auth_id').val(),
                        is_active: $('#switch_on_off').prop('checked')
                    },

                    success: function (obj) {

                        if (!obj.is_active) {
                            let data = $(".table_block input")
                            for (let i = 0; i < data.length; i++) {
                                $('#' + $(data[i]).attr('id')).prop("checked", false)
                                $('#' + $(data[i]).attr('id')).prop("disabled", true);

                            }

                        } else {
                            location.reload()
                        }
                    }
                });
            }, 500);
        }


    </script>
@endsection
