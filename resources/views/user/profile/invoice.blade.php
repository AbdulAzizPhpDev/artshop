@extends('admin.layouts.user-layout')
@section('content')

    <div class="main_info user_panel">
        <div class="title">{{__('orders')}}</div>

        <div class="order_view">
            <div class="clear"></div>
            @if(count($invoice_items)>0)
                <div class="table_block">
                    <table>
                        <tbody>
                        <tr style="font-weight: bold">
                            <th class="row4">{{__('image')}}</th>
                            <th class="row6">{{__('user_order_table_detail')}}</th>
                            <th class="">{{__('quantity')}}</th>
                            <th class="row8">{{__('price')}}</th>
                        </tr>

                        @foreach($invoice_items as $item)
                            <tr>
                                <th>
                                    <img src="{{Storage::url($item->product->image)}}">
                                </th>
                                <th>
                                    <div class="pr">{{$item->product['name_'.app()->getLocale()]}}</div>
                                    <div class="pr">{{__('invoice_order_date')}}: <span>{{$order->created_at}}</span>
                                    </div>
                                    <div class="pr">{{__('price')}}:
                                        <span>{{(int)$item->product->price}} {{__('sum')}}/{{__('pre_product')}}</span>
                                    </div>
                                </th>
                                <th>
                                    {{$item->product_quantity    }} {{__('quantity_abbr')}}
                                </th>
                                <th>
                                    {{number_format(((int)$item->product->price* (int)$item->product_quantity),null,null," ")}} {{__('sum')}}

                                </th>


                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                    <div class="b6">
                        <div class="b7">
                            <div class="t5">{{__('total_product')}}:</div>
                            @if($order->address_id!=0)
                                <div class="t5">{{__('delivery_p_2')}}:</div>
                            @else
                                <div class="t5">{{__('delivery_type_1')}}:</div>
                            @endif
                            <div class="t5">{{__('total')}}:</div>
                        </div>
                        <div class="b7">
                            @if($order->payment_method=='paycom' && $order->address_id!=0)
                                <div class="t5">{{number_format($order->total_price-$order->deliveryTable->where('region_id',$order->address->region_id)->first()->price,null,null," ")}} {{__('sum')}}</div>
                            @else
                                <div class="t5">{{number_format($order->total_price,null,null," ")}} {{__('sum')}}</div>
                            @endif
                            @if($order->address_id!=0)
                                <div class="t5">{{number_format($order->deliveryTable->where('region_id',$order->address->region_id)->first()->price,null,null," ")}} {{__('sum')}}</div>
                            @else
                                <div class="t5">.</div>
                            @endif
                            <div class="t5">
                                @if($order->address_id!=0)
                                    @if($order->payment_method=='paycom')
                                        <span>{{number_format($order->total_price,null,null," ")}} {{__('sum')}}</span>
                                    @else
                                        <span>{{number_format(($order->total_price + $order->deliveryTable->where('region_id',$order->address->region_id)->first()->price),null,null," ")}} {{__('sum')}}</span>

                                    @endif
                                @else
                                    <span>{{number_format($order->total_price ,null,null," ")}} {{__('sum')}}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="b1">
                    <div class="b2">
                        <div class="t1">{{__('user_order_table_order_list_setting')}}</div>
                        <div class="t2">{{__('user_order_table_order_list_user')}}:
                            <span>{{$order->orderer->name}}</span>
                        </div>
                        <div class="t2">{{__('phone')}}:
                            <span>{{$order->orderer->phone_number}}</span>
                        </div>
                        @if($order->address_id!=0)
                            <div class="t2">{{__('user_order_table_order_list_delivery_region')}}:

                                <span>{{$order->orderer->address->region['name_'.app()->getLocale()]}}, 
 @if(isset($order->orderer->address->a_district))
                                    {{ $order->orderer->address->a_district['name_'.app()->getLocale()]}}
                                     @endif
                                </span>
                            </div>
                            <div class="t2">{{__('user_order_table_order_list_delivery')}}:
                                <span>{{$order->orderer->address->street}}, {{ $order->orderer->address->house}}</span>
                            </div>
                        @endif
                        <div class="t2">{{__('user_order_table_order_list_seller_address')}}:
                            <span>{{$order->mertchant->address->region['name_'.app()->getLocale()]}}, {{ $order->mertchant->address->a_district['name_'.app()->getLocale()]}}, {{$order->mertchant->address->street}}, {{ $order->mertchant->address->house}}</span>
                        </div>
                    </div>
                    <div class="b3">
                        <div class="t1">{{__('user_order_table_supplier')}}</div>
                        <div class="b4">
                            <div class="t3">"{{$order->mertchant->name}}"</div>

                            <div class="seller_info seller_info_geo">
                                {{$order->mertchant->address->region['name_'.app()->getLocale()]}} <br>
                                {{$order->mertchant->address->a_district['name_'.app()->getLocale()]}} <br>
                                {{$order->mertchant->address->street}} <br>
                                {{$order->mertchant->address->house}}
                            </div>
                            <div class="seller_info seller_info_web">artshop.uz</div>
                            <div class="seller_info seller_info_phone">{{$order->mertchant->phone_number}}</div>
                            <div class="seller_info seller_info_mail">Написать письмо</div>
                        </div>
                    </div>
                </div>

            @endif
            <div class="b5">
                <a href="{{route('user.profile.orders',app()->getLocale())}}" style="text-decoration: none"
                   class="btn back_b_list">{{__('back')}}</a>

            </div>
        </div>
    </div>
@endsection
