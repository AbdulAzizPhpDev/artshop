@extends('admin.layouts.admin-layout')
@section('content')
    <div class="main_info">
        <div class="title">{{__('admin_dashboard_title')}}</div>
        <div class="dashboard">
            <div class="b1">
                <div class="b2">
                    <div class="b4">
                        <div class="tab_ico tab_ico1"></div>
                        <div class="t1">{{__('admin_dashboard_products_display')}}</div>
                        <div class="t2">{{$active_products}}</div>
                    </div>
                    <div class="b4">
                        <div class="tab_ico tab_ico10"></div>
                        <div class="t1">{{__('admin_dashboard_products_not_available')}}</div>
                        <div class="t2">{{$not_cash_products}}</div>
                    </div>
                    <div class="b4">
                        <div class="tab_ico tab_ico3"></div>
                        <div class="t1">{{__('admin_dashboard_users')}}</div>
                        <div class="t2">{{$users}}</div>
                    </div>
                </div>
                <div class="b3">
                    <div class="b5">
                        <div class="t3">{{__('admin_dashboard_statistics')}}</div>
                        <div class="btn">{{__('admin_dashboard_sell')}}</div>
                        <canvas id="canvas"></canvas>

                       
                    </div>
                    <div class="b4">
                        <div class="tab_ico tab_ico2"></div>
                        <div class="t1">{{__('admin_dashboard_catalogs')}}</div>
                        <div class="t2">{{$catalogs}}</div>
                    </div>
                    <div class="b4">
                        <div class="tab_ico tab_ico4"></div>
                        <div class="t1">{{__('admin_dashboard_sellers')}}</div>
                        <div class="t2">{{$sellers}}</div>
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
