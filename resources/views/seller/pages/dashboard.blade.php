@extends('seller.layout.seller-layout')
@section('content')
    <div class="main_info">
        <div class="title">{{__('admin_dashboard_statistics')}}</div>
        <div class="seller_dashboard">
            <div class="b1">
                <div class="tab_ico tab_ico1"></div>

                <div class="t5">{{$product}}</div>
                <div class="t1">{{__('seller_d_product_number')}}</div>
            </div>
            <div class="b1">
                <div class="tab_ico tab_ico2"></div>

                <div class="t5">{{$order}}</div>
                <div class="t1" style="margin-left: 50px;">{{__('seller_d_product_selled_number')}}</div>
            </div>
            <div class="b1">
                <div class="tab_ico tab_ico4"></div>

                @if($product!=0 && $rating!=0)
                    <div class="t5">{{round($rating/$product,1)}}</div>
                @else
                    <div class="t5">0</div>

                @endif
                <div class="t1">{{__('seller_d_rating_com')}}</div>
            </div>
            <div class="b1 scroll">
                <div class="t2">{{__('orders')}}</div>
                @if(count($order_list)>0)
                    @foreach($order_list  as $item)
                        <div class="b3">

                            <div class="t3">{{__('quantity')}}:<span> {{count($item->orderList)}} {{__('quantity_abbr')}}</span></div>
                            <div class="t3">{{__('full_name_abbr')}}:<span> {{$item->orderer->name}}</span></div>
                            <div class="t3">{{__('phone')}}:<span> {{$item->orderer->phone_number}}</span></div>

                        </div>
                    @endforeach
                @endif
            </div>
            <div class="b1 b2">
                <div class="t4">{{__('admin_dashboard_statistics')}}</div>
                <div class="btn">{{__('sell')}}</div>
                <canvas id="canvas"></canvas>

                <div class="axis">
                    <div class="tick">
                        Jan
                        <span class="value value--this">44</span>
                        <span class="value value--prev">20</span>
                    </div>
                    <div class="tick">
                        Feb
                        <span class="value value--this">18</span>
                        <span class="value value--prev">22</span>
                    </div>
                    <div class="tick">
                        Mar
                        <span class="value value--this">16</span>
                        <span class="value value--prev">30</span>
                    </div>
                    <div class="tick">
                        Apr
                        <span class="value value--this">18</span>
                        <span class="value value--prev">22</span>
                    </div>
                    <div class="tick">
                        May
                        <span class="value value--this">24</span>
                        <span class="value value--prev">18</span>
                    </div>
                    <div class="tick">
                        Jun
                        <span class="value value--this">36</span>
                        <span class="value value--prev">22</span>
                    </div>
                    <div class="tick">
                        Jul
                        <span class="value value--this">28</span>
                        <span class="value value--prev">30</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>

@endsection
@section('script')
    <script type="text/javascript" src="/assets/admin/js/chart_2_7.js"></script>
    <script type="text/javascript" src="/assets/admin/js/chart.js"></script>
@endsection

