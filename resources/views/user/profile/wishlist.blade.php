@extends('admin.layouts.user-layout')
@section('content')
    <div class="main_info user_panel">
        <div class="title">{{__('user_site_bar_title')}}</div>
        <div class="table_title">{{__('wishlist_product')}}</div>

        <div class="favorite">

            <div class="table_block">
                @if(count($wishlists)>0    )
                    <table>
                        <tbody>
                        <tr style="font-weight: bold">
                            <th class="row2">{{__('image')}}</th>
                            <th class="row6">{{__('name')}}</th>
                            <th class="row6">{{__('actions')}}</th>
                        </tr>
                        @foreach($wishlists as $wishlist)
                            <tr id="wish_list_{{$wishlist->getProduct->id}}">
                                <th>
                                    <img src="{{Storage::url($wishlist->getProduct->image)}}">
                                </th>
                                <th>
                                    <div class="pr">{{$wishlist->getProduct['name_'.app()->getLocale()]}}</div>
                                </th>
                                <th>
                                    <div class="b1">
                                        <div class="fav" style="width: 32px;
                                                height: 32px;background-image: url({{asset('/assets/img/icons/heart.png')}});"
                                             onclick="removeProductToWishList({{$wishlist->getProduct->id}})"></div>
                                        <a  href="{{route('user.buy_checkout',['lang'=>app()->getLocale(),'product_id'=>$wishlist->getProduct->id])}}"
                                           class="btn_fav" style="width: 250px!important; text-decoration: none">{{__('make_order')}}</a>
                                    </div>
                                </th>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif

            </div>
        </div>
    </div>
@endsection
