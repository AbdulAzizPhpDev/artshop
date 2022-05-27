@extends('seller.layout.seller-layout')
@section('content')
    <div class="main_info seller_panel">
        <div class="title">{{__('user_order_title')}}</div>
        <div class="table_title">{{__('order_list')}}</div>

        <div class="products">
            <form action="{{route('seller.order.post.search',app()->getLocale())}}" method="post">
                @csrf

                <div class="filter_block">
                    <input type="text" class="search" name="search" placeholder="{{__('search')}}"
                           value="{{isset($search) ? ($search=="null" ? " " : $search)  : " " }}">
                    <select class="select" style="width: 330px;border-radius: 5px" name="state">
                        <option {{!isset($state) ? "selected" :"" }}  value="null">{{__('status')}}</option>
                        <option
                            {{isset($state) ? ($state=="new" ? "selected" : "")  : "" }}  value="new">{{__('user_order_table_order_state_2')}}</option>
                        <option
                            {{isset($state) ? ($state=="accept" ? "selected " : " "  ) :"" }}  value="accept">{{__('user_order_table_order_state_1')}}</option>
                    </select>
                    {{--                    <select class="select">--}}
                    {{--                        <option value="null">{{__('delivery_t_type')}}</option>--}}
                    {{--                        <option value="free">{{__('delivery_p_f')}}</option>--}}
                    {{--                        <option value="not_free">{{__('delivery_p_n_f')}}</option>--}}

                    {{--                    </select>--}}
                    {{--                <select class="select">--}}
                    {{--                    <option>{{__('In_stock_title')}}</option>--}}

                    {{--                </select>--}}
                </div>
                <div class="fltr_btns">
                    <button style="border: none" type="submit" class="fltr_accept">{{__('filter')}}</button>
                    <a style="text-decoration: none" href="{{route('seller.order.index',app()->getLocale())}}"
                       class="fltr_cancel">{{__('reset')}}</a>
                </div>
            </form>
            <div class="clear"></div>

            @if(count($orders)>0)
                <div class="table_block">
                    <table>
                        <tbody>
                        <tr style="font-weight: bold">

                            <th class="row6">{{__('user_order_table_detail')}}</th>
                            <th class="row6">{{__('buyer_user')}}</th>
                            <th class="row6">{{__('user_order_table_order_list_delivery_to_user')}}</th>
                            <th class="row2">{{__('status')}}</th>
                            <th class="row1">{{__('actions')}}</th>
                        </tr>
                        @foreach($orders as $order)
                            <tr>
                                {{--                                <th>--}}
                                {{--                                    <img src="{{Storage::url($order->product->image)}}">--}}
                                {{--                                </th>--}}
                                <th>

                                    <div class="pr">{{__('quantity')}}: <span>{{count($order->orderList)}}</span></div>
                                    <div class="pr">{{__('invoice_order_date')}}: <span>{{$order->created_at}}</span>
                                    </div>
                                </th>
                                <th>
                                    <div class="pr">{{__('full_name_abbr')}}: <span>{{$order->orderer->name}}</span>
                                    </div>
                                    <div class="pr">{{__('phone')}}: <span>  {{$order->orderer->phone_number}}</span>
                                    </div>
                                </th>
                                <th>
                                    @if($order->address_id!=0)

                                        <div class="pr">{{__('delivery_t_type')}}:
                                            <span>
                                                @if($order->deliveryTable->where('region_id',$order->address->region_id)->first()->price>0)
                                                    {{__('delivery_p_n_f')}}
                                                @else
                                                    {{__('delivery_p_f')}}
                                                @endif
                                        </span>
                                        </div>
                                    @else
                                        <div class="pr">
                                            {{__('delivery_type_1')}}
                                        </div>
                                    @endif
                                    <div class="pr">{{__('payment_way')}}:
                                        <span>
                                            {{__($order->payment_method)}}

                                        </span>
                                    </div>


                                    <div class="pr">{{__('user_profile_info_address_title')}}:
                                        @if($order->address)
                                            <span>
                                                {{$order->address->region['name_'.app()->getLocale()]}}
                                            @if(isset($order->address->a_district))
                                                    , {{ $order->address->a_district['name_'.app()->getLocale()]}}
                                                @endif
                                                @if(isset($order->address->street))
                                                , {{ $order->address->street}}
                                                @endif
                                                @if(isset($order->address->house))
                                                    , {{$order->address->house}}
                                                @endif

                                        </span>
                                        @endif
                                    </div>

                                </th>
                                <th>
                                    @if($order->state == 'new')
                                        {{__('user_order_table_order_state_2')}}
                                    @elseif($order->state == 'accept')
                                        {{__('user_order_table_order_state_1')}}
                                    @elseif($order->state == 'cancel')
                                        {{__('cancel_1')}}
                                    @endif
                                </th>

                                <th>
                                    <div class="b1">
                                        <a class="view"
                                           href="{{route('seller.order.invoice',['id'=>$order->id,'lang'=>app()->getLocale()])}}"></a>
                                        <div class="trash" onclick="deleteOrder({{$order->id}})"></div>
                                    </div>
                                </th>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @component('user.components.pagination',['pagination'=>$orders])
                    @endcomponent
                </div>
            @endif
        </div>
    </div>

@endsection

@section('script')
    <script>
        function deleteOrder(id) {
            clearTimeout(time);
            time = setTimeout(function () {
                $.ajax({
                    type: 'POST',
                    url: '/ajax/put-in-archive',
                    data: {
                        id: id,
                    },

                    success: function (data) {

                        window.location.reload();


                    }
                });
            }, 350);
        }
    </script>
@endsection
