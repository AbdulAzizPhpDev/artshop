@extends('admin.layouts.user-layout')
@section('content')
    <div class="main_info user_panel">
        <div class="title">{{__('user_site_bar_title')}}</div>
        <div class="table_title">{{__('user_order_title')}}</div>

        <div class="products">
            <div class="filter_block">
                <form action="{{route('user.profile.post.search',app()->getLocale())}}" method="post">
                    @csrf
                    <input value="{{isset($search) ? ($search=="null" ? " " : $search)  : " " }}" type="text"
                           class="search" name="search"
                           style="width: 700px" placeholder="{{__('search')}}">
                    <select class="select" style="width: 330px;border-radius: 5px" name="state">
                        <option {{!isset($state) ? "selected" :"" }}  value="null">{{__('status')}}</option>
                        <option {{isset($state) ? ($state=="new" ? "selected" : "")  : "" }}  value="new">{{__('user_order_table_order_state_2')}}</option>
                        <option {{isset($state) ? ($state=="accept" ? "selected " : " "  ) :"" }}  value="accept">{{__('user_order_table_order_state_1')}}</option>
                    </select>
                    <div class="fltr_btns" style="margin-top: 20px">

                        <button type="submit" style="padding: 0;height: 40px;border: none"
                                class="fltr_accept">{{__('filter')}}</button>
                        <a style="text-decoration: none" href="{{route('user.profile.orders',app()->getLocale())}}"
                           class="fltr_cancel">{{__('reset')}}</a>
                    </div>
                </form>
            </div>

            <div class="clear"></div>
            <div class="table_block">
                <table>
                    <tbody>
                    @if(count($orders)>0)
                        <tr style="font-weight: bold">

                            <th class="row6">{{__('user_order_table_detail')}}</th>
                            <th class="row6">{{__('user_order_table_supplier')}}</th>
                            <th class="row6">{{__('user_order_table_sum')}}</th>
                            <th class="row2">{{__('status')}}</th>
                            <th class="row1">{{__('user_order_table_action')}}</th>
                        </tr>
                        @foreach($orders as $order)
                            <tr>

                                <th>
                                    <div class="pr">
                                        <a>{{__('user_order_table_more')}} {{count($order->orderList)}} {{__('user_order_table_more_order')}}</a>
                                    </div>

                                    <div class="pr">{{__('invoice_order_date')}}: <span>{{$order->created_at}}</span>
                                    </div>
                                </th>
                                <th>
                                    <div class="pr"><span>"{{$order->mertchant->name}}"</span></div>
                                    <div class="pr"><span>  {{$order->mertchant->phone_number}}</span></div>
                                </th>
                                <th>
                                    @if($order->address_id==0)
                                        <div class="pr">{{__('delivery_type_1')}}</div>
                                    @else


                                        <div class="pr">{{__('delivery_p_2')}}:
                                            <span>{{number_format($order->deliveryTable->where('region_id',$order->address->region_id)->first()->price,null,null," ")}} {{__('sum')}}</span>
                                        </div>
                                    @endif
                                    <div class="pr">{{__('user_order_table_product_price')}}:
                                        @if($order->payment_method=='paycom' && $order->address_id!=0)

                                            <span>{{number_format($order->total_price - $order->deliveryTable->
    where('region_id',$order->address->region_id)->first()->price,null,null,' ')}} {{__('sum')}}</span>
                                        @else
                                            <span>{{number_format($order->total_price,null,null,' ')}} {{__('sum')}}</span>
                                        @endif
                                    </div>
                                    @if($order->address_id==0)
                                        <div class="pr">{{__('total')}}:
                                            <span>{{number_format(
                                           $order->total_price,null,null,' ')}} {{__('sum')}}
                                        </span>
                                        </div>
                                    @else
                                        <div class="pr">{{__('total')}}:
                                            @if($order->payment_method=='paycom')
                                                <span>{{number_format(
                                                $order->total_price,null,null,' ')}} {{__('sum')}}
                                        </span>
                                            @else
                                                <span>{{number_format(
    $order->total_price+$order->deliveryTable->
    where('region_id',$order->address->region_id)->first()->price,null,null,' ')}} {{__('sum')}}
                                        </span>
                                            @endif
                                        </div>
                                    @endif

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
                                        <a href="{{route('user.profile.invoice',['id'=>$order->id,'lang'=>app()->getLocale()])}}"
                                           class="view"></a>
                                    </div>
                                </th>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                @component('user.components.pagination',['pagination'=>$orders])
                @endcomponent
            </div>
        </div>
    </div>
@endsection
