@extends('seller.layout.seller-layout')
@section('style')
    <style>
        .inp {
            width: 600px;
            height: 37px;
            outline: none;
            border: solid 1px #ebedf2;
            box-shadow: 0px 1px 6px 0 #EBEDF2;
            color: #000;
            border-radius: 10px;
            padding-left: 28px;
            margin-bottom: 25px;
        }

        .btn {
            width: 219px;
            height: 48px;
            font-size: 16px;
            background-color: #FFF;
            color: #000000;
            border-radius: 49px;
            text-align: center;
            line-height: 48px;
            border: #5eaff0 solid 1px;
            margin: 0 auto;
            margin-top: 39px;
            cursor: pointer;
            padding: 0;
        }
    </style>
@endsection
@section('content')
    <div class="main_info seller_panel">
        <div class="title">{{__('delivery_t')}}</div>
        <div class="table_title">{{__('setting_price')}}</div>
        <div class="table_block">
            <form action="{{route('seller.delivery.post.payment',app()->getLocale())}}" method="post">
                @csrf
                <table style="width: 800px;">
                    <tbody>
                    <tr style="font-weight: bold">

                        <th class="row2" style="padding: 20px 35px 25px 30px;width: 500px;font-size: 18px">{{__('setting_price_table_1')}}
                        </th>
                        <th class="row1" style="font-size: 18px">{{__('actions')}}</th>
                    </tr>

                    <tr>
                        <th style="font-size: 20px">
                            <a class="b1 view"
                               style="display: inline;padding:20px 35px 25px 30px;background: url({{asset('/assets/img/icons/nal.png')}}) 0px 0px no-repeat;">
                            </a>{{__('cash_1')}}
                        </th>

                        <th>
                            <label class="iosCheck blue">
                                <input type="checkbox"
                                       {{$payment ? ($payment->is_cash ? "checked" : " ")  : " "}}  name="cash">
                                <i></i>
                            </label>
                        </th>
                    </tr>
                    <tr>
                        <th style="font-size: 20px">
                            <a class="b1 view"
                               style="display: inline;padding:20px 35px 25px 30px;background: url({{asset('/assets/img/icons/click.png')}}) 0px 50%  no-repeat;">
                            </a>

                            {{__('payment_way_online')}}

                            <div id="payme_tag">
                                @if ($payment && $payment->is_active)
                                    <div class="t1" style="margin-top: 50px">
                                        SERVICE_ID
                                    </div>
                                    <input style="margin-top: 10px" value="{{$payment->login}}" name="login"
                                           type="text" class="inp" required>
                                    <div class="t1">
                                        MERCHANT_ID
                                    </div>
                                    <input style="margin-top: 10px" value="{{$payment->merchant_id}}" name="merchant_id"
                                           type="text" class="inp" required>
                                    <div class="t1">
                                        SECRET_KEY
                                    </div>
                                    <input style="margin-top: 10px" value="{{$payment->key}}" name="key"
                                           type="text" class="inp" required>
                                    <div class="t1">
                                        Merchant user id
                                    </div>
                                    <input style="margin-top: 10px" value="{{$payment->password}}" name="password"
                                           type="text" class="inp" required>
                                @endif
                            </div>
                        </th>

                        <th>
                            <input type="hidden" id="service_id" value="{{$payment  ? $payment->login : '' }}">
                            <input type="hidden" id="merchant_id" value="{{$payment  ? $payment->merchant_id : '' }}">
                            <input type="hidden" id="secret_key" value="{{$payment  ? $payment->key : '' }}">
                            <input type="hidden" id="user_id" value="{{$payment  ? $payment->password : '' }}">
                            <input type="hidden" name="paycom_name" value="click">
                            <label class="iosCheck blue">
                                <input  {{$payment ? ($payment->is_active ? "checked" : " ")  : " "}}  type="checkbox" style="display: none"
                                       onchange="payme()" id="payme_checkbox"  name="paycom">
                                <i></i>
                            </label>
                        </th>
                    </tr>

                    </tbody>
                </table>
                <div style="display: flex;   justify-content: space-evenly;    align-content: center; margin-top: 20px">
                    <button type="submit" class="btn">{{__('save')}}</button>
                </div>
            </form>
        </div>
    </div>
    @error('success')

    <input type="hidden" id="check_success_1" value="1">
    @enderror
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            popUp()
        });

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

        function payme() {

            if ($('#payme_checkbox').prop('checked')) {
                $('#payme_tag').html(createHtml())
            } else {
                $('#payme_tag').html('')
            }
        }

        function createHtml() {
            let VAR_SERVICE_ID = $('#service_id').val()
            let VAR_MERCHANT_ID = $('#merchant_id').val()
            let VAR_SECRET_KEY = $('#secret_key').val()
            let VAR_Merchant_user_id = $('#user_id').val()
            let html = ' <div class="t1" style="margin-top: 50px">' +
                'SERVICE_ID' +
                ' </div>' +
                '<input style="margin-top: 10px" name="login" type="text" class="inp" value="' + VAR_SERVICE_ID + '" required>' +
                ' <div class="t1">' +
                'MERCHANT_ID' +
                '</div>' +
                '<input style="margin-top: 10px" name="merchant_id" type="text" class="inp" value="' + VAR_MERCHANT_ID + '" required>'+
                ' <div class="t1">' +
                'SECRET_KEY' +
                '</div>' +
                '<input style="margin-top: 10px" name="key" type="text" class="inp" value="' + VAR_SECRET_KEY + '" required>'+
                ' <div class="t1">' +
                'Merchant user id' +
                '</div>' +
                '<input style="margin-top: 10px" name="password" type="text" class="inp" value="' + VAR_Merchant_user_id + '" required>';
            return html;
        }
    </script>
@endsection
