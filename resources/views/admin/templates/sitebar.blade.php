<div class="sidebar active">
    <div class="open_menu"></div>
    <div class="unpin"></div>
    <a href="{{route('admin.dashboard',['lang'=>app()->getLocale()])}}" class="logo"></a>
    <div class="menu">
        <div class="{{url()->current()==route('admin.dashboard',['lang'=>app()->getLocale()]) ? "active" :""}}"><a
                    href="{{route('admin.dashboard',['lang'=>app()->getLocale()])}}"
                    class="cat">{{__('admin_site_bar_title')}}</a></div>
        <div><a class="title_block cat">{{__('catalog')}}</a></div>

        <div class="{{url()->current()==route('admin.product.index',['lang'=>app()->getLocale()]) ? "active" :""}}"><a
                    href="{{route('admin.product.index',['lang'=>app()->getLocale()])}}" class="subcat">
                <div class="tab_ico tab_ico1"></div>
                {{__('products')}}</a></div>

        <div class="{{url()->current()==route('admin.catalog.index',['lang'=>app()->getLocale()]) ? "active" :""}}"><a
                    href="{{route('admin.catalog.index',['lang'=>app()->getLocale()])}}" class="subcat">
                <div class="tab_ico tab_ico2"></div>
                {{__('catalog')}}</a></div>
        <div class="{{url()->current()==route('admin.region.index',['lang'=>app()->getLocale()]) ? "active" :""}}"><a
                    href="{{route('admin.region.index',['lang'=>app()->getLocale()])}}" class="subcat">
                <div class="tab_ico tab_ico2"></div>
                {{__('admin_regions_title')}}</a></div>
        <div><a class="title_block cat">{{__('admin_dashboard_users')}}</a></div>

        <div class="{{url()->current()==route('admin.member.user.index',['lang'=>app()->getLocale()]) ? "active" :""}}">
            <a
                    href="{{route('admin.member.user.index',['lang'=>app()->getLocale()])}}" class="subcat">
                <div class="tab_ico tab_ico3"></div>
                {{__('admin_dashboard_buyers')}}
            </a>
        </div>

        <div
                class="{{url()->current()==route('admin.member.seller.index',['lang'=>app()->getLocale()]) ? "active" :""}}">
            <a
                    href="{{route('admin.member.seller.index',['lang'=>app()->getLocale()])}}" class="subcat">
                <div class="tab_ico tab_ico4"></div>
                {{__('admin_dashboard_sellers')}}
            </a>
        </div>
        <div
                class="{{url()->current()==route('admin.member.admin.index',['lang'=>app()->getLocale()]) ? "active" :""}}">
            <a
                    href="{{route('admin.member.admin.index',['lang'=>app()->getLocale()])}}" class="subcat">
                <div class="tab_ico tab_ico5"></div>
                {{__('admin_dashboard_admins')}}</a></div>
        <div><a
                    class="title_block cat">{{__('messages')}}</a></div>
        <div
                class="{{url()->current()==route('admin.message.feedback.index',['lang'=>app()->getLocale()]) ? "active" :""}}">
            <a href="{{route('admin.message.feedback.index',['lang'=>app()->getLocale()])}}" class="subcat">
                <div class="tab_ico tab_ico6"></div>
                Обратная связь</a></div>
        <div><a
                    class="title_block cat">{{__('header_info_center')}}</a></div>
        <div class="{{url()->current()==route('admin.video.index',['lang'=>app()->getLocale()]) ? "active" :""}}"><a
                    href="{{route('admin.video.index',['lang'=>app()->getLocale()])}}" class="subcat">
                <div class="tab_ico tab_ico7"></div>
            {{__('video_site_bar')}}</a></div>
        <div class="{{url()->current()==route('admin.section.index',['lang'=>app()->getLocale()]) ? "active" :""}}"><a
                    href="{{route('admin.section.index',['lang'=>app()->getLocale()])}}" class="subcat">
                <div class="tab_ico tab_ico8"></div>
            {{__('section_site_bar')}}</a></div>
        <div><a
                    class="title_block cat">{{__('storage')}}</a></div>
        <div class="{{url()->current()==route('admin.archive.seller',['lang'=>app()->getLocale()]) ? "active" :""}}">
            <a href="{{route('admin.archive.seller',['lang'=>app()->getLocale()])}}" class="subcat">
                <div class="tab_ico tab_ico9"></div>
               {{__('storage_seller')}}
            </a>
        </div>
        <div class="{{url()->current()==route('admin.archive.purchaser',['lang'=>app()->getLocale()]) ? "active" :""}}">
            <a href="{{route('admin.archive.purchaser',['lang'=>app()->getLocale()])}}" class="subcat">
                <div class="tab_ico tab_ico10"></div>
                {{__('storage_user')}}
            </a>
        </div>
    </div>
</div>

