<div class="product_cont">
    <div class="product">
        
        <img src="{{Storage::url($product->image)}}" class="product_img">
        <div class="b2">
            <a href="{{route('user.product_view',['id'=>$product->id,'lang'=>app()->getLocale()])}}" class="product_name">
                {{$product['name_'.app()->getLocale()]}}
            </a>
            <div class="in_stock">{{__('In_stock')}}:<span> {{$product->quantity}} {{__('quantity_abbr')}}</span></div>
            <div class="in_stock">{{__('price')}}:<span> {{number_format($product->price, null, null, ' ')}} {{__('sum')}}</span></div>
        </div>
        <div class="b3">
            <div onclick="addToCart({{$product->id}},{{$product->min}},{{$product->quantity}})" class="action tcart"></div>
            <div class="action phone" id="contact_{{$product->id}}"></div>


            @if ($product->wishList!=null && auth()->check())
                <div id="wish_list_{{$product->id}}"

                     style="background-image: url({{asset('/assets/img/icons/heart.png')}});"
                     onclick="addProductToWishList({{$product->id}})" class="action wishlist"></div>
            @else
                <div id="wish_list_{{$product->id}}" onclick="addProductToWishList({{$product->id}})"
                     class="action wishlist"></div>
            @endif

        </div>
    </div>
</div>
