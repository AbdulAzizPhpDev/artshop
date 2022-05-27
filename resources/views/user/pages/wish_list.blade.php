@extends('user.layouts.page-layout')
@section('content')
    <div class="favorites">
        <div class="content">
            <div class="navi">
                <a href="/" class="navi_point">{{__('main')}}</a>
                <a class="navi_point">{{__('wishlist_title')}}</a>
            </div>
            <div class="page_title">{{__('wishlist_title')}}</div>

            @if(count($products)>0)

                <div class="b1">
                    @foreach($products as $product)
                        <div class="product" id="wish_list_{{$product->getProduct->id}}">
                            <div class="close" onclick="removeProductToWishList({{$product->getProduct->id}})"></div>
                            <img src="{{Storage::url($product->getProduct->image)}}" class="product_img">
                            <div class="b2">
                                <a href="{{route('user.product_view',['id'=>$product->getProduct->id,'lang'=>app()->getLocale()])}}"
                                   class="product_name">{{$product->getProduct['name_'.app()->getLocale()]}}
                                </a>
                                <div class="in_stock">{{__('price')}}
                                    :<span>  {{number_format($product->getProduct->price,null,null,' ')}} {{__('sum')}}</span>
                                </div>
                            </div>
                            <div class="b3">
                                <div style="width: 180px!important;"
                                     onclick="addToCart({{$product->getProduct->id}},{{$product->getProduct->min}},{{$product->getProduct->quantity}})"
                                     class="btn">
                                    {{__('product_cart')}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="b1">
                <p class="page_title">{{__('wishlist_no_data')}}</p>
                </div>
            @endif

        </div>
    </div>
@endsection
