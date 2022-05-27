<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ArtShop - {{__('main_title')}}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="/assets/img/artshop.png">
    <meta name="viewport" content="width=1750, initial-scale=1">
    <link rel="stylesheet" href="/assets/admin/css/admin.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/admin/css/seller.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/admin/css/normalize.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/admin/css/reset.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/admin/css/rating.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/admin/css/font-awesome.min.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/admin/css/tooltip.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/admin/css/util.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/admin/css/slick.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/admin/css/slick-theme.css" type="text/css"/>

    @yield('style')

</head>

<body>

@include('seller.templates.seller-header')
<input type="hidden" id="auth_id" value="{{auth()->user()->id}}">
<div class="content">

    @include('seller.templates.seller-sitebar')
    @yield('content')

</div>

{{--@include('admin.templates.footer')--}}

</body>

<script type="text/javascript" src="/assets/admin/js/jquery.min.js"></script>
<script type="text/javascript" src="/assets/admin/js/loader.js"></script>
<script type="text/javascript" src="/assets/admin/js/admin.js"></script>
<script type="text/javascript" src="/assets/admin/js/sweetalert.js"></script>
<script type="text/javascript" src="/assets/admin/js/script.js"></script>


@yield('script')

</html>
