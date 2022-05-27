<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ArtShop - Изделия ручной работы</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="/assets/img/artshop.png">
    <meta name="viewport" content="width=1750, initial-scale=1">


    <link rel="stylesheet" type="text/css" href="/assets/admin/css/slick.css">
    <link rel="stylesheet" type="text/css" href="/assets/admin/css/slick-theme.css">
    <link rel="stylesheet" href="/assets/admin/css/admin.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/admin/css/seller.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/admin/css/normalize.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/admin/css/reset.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/admin/css/rating.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/admin/css/font-awesome.min.css" type="text/css"/>


    @yield('style')

</head>

<body>

@include('admin.templates.user-header')
<div class="content">
    @include('admin.templates.user-sitebar')
    @yield('content')

</div>

</body>

<script type="text/javascript" src="/assets/admin/js/jquery.min.js"></script>
<script type="text/javascript" src="/assets/admin/js/script.js"></script>
<script type="text/javascript" src="/assets/admin/js/slick.js"></script>
<script type="text/javascript" src="/assets/admin/js/sweetalert.js"></script>
<script type="text/javascript" src="/assets/admin/js/admin.js"></script>


<script>

    $("#slider_id_1").slick({
        dots: true,
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 4
    });

    $("#slider_id_2").slick({
        dots: true,
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 4
    });
    $(".vertical").slick({
        dots: true,
        vertical: true,
        slidesToShow: 1,
        slidesToScroll: 1
    });
</script>

@yield('script')

</html>
