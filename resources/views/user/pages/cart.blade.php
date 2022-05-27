@extends('user.layouts.page-layout')
@section('my_style')
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endsection
@section('content')



    <div class="cart">
        <div class="content">
            <div class="navi">
                <a href="/" class="navi_point">{{__('main')}}</a>
                <a class="navi_point">{{__('cart')}}</a>
            </div>
            <div class="page_title">{{__('cart')}}</div>
            <div class="products_qty">{{__('cart_insite')}}: <span
                        id="manufacturers_all_products">{{session()->has('cart') ? session()->get('cart')['total_products'] : 0}}</span>
            </div>
            {{--            @dd(session()->has('cart'))--}}
            @if(session()->has('cart'))

                @foreach(session()->get('cart')['manufacturers'] as $merchant)
                    <div class="b1" style="margin-bottom: 10px" id="manufacturer_tag_{{$merchant['manufacturer']->id}}">

                        <div class="b2">
                            @foreach($merchant['products'] as $product )
                                <div class="b4" id="product_{{$product['product']->id}}">
                                <span style="    content: '';
                                position: absolute;
                                background: url(/assets/img/icons/close.png) 50% 0px no-repeat;
                                width: 14px;
                                height: 14px;
                                top: 12px;
                                right: 12px;
                                cursor: pointer;" onclick="deleteProductFromCart({{$product['product']->id}})"></span>
                                    <div class="b5">
                                        <img src="{{$product['product']->image!=null?Storage::url($product['product']->image):'/1.png'}}"
                                             class="prod_img">
                                    </div>
                                    <div class="b6">
                                        <a style="display: block  "
                                           href="{{route('user.product_view',['id'=>$product['product']->id,'lang'=>app()->getLocale()])}}
                                                   "
                                           class="prod_name">{{$product['product']['name_'.app()->getLocale()]}}
                                        </a>
                                        <div class="price">{{number_format($product['product']->price,null,null,' ')}} {{__('sum')}}
                                            /{{__('quantity_abbr')}}</div>
                                    </div>
                                    <div class="b7">
                                        <div class="amount">
                                            <input type="button" class="left_button" value="-"
                                                   onClick="change('amount_{{$product['product']->id}}',{{$product['product']->min}},{{$product['product']->quantity}},-1);"/>

                                            <input  class="count_field" id="amount_{{$product['product']->id}}"
                                                   type="number" min="{{$product['product']->min}}"
                                                   oninput="changeQuantityProduct({{$product['product']->id}}, {{$product['product']->min}},false)"
                                                   value="{{$product['total_quantity']}}"/>

                                            <input type="button" class="right_button" value="+"
                                                   onClick="change('amount_{{$product['product']->id}}',{{$product['product']->min}},{{$product['product']->quantity}}, 1);"/>
                                        </div>
                                    </div>
                                    <div class="total_price">{{__('total')}}:<span> <spam
                                                    id="summa_{{$product['product']->id}}">{{number_format($product['total_price'],null,null,' ')}}</spam> {{__('sum')}}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>


                        <div class="b3">
                            <div class="seller_name">“{{$merchant['manufacturer']->name}}”</div>
                            <div class="b8">
                                <div class="b9">
                                    <div class="t1">{{__('total_p')}}:</div>
                                    <div class="t1">{{__('total')}}:</div>
                                </div>
                                <div class="b9">
                                    <div class="t2"
                                         id="manufacturer_quantity_{{$merchant['manufacturer']->id}}">{{$merchant['total_product_quantity']}}</div>
                                    <div class="t2"><span
                                                id="manufacturer_price_{{$merchant['manufacturer']->id}}">{{number_format($merchant['total_product_price'],null,null," ")}}</span>
                                        сум
                                    </div>
                                </div>
                            </div>
                            <a style="display: block;text-decoration: none; color: #FFF"
                               href="{{route('user.catalog',app()->getLocale())}}"
                               class="btn">{{__('sell_c')}}</a>
                            <form action="{{route('user.checkout',['lang'=>app()->getLocale(),'merchant_id'=>$merchant['manufacturer']->id ])}}"
                                  method="get">


                                <button style="border: none;" type="submit" class="btn blue">
                                    {{__('make_order')}}
                                </button>
                            </form>

                        </div>

                    </div>
                @endforeach
            @endif
        </div>
    </div>


@endsection
