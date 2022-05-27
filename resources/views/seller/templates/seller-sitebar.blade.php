<div class="sidebar active">
    <div class="open_menu"></div>
    <div class="unpin"></div>

    <a href="{{route('seller.dashboard',app()->getLocale())}}" class="logo"></a>
    <div class="menu">

        <div class="{{url()->current()==route('seller.dashboard',app()->getLocale()) ? "active" :""}}">
            <a href="{{route('seller.dashboard',app()->getLocale())}}"
               class="cat">{{__('admin_dashboard_statistics')}}</a>

        </div>

        <div><a class="title_block cat">{{__('company')}}</a></div>
        <div class="{{url()->current()==route('seller.company.about',app()->getLocale()) ? "active" :""}}">
            <a href="{{route('seller.company.about',app()->getLocale())}}" class="subcat">
                <div class="tab_ico tab_ico4"></div>
                {{__('about_company')}}
            </a>
        </div>
        <div class="{{url()->current()==route('seller.company.requisite',app()->getLocale()) ? "active" :""}}">
            <a href="{{route('seller.company.requisite',app()->getLocale())}}" class="subcat">
                <div class="tab_ico tab_ico5"></div>
                {{__('requisites')}}
            </a>
        </div>

        <div><a class="title_block cat">{{__('products')}}</a></div>
        <div class="{{url()->current()==route('seller.product.index',app()->getLocale()) ? "active" :""}}">
            <a href="{{route('seller.product.index',app()->getLocale())}}" class="subcat">
                <div class="tab_ico tab_ico1"></div>
                {{__('seller_p_list')}}
            </a>
        </div>
        <div class="{{url()->current()==route('seller.product.archive',app()->getLocale()) ? "active" :""}}">
            <a href="{{route('seller.product.archive',app()->getLocale())}}" class="subcat">
                <div class="tab_ico tab_ico9"></div>
                {{__('seller_p_archive')}}
            </a>
        </div>

        <div><a class="title_block cat">{{__('orders')}}</a></div>
        <div class="{{url()->current()==route('seller.order.index',app()->getLocale()) ? "active" :""}}">
            <a href="{{route('seller.order.index',app()->getLocale())}}" class="subcat">
                <div class="tab_ico tab_ico2"></div>
                {{__('order_list')}}
            </a>
        </div>
        <div class="{{url()->current()==route('seller.order.archive',app()->getLocale()) ? "active" :""}}">
            <a href="{{route('seller.order.archive',app()->getLocale())}}" class="subcat">
                <div class="tab_ico tab_ico10"></div>
                {{__('order_archive')}}
            </a>
        </div>

        <div><a class="title_block cat">{{__('delivery_t')}}</a></div>
        <div class="{{url()->current()==route('seller.delivery.index',app()->getLocale()) ? "active" :""}}">
            <a href="{{route('seller.delivery.index',app()->getLocale())}}" class="subcat">
                <div class="tab_ico tab_ico3"></div>
                {{__('delivery_s')}}
            </a>
        </div>
        <div class="{{url()->current()==route('seller.delivery.payment',app()->getLocale()) ? "active" :""}}">
            <a href="{{route('seller.delivery.payment',app()->getLocale())}}" class="subcat">
                <div class="tab_ico tab_ico3"></div>
                {{__('delivery_p')}}
            </a>
        </div>

        <div class="{{url()->current()==route('seller.review',app()->getLocale()) ? "active" :""}}">
            <a href="{{route('seller.review',app()->getLocale())}}" class="cat"
               style="margin-top: 40px">
                <div class="tab_ico tab_ico6"></div>
                {{__('comments')}}
            </a>
        </div>

    </div>
</div>
