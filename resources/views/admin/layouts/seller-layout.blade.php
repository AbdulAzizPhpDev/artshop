<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ArtShop - Изделия ручной работы</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="/assets/img/artshop.png">
    <meta name="viewport" content="width=1750, initial-scale=1">

    <link rel="stylesheet" href="/assets/user/css/admin.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/user/css/normalize.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/user/css/reset.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/user/css/rating.css" type="text/css"/>
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    @yield('style')

</head>

<body>

@include('admin.templates.header')
<div class="content">
    @include('admin.templates.sitebar    ')
    @yield('content')

</div>

{{--@include('admin.templates.footer')--}}

</body>
<script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>

<script type="text/javascript" src="/assets/admin/js/chart_2_7.js"></script>
<script type="text/javascript" src="/assets/admin/js/script.js"></script>
<script type="text/javascript" src="/assets/admin/js/chart.js"></script>


<script type="text/javascript" src="/assets/admin/admin.js"></script>

@yield('script')

</html>
