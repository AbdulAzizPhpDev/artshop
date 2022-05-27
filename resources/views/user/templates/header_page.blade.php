@if (auth()->check() && auth()->user()->role_id==2)
    <a href="{{route('user.cart',['lang'=>app()->getLocale()])}}" class="fix_cart" id="cart_logo"
       style="{{session()->has('cart')  ? 'background: url(/assets/img/icons/full_cart.svg) #fbb03b 50% 50% no-repeat;':' '}}"></a>

@elseif(!auth()->check())
    <a href="{{route('user.cart',['lang'=>app()->getLocale()])}}" class="fix_cart" id="cart_logo"
       style="{{session()->has('cart')  ? 'background: url(/assets/img/icons/full_cart.svg) #fbb03b 50% 50% no-repeat;':' '}}"></a>

@endif
<div class="header header_pages">
    <div class="content">
        <a href="{{route('user.index',['lang'=>app()->getLocale()])}}" class="logo"></a>
        <div class="b1" id="menu_nav">

            <div><a href="{{route('user.catalog',['lang'=>app()->getLocale()])}}" class="t1">
                    {{url()->current()==route('user.catalog',['lang'=>app()->getLocale()]) ? mb_strtoupper(__('header_catalog'),"utf-8") :__('header_catalog')}}
                </a></div>
            <div><a href="{{route('user.info_center.index',['lang'=>app()->getLocale()])}}" class="t1">
                    {{url()->current()==route('user.info_center.index',['lang'=>app()->getLocale()]) ? mb_strtoupper(__('header_info_center'),"utf-8") :__('header_info_center')}}

                </a></div>
            <div><a href="{{route('user.about',['lang'=>app()->getLocale()])}}" class="t1">
                    {{url()->current()==route('user.about',['lang'=>app()->getLocale()]) ? mb_strtoupper(__('header_about'),"utf-8") :__('header_about')}}

                </a></div>
            <div><a href="{{route('user.help',['lang'=>app()->getLocale()])}}" class="t1">
                    {{url()->current()==route('user.help',['lang'=>app()->getLocale()]) ? mb_strtoupper(__('header_help'),"utf-8") :__('header_help')}}

                </a></div>
        </div>

        <div class="b2">


            @if(app()->getLocale()=="uz")
                <a style="text-decoration: none;color: #FFFFFF;font-size: 22px"
                   href="{{'https://'.Request::getHost().'/'.preg_replace("/uz/","ru",explode("/",Request::url(),4)[3],1)}}">  {{strtoupper('UZ')}}</a>
            @else
                <a style="text-decoration: none;color: #FFFFFF;font-size: 22px"
                   href="{{'https://'.Request::getHost().'/'.preg_replace("/ru/","uz",explode("/",Request::url(),4)[3],1) }}">{{mb_strtoupper('RU',"utf-8")}}</a>
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


