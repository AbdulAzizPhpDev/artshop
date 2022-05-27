@if (auth()->check() && auth()->user()->role_id==2)
    <a href="{{route('user.cart',['lang'=>app()->getLocale()])}}" class="fix_cart" id="cart_logo"
       style="{{session()->has('cart')  ? 'background: url(/assets/img/icons/full_cart.svg) #fbb03b 50% 50% no-repeat;':' '}}"></a>

@elseif(!auth()->check())
    <a href="{{route('user.cart',['lang'=>app()->getLocale()])}}" class="fix_cart" id="cart_logo"
       style="{{session()->has('cart')  ? 'background: url(/assets/img/icons/full_cart.svg) #fbb03b 50% 50% no-repeat;':' '}}"></a>

@endif
<div class="fixedMenuBurger">
    <a class="burgerMenuLogo">
        <img src="/assets/img/logo.png">
    </a>
    <div class="menuBurger">
        <span></span>
    </div>
    <div class="fixedMenuBurgerLayout">
        <a class="fixedMenuBurgerLayoutLogo">
            <img src="/assets/img/logo.png">
        </a>
        <div class="fixedMenuBurgerLayoutLinks">
            <div class="fixedMenuBurgerLayoutLinksLang">
                <i class="fas fa-globe"></i>
                @if(app()->getLocale()=="uz")
                    <div class="LinksLang LinksLangActive">
                        <a href="{{'https://'.Request::getHost().'/'.preg_replace("/uz/","ru",explode("/",Request::url(),4)[3],1)}}">  {{strtoupper('UZ')}}</a>
                    </div>
                @else
                    <div class="LinksLang LinksLangActive">
                        <a href="{{'https://'.Request::getHost().'/'.preg_replace("/ru/","uz",explode("/",Request::url(),4)[3],1)}}">{{mb_strtoupper('RU',"utf-8")}}</a>
                    </div>
                @endif

            </div>

            @if(auth()->check())

                @if(auth()->user()->role_id==2)
                    <a href="{{route('user.profile.index',['lang'=>app()->getLocale()])}}"><i
                                class="fas fa-user"></i> {{__('user_profile_title')}}</a>
                   
                @elseif(auth()->user()->role_id==1 || auth()->user()->role_id==4)
                    <a href="{{route('admin.dashboard',['lang'=>app()->getLocale()])}}"><i class="fas fa-user"></i>
                        Admin</a>

                @elseif(auth()->user()->role_id==3)
                    <a href="{{route('seller.dashboard',['lang'=>app()->getLocale()])}}"><i
                                class="fas fa-user"></i> {{__('seller')}}</a>

                @endif

            @endif


            @if (auth()->check() && auth()->user()->role_id==2)
                <a href="{{route('user.cart',['lang'=>app()->getLocale()])}}">
                    <i class="fas fa-shopping-cart"></i>
                    {{__('cart')}}
                </a>

            @elseif(!auth()->check())
                <a href="{{route('user.cart',['lang'=>app()->getLocale()])}}">
                    <i class="fas fa-shopping-cart"></i>
                    {{__('cart')}}
                </a>

            @endif

            @if (auth()->check() && auth()->user()->role_id==2)
                <a href="{{route('user.wish_list',['lang'=>app()->getLocale()])}}"><i
                            class="fas fa-heart"></i> {{__('wishlist_product')}} </a>
            @elseif(!auth()->check())
                <a href="{{route('user.login',['lang'=>app()->getLocale()])}}"><i
                            class="fas fa-heart"></i> {{__('wishlist_product')}} </a>
            @endif

            <a href="{{route('user.catalog',['lang'=>app()->getLocale()])}}">
                <i class="fas fa-th-list"></i>
                {{url()->current()==route('user.catalog',['lang'=>app()->getLocale()]) ? mb_strtoupper(__('header_catalog'),"utf-8") :__('header_catalog')}}
            </a>
            <a href="{{route('user.info_center.index',['lang'=>app()->getLocale()])}}">
                <i class="fas fa-paperclip"></i>
                {{url()->current()==route('user.info_center.index',['lang'=>app()->getLocale()]) ? mb_strtoupper(__('header_info_center'),"utf-8") :__('header_info_center')}}
            </a>
            <a href="{{route('user.about',['lang'=>app()->getLocale()])}}">
                <i class="fas fa-info-circle"></i>
                {{url()->current()==route('user.about',['lang'=>app()->getLocale()]) ? mb_strtoupper(__('header_about'),"utf-8") :__('header_about')}}
            </a>
            <a href="{{route('user.help',['lang'=>app()->getLocale()])}}"><i class="fas fa-question-circle"></i>
                {{url()->current()==route('user.help',['lang'=>app()->getLocale()]) ? mb_strtoupper(__('header_help'),"utf-8") :__('header_help')}}
            </a>

            @if (!auth()->check())
                <a href="{{route('user.login',['lang'=>app()->getLocale()])}}"><i class="fas fa-sign-in-alt"></i>
                    {{__('user_login_button')}}
                </a>
            @else
                <a href="{{route('user.logout',['lang'=>app()->getLocale()])}}"><i class="fas fa-sign-out-alt"></i>
                    {{__('user_logout_button')}}
                </a>
            @endif
        </div>
    </div>
</div>
<div class="header">
    <div class="content">

        <a href="{{route('user.index',['lang'=>app()->getLocale()])}}" class="logo"></a>
        <div class="b1" id="menu_nav">

            <div><a href="{{route('user.catalog',['lang'=>app()->getLocale()])}}" class="t1">
                    {{url()->current()==route('user.catalog',['lang'=>app()->getLocale()]) ? strtoupper(__('header_catalog')) :__('header_catalog')}}
                </a></div>
            <div><a href="{{route('user.info_center.index',['lang'=>app()->getLocale()])}}" class="t1">
                    {{url()->current()==route('user.info_center.index',['lang'=>app()->getLocale()]) ? strtoupper(__('header_info_center')) :__('header_info_center')}}

                </a></div>
            <div><a href="{{route('user.about',['lang'=>app()->getLocale()])}}" class="t1">
                    {{url()->current()==route('user.about',['lang'=>app()->getLocale()]) ? strtoupper(__('header_about')) :__('header_about')}}

                </a></div>
            <div><a href="{{route('user.help',['lang'=>app()->getLocale()])}}" class="t1">
                    {{url()->current()==route('user.help',['lang'=>app()->getLocale()]) ? strtoupper(__('header_help')) :__('header_help')}}

                </a></div>
        </div>
        <div class="b2">
            @if(app()->getLocale()=="uz")
                <a style="text-decoration: none;color: #FFFFFF;font-size: 22px"
                   href="{{'https://'.Request::getHost().'/'.preg_replace("/uz/","ru",explode("/",Request::url(),4)[3],1)}}">  {{strtoupper('UZ')}}</a>
            @else
                <a style="text-decoration: none;color: #FFFFFF;font-size: 22px"
                   href="{{'https://'.Request::getHost().'/'.preg_replace("/ru/","uz",explode("/",Request::url(),4)[3],1)}}">{{mb_strtoupper('RU',"utf-8")}}</a>
            @endif
            @if (auth()->check() && auth()->user()->role_id==2)
                <a href="{{route('user.cart',['lang'=>app()->getLocale()])}}" class="tcart"></a>
                @auth()
                    <a href="{{route('user.wish_list',['lang'=>app()->getLocale()])}}" class="like"></a>
                @else
                    <a href="{{route('user.login',['lang'=>app()->getLocale()])}}" class="like"></a>
                @endauth
            @elseif(!auth()->check())
                <a href="{{route('user.cart',['lang'=>app()->getLocale()])}}" class="tcart"></a>
                @auth()
                    <a href="{{route('user.wish_list',['lang'=>app()->getLocale()])}}" class="like"></a>
                @else
                    <a href="{{route('user.login',['lang'=>app()->getLocale()])}}" class="like"></a>
                @endauth
            @endif
            @if(auth()->check())
                <div id="dropdown_id">
                    <a class="profile"></a>
                    <div id="sub_dropdown_id" class="new_profile">
                        @if(auth()->user()->role_id==2)
                            <a class="t1" href="{{route('user.profile.index',['lang'=>app()->getLocale()])}}" style="text-decoration: none;font-family: Montserrat-Regular;font-size: 14px;
                color: #888888;">{{__('user_profile_title')}}</a>
                            <hr>
                        @elseif(auth()->user()->role_id==1 || auth()->user()->role_id==4)
                            <a class="t1" href="{{route('admin.dashboard',['lang'=>app()->getLocale()])}}" style="text-decoration: none;font-family: Montserrat-Regular;font-size: 14px;
                color: #888888;">Admin</a>
                            <hr>
                        @elseif(auth()->user()->role_id==3)
                            <a class="t1" href="{{route('seller.dashboard',['lang'=>app()->getLocale()])}}" style="text-decoration: none;font-family: Montserrat-Regular;font-size: 14px;
                color: #888888;">{{__('seller')}}</a>
                            <hr>
                        @endif
                        <a class="t1" href="{{route('user.logout',['lang'=>app()->getLocale()])}}" style="text-decoration: none;font-family: Montserrat-Regular;font-size: 14px;
                color: #888888;">{{__('user_logout_button')}}</a>
                    </div>
                </div>
            @else
                <a class="profile " href="{{route('user.login',['lang'=>app()->getLocale()])}}" style="text-decoration: none;font-family: Montserrat-Regular;font-size: 14px;
                color: #888888;"></a>
            @endif

        </div>
    </div>
</div>
<div class="headerSearchContent">
    <div class="headerSearchContentTitle">{!! __('header_text') !!}</div>
    <div style="position: relative;">
        <input onkeyup="searchByName(1)" id="searching_item_1" maxlength="32" type="text" class="search"
               placeholder="{{__('header_search')}}">
        <ul id="search_drop_1" class="search_drop"></ul>
    </div>
    <div id="go_to_catalog_id" class="b1" style="">
        <a href="{{route('user.catalog',['lang'=>app()->getLocale()])}}" class="list_ico"></a>
        <a href="{{route('user.catalog',['lang'=>app()->getLocale()])}}" style="color: #FFFFFF"
           class="t2">{{__('header_link')}}</a>
    </div>
</div>
<div class="vertical slider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1}'>
    <div>
        <div class="p1">
        <!-- <div class="t1">{!! __('header_text') !!}</div> -->
        <!-- <div style="position: relative;">
                <input onkeyup="searchByName(1)" id="searching_item_1" maxlength="32" type="text" class="search" placeholder="{{__('header_search')}}">
                <ul class="search_drop"></ul>
            </div>
            <div id="go_to_catalog_id" class="b1" style="">
                <a href="{{route('user.catalog',['lang'=>app()->getLocale()])}}" class="list_ico"></a>
                <a href="{{route('user.catalog',['lang'=>app()->getLocale()])}}" style="color: #FFFFFF"
                   class="t2">{{__('header_link')}}</a>
            </div> -->
        </div>
    </div>
    <div>
        <div class="p1"
             style="background: url(/assets/img/index/1.2.png) 50% 0px no-repeat;background-size: cover!important;">
        </div>
    </div>
    <div>
        <div class="p1"
             style="background: url(/assets/img/index/2.2.png) 50% 0px no-repeat;background-size: cover!important;">
        </div>
    </div>
</div>
