@extends('admin.layouts.user-layout')
@section('content')
    <div class="main_info">
        <div class="title">{{__('user_profile_main')}}</div>
        <div class="table_title">{{__('user_profile_main_title_1')}}</div>
        <div class="user_panel">
            @if(count($products)>0)
                <div  id="slider_id_1" class=" regular slider">
                    @foreach($products as $product)
                        <div class="padd_block">
                            <div class="featured_block">
                                <img src="{{Storage::url($product->image)}}">
                                <div class="featured_block_info">

                                    <a style="text-decoration: none" class="t1" href="{{route('user.product_view',['id'=>$product->id,'lang'=>app()->getLocale()])}}">{{$product['name_'.app()->getLocale()]}}</a>
                                    <div class="info">
                                        <div class="t2">{{number_format($product->price, null, null, ' ')}} {{__('sum')}}</div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div  class="table_title">{{__('user_profile_main_title_2')}}</div>
            @if(count($viewed_products)>0)
                <div id="slider_id_2" class="regular slider">
                    @foreach($viewed_products as $item)

                        <div class="padd_block">
                            <div class="featured_block">
                                <img src="{{Storage::url($item->product->image)}}">
                                <div class="featured_block_info">
                                    <a style="text-decoration: none" class="t1" href="{{route('user.product_view',['id'=>$item->product->id,'lang'=>app()->getLocale()])}}">{{$item->product['name_'.app()->getLocale()]}}</a>

                                    <div class="info">
                                        <div class="t2">{{number_format($item->product->price, null, null, ' ')}}{{__('sum')}}
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
