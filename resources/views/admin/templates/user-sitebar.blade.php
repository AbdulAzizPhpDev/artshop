<div class="sidebar active">
    <div class="open_menu"></div>
    <div class="unpin"></div>

    <a href="{{route('user.index',['lang'=>app()->getLocale()])}}" class="logo"></a>
    <div class="menu">
        <div class="{{url()->current()==route('user.profile.index',['lang'=>app()->getLocale()]) ? "active" :""}}"><a
                    href="{{route('user.profile.index',['lang'=>app()->getLocale()])}}"
                    class="cat">{{__('user_profile_main')}}</a></div>

        <div><a class="title_block cat">{{__('user_profile_title')}}</a></div>
        <div class="{{url()->current()==route('user.profile.information',['lang'=>app()->getLocale()]) ? "active" :""}}">
            <a href="{{route('user.profile.information',['lang'=>app()->getLocale()])}}" class="subcat">
                <div class="tab_ico tab_ico3"></div>
                {{__('user_profile_info_site_bar_title')}}
            </a>
        </div>
        <div class="{{url()->current()==route('user.profile.security',['lang'=>app()->getLocale()]) ? "active" :""}}">
            <a href="{{route('user.profile.security',['lang'=>app()->getLocale()])}}" class="subcat">
                <div class="tab_ico tab_ico5"></div>
                {{__('user_security_title')}}
            </a>
        </div>

        <div><a class="title_block cat">{{__('user_site_bar_title')}}</a></div>
        <div class="{{url()->current()==route('user.profile.orders',['lang'=>app()->getLocale()]) ? "active" :""}}">
            <a href="{{route('user.profile.orders',['lang'=>app()->getLocale()])}}" class="subcat">
                <div class="tab_ico tab_ico1"></div>
                {{__('user_order_title')}}
            </a>
        </div>
        <div class="{{url()->current()==route('user.profile.wishlist',['lang'=>app()->getLocale()]) ? "active" :""}}">
            <a href="{{route('user.profile.wishlist',['lang'=>app()->getLocale()])}}" class="subcat">
                <div class="tab_ico tab_ico9"></div>
                {{__('wishlist_product')}}
            </a>
        </div>
        <div class="{{url()->current()==route('user.profile.reviews',['lang'=>app()->getLocale()]) ? "active" :""}}">
            <a href="{{route('user.profile.reviews',['lang'=>app()->getLocale()])}}" class="cat"
               style="margin-top: 40px">
                <div class="tab_ico tab_ico6"></div>
                {{__('comments')}}
            </a>
        </div>
{{--        <div class="{{url()->current()==route('user.profile.messages',['lang'=>app()->getLocale()]) ? "active" :""}}">--}}
{{--            <a href="{{route('user.profile.messages',['lang'=>app()->getLocale()])}}" class="cat"--}}
{{--               style="margin-top: 40px">--}}
{{--                <div class="tab_ico tab_ico6"></div>--}}
{{--                {{__('messages')}}--}}
{{--            </a>--}}
{{--        </div>--}}

    </div>
</div>
