@extends('user.layouts.page-layout')
@section('content')
    <div class="check">
        <div class="content">
            <div class="b1">
                <div class="b2">

                    <div class="t1">{{__('invoice_executor')}}</div>
                    <div class="t2">{{__('user_register_seller_name_label')}}:
                        <span>{{$order->mertchant->requisite ==null ? $order->mertchant->name : $order->mertchant->requisite->official_name}}</span>
                    </div>
                    <div class="t2">{{__('invoice_address_company')}}: <span>
                            {{$order->mertchant->address!=null ?
                            ($order->mertchant->address->region['name_'.app()->getLocale()] ) :"" }}
                        </span></div>


                    <div class="t2">{{__('invoice_bank_requisites')}}:</div>
                    <div class="t2">Р/С:{{$order->mertchant->requisite->bank_account}}</div>
                    <div class="t2">{{__('stir')}}:{{$order->mertchant->requisite->stir}}</div>
                    <div class="t2">МФО:{{$order->mertchant->requisite->bank_info}}</div>
                    <div class="t2">ОКЭД:</div>
                    <div class="t2">{{__('phone')}}: <span>{{$order->mertchant->phone_number}}</span></div>
                    <div class="t2">{{__('email')}}: <span>info@artshop.uz</span></div>
                </div>
                <div class="b2">

                    <div class="t1">{{__('invoice_customer')}}</div>
                    <div class="t2">{{__('invoice_full_name')}}:</div>
                    <div class="t2"> {{$order->orderer->name}}</div>
                    <div class="t2">{{__('invoice_address_user')}}:</div>
                    <div class="t2">{{$order->orderer->address!=null ?
($order->orderer->address->region->name_uz ) :"" }}</div>
                    <div class="t2">{{$order->orderer->address!=null ?
($order->orderer->address->a_district->name_uz) :"" }}</div>
                    <div class="t2">{{$order->orderer->address!=null ?
($order->orderer->address->street) :"" }}</div>
                    <div class="t2">{{$order->orderer->address!=null ?
($order->orderer->address->house) :"" }}</div>
                    <div class="t2">{{__('phone')}}:</div>
                    <div class="t2">{{$order->orderer->phone_number}}</div>

                </div>
                <div class="b2">
                    <div class="t1"><span>{{__('invoice_not_paid')}}</span></div>
                    <div class="t2">{{__('invoice_account_number')}}: {{$order->id}}</div>
                    <div class="t2">{{__('invoice_order_date')}}: {{$order->created_at}}</div>
                    <div class="t2">{{__('invoice_payment_duration')}}: 3</div>
                    <div class="t2">{{__('invoice_payment_type')}}: {{__($order->payment_method)}}</div>
                </div>
            </div>
            <div class="checkout" style="margin-bottom: 30px;display: flex;">
                <a href="{{route('user.cart',app()->getLocale())}}" style="display: block;border: none;"
                   class="btn btn2">{{__('back')}}</a>

                <a href="{{route('user.profile.orders',app()->getLocale())}}" style="display: block;border: none;"
                   class="btn btn2">
                    {{__('orders')}}
                </a>


            </div>
        </div>


    </div>


@endsection