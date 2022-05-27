@extends('admin.layouts.admin-layout')
@section('content')
    <div class="main_info seller_panel ">
        <div class="title">{{__('catalog')}}</div>
        <div class="products">
            <input type="hidden" value="{{route('admin.product.index',['lang'=>app()->getLocale()])}}"
                   id="redirect_url">
            <div class="table_title">{{__('products')}}</div>
            <form action="{{route('admin.product.post.search',['lang'=>app()->getLocale()])}}" method="post">
                @csrf
                <div class="filter_block">
                    <input name="search" type="text" class="search"
                           value="{{isset($search) ? ($search=='null' ?"": $search): "" }}"
                           placeholder="{{__('search')}}">
                    <select class="select" name="catalog">
                        <option value="all">{{__('admin_product_all_catalogs')}}</option>
                        @if(count($catalogs)>0)
                            @foreach($catalogs as $catalog)
                                <option
                                    value="{{$catalog->id}}" {{isset($catalog_id) ? ($catalog->id==$catalog_id ? "selected": ""): ""}}>{{$catalog->name_uz}}</option>
                            @endforeach
                        @endif
                    </select>
                    <select class="select" name="seller">
                        <option value="all">{{__('admin_dashboard_sellers')}}</option>
                        @foreach($merchants as $merchant)
                            <option
                                value="{{$merchant->id}}" {{isset($seller_id) ? ($merchant->id==$seller_id ? "selected": "") : ""}}>{{$merchant->name}}</option>
                        @endforeach
                    </select>
                    <select class="select" name="pagination">
                        <option {{isset($pagination) ? ($pagination==5 ? "selected" :"") : "" }}  value="5">
                            @if (app()->getLocale()=="ru")
                                {{__('admin_product_pagination')}} 5
                            @else
                                5 {{__('admin_product_pagination')}}
                            @endif
                        </option>
                        <option {{isset($pagination) ? ($pagination==10 ? "selected" :"") :"" }} value="10">
                            @if (app()->getLocale()=="ru")
                                {{__('admin_product_pagination')}} 10
                            @else
                                10 {{__('admin_product_pagination')}}
                            @endif
                        </option>
                        <option {{isset($pagination) ? ($pagination==20 ? "selected" :"") : "" }} value="20">
                            @if (app()->getLocale()=="ru")
                                {{__('admin_product_pagination')}} 20
                            @else
                                20 {{__('admin_product_pagination')}}
                            @endif
                        </option>
                        <option {{isset($pagination) ? ($pagination==50 ? "selected" :""):"" }} value="50">
                            @if (app()->getLocale()=="ru")
                                {{__('admin_product_pagination')}} 50
                            @else
                                50 {{__('admin_product_pagination')}}
                            @endif
                        </option>
                    </select>
                </div>

                <div class="fltr_btns">
                    <button type="submit" style="border: none" class="fltr_accept">{{__('filter')}}</button>
                    <a href="{{route('admin.product.index',['lang'=>app()->getLocale()])}}"
                       style="text-decoration: none"
                       class="fltr_cancel">{{__('reset')}}</a>
                </div>
            </form>
            <div class="clear"></div>
            @if(count($products)>0)
                <div class="table_block">
                    <table>
                        <tr style="font-weight: bold">
                            <th class="row1">ID</th>
                            <th class="row2">{{__('image')}}</th>
                            <th class="row3">{{__('name')}}</th>
                            <th class="row4">{{__('price')}}</th>
                            <th class="row5">{{__('quantity')}}</th>
                            <th class="row6">{{__('seller')}}</th>
                            <th class="row7">{{__('active')}}</th>
                            <th class="row8">{{__('action')}}</th>
                        </tr>

                        @foreach($products as $product)
                            <tr>
                                <th>{{$product->id}}</th>
                                <th><img src="{{Storage::url($product->image)}}" alt="image" height="100"
                                         width="100"></th>
                                <th>{{$product->name_ru}}</th>
                                <th>{{$product->price}} {{__('sum')}}</th>
                                <th>{{$product->quantity}} {{__('quantity_abbr')}}</th>
                                <th>{{$product->merchant->name}}</th>
                                <th class="check">
                                    <label class="iosCheck blue">
                                        <input
                                            {{$product->is_active ? "checked":""}}  onchange="updateProductStatus({{$product->id}},'/ajax/product',{{!$product->is_active ? 'true' : 'false'}})"
                                            type="checkbox"><i></i>
                                    </label>
                                </th>
                                <th>
                                    <div class="b1">
                                        @if($product->is_popular)
                                            <div id="popular_id_{{$product->id}}"
                                                 style="background-position-y: -28px" class="fav"
                                                 onclick="popular({{$product->id}})"></div>
                                        @else
                                            <div id="popular_id_{{$product->id}}" class="fav"
                                                 onclick="popular({{$product->id}})"></div>
                                        @endif

                                        <a class="view" href="{{route('admin.product.show',['id'=>$product->id,'lang'=>app()->getLocale()])}}"></a>
                                        <div class="trash" onclick="showModal({{$product->id}})"></div>

                                    </div>
                                </th>
                            </tr>
                        @endforeach

                    </table>
                    @component('admin.components.pagination',['pagination'=>$products])
                    @endcomponent

                </div>
            @endif

        </div>
    </div>
    <div class="popup" id="modal_id" style="display:none;">
        <input type="hidden" value="null" id="delete_product_id">
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
            <div class="t1"
                 style="color: rgba(239,78,78,0.74);margin-bottom: 10px;">{{__('admin_product_delete')}}</div>
            <button id="close" class="btn save" style="background-color: #FFFFFF;
                    border: 2px solid #5eaff0; color: #000;height: 52px">{{__('cancel')}}
            </button>
            <button type="submit" onclick="deleteProduct()" style="background-color:rgba(239,78,78,0.74);
                    border: 2px solid #5eaff0; color: #000;height: 52px" class="btn save">{{__('accept')}}
            </button>
        </div>
    </div>
@endsection
@section('script')

    <script>
        function showModal(id) {
            $('#modal_id').show();
            $('#delete_product_id').val(id);

        }

        $('#close').click(function () {
            $('#modal_id').hide();
        })

        function popular(product_id) {
            clearTimeout(time);
            time = setTimeout(function () {
                $.ajax({
                    type: 'POST',
                    url: '/ajax/product/popular',
                    data: {
                        id: product_id
                    },
                    success: function (data) {
                        if (data.popular) {
                            $('#popular_id_' + data.id).css("background-position-y", "-28px");
                        } else {
                            $('#popular_id_' + data.id).removeAttr("style");
                        }
                    }
                });
            }, 350);
        }
    </script>

@endsection
