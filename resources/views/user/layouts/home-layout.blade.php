<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Artshop</title>
    <meta name="description" content="Artshop">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="shortcut icon" href="/assets/img/artshop.png">

    <link rel="stylesheet" href="/assets/user/css/style.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/user/css/jquery-ui.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/user/css/normalize.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/user/css/reset.css" type="text/css"/>
    {{--    <link rel="stylesheet" href="/assets/user/css/rating.css" type="text/css"/>--}}
    <link rel="stylesheet" href="/assets/user/css/slick.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/user/css/slick-theme.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/user/css/font-awesome.min.css" type="text/css"/>

    <style>
        .new_profile {
            width: 80px;
            padding: 10px;
            background-color: #f9f9f9;
            display: none;
            position: absolute;
            right: 250px;
            top: 80px;
            border-radius: 10px;
            box-shadow: 0px 0px 20px #f9f9f9;
            z-index: 9999;
        }

        .search_drop {
            background: #ffffff;
            width: 604px;
            padding: 0;
            max-height: 400px;
            overflow: auto;
            position: relative;
            z-index: 999999;
            border-radius: 3px;
            border-width: 0 1px 1px 1px;
            margin: 0 auto;
            display: none;
        }

        .search_li_class {
            margin: 10px 10px 10px;
            font-size: 20px;
            line-height: 22px;
            font-weight: normal;
            font-family: Montserrat-Bold
        }
    </style>
    @yield('my_style')

</head>

<body>

@include('user.templates.header')
<input type="hidden" id="auth_check_id" value="{{auth()->check()}}">
<input type="hidden" id="login_id" value="{{route('user.login',app()->getLocale())}}">
@yield('content')
@component('user.components.news-letter')
@endcomponent
@include('user.templates.footer')

</body>
<script type="text/javascript" src="/assets/user/js/jquery.min.js"></script>
<script type="text/javascript" src="/assets/user/js/sweetalert.js"></script>
<script type="text/javascript" src="/assets/user/js/slick.js"></script>
<script type="text/javascript" src="/assets/user/js/script.js"></script>
<script type="text/javascript" src="/assets/user/js/app.js"></script>

<script type="text/javascript" src="/assets/user/user.js"></script>
<script type="text/javascript" src="/assets/user/cart.js"></script>
<script src="https://kit.fontawesome.com/623c56bddb.js" crossorigin="anonymous"></script>
@yield('my_script')
<script>

    $(".vertical").not('.slick-initialized').slick({
        dots: true,
        vertical: true,
        slidesToShow: 1,

        slidesToScroll: 1,
        // autoplay: true,
        // autoplaySpeed: 2000,

    });
    let search_time;

    function searchByName(id) {


        clearTimeout(search_time)
        search_time = setTimeout(function () {
            let search = $('#searching_item_' + id).val().trim();

            if (search.length > 0) {
                $.ajax({
                    type: 'POST',
                    url: '/ajax/search-products',
                    data: {
                        search: search,
                    },

                    success: function (obj) {

                        if (obj.products.length > 0) {
                            // switch (id) {
                            //     case 1 :
                            //         $('#search_drop_2').css('display', 'none')
                            //         $('#search_drop_2').html(' ')
                            //         $('#search_drop_3').css('display', 'none')
                            //         $('#search_drop_3').html(' ')
                            //
                            //         $('#searching_item_2').val(' ');
                            //         $('#searching_item_3').val(' ');
                            //         break
                            //     case 2 :
                            //         $('#search_drop_1').css('display', 'none')
                            //         $('#search_drop_1').html(' ')
                            //         $('#search_drop_3').css('display', 'none')
                            //         $('#search_drop_3').html(' ')
                            //
                            //         $('#searching_item_1').val(' ');
                            //         $('#searching_item_3').val(' ');
                            //         break
                            //     case 3 :
                            //         $('#search_drop_1').css('display', 'none')
                            //         $('#search_drop_1').html(' ')
                            //         $('#search_drop_2').css('display', 'none')
                            //         $('#search_drop_2').html(' ')
                            //
                            //         $('#searching_item_1').val(' ');
                            //         $('#searching_item_2').val(' ');
                            //         break
                            // }
                            $('#go_to_catalog_id').css('display', 'none')
                            $('#search_drop_' + id).css('display', 'block')
                            $('#search_drop_' + id).html(' ')
                            $.each(obj.products, function (index, value) {
                                let url = "{{route('user.product_view',[':id','lang'=>app()->getLocale()])}}".replace(':id', value.id);
                                let text = ' <li class="search_li_class">\n' +
                                    '        <a style="color: #999999;display: inline-flex;" href="' + url + '">\n' +
                                    '        <img width="40" height="40" src="/storage/' + value.image + '" alt="" >\n' +
                                    '        <span style="margin-left: 30px">' + value.name_{{app()->getLocale()}} + '</span>\n' +
                                    '        </a>\n' +
                                    '        </li>'

                                $('#search_drop_' + id).append(text)

                            })
                        } else {
                            $('#go_to_catalog_id').css('display', 'none')
                            $('#search_drop_' + id).css('display', 'block')
                            $('#search_drop_' + id).html(' ')
                            let text = ' <li class="search_li_class">\n' +
                                '        <a style="color: #999999;display: inline-flex;" >\n' +
                                '        <span style="margin-left: 30px">' + ' {{__('nothing_find')}} ' + '</span>\n' +
                                '        </a>\n' +
                                '        </li>'

                            $('#search_drop_' + id).append(text)
                        }

                    }

                });
            } else {
                $('#go_to_catalog_id').css('display', 'flex')
                $('#search_drop_' + id).css('display', 'none')
                $('#search_drop_' + id).html(' ')
            }
        }, 500);


    }

</script>
</html>
