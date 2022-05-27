<div class="header">
    <a href="index.php" class="logo"></a>
    <div class="b2">

        <div class="t1">{{__('seller_cabinet')}}</div>
        <div class="t2">"{{Auth::user()->name}}"</div>
    </div>
    <div class="b1">
        <div class="lang">
            @if(app()->getLocale()=="uz")
                <a style="text-decoration: none;color: #9c9fa6;"
                   href="{{'https://'.Request::getHost().'/'.preg_replace("/uz/","ru",explode("/",Request::url(),4)[3],1)}}"> {{mb_strtoupper('RU',"utf-8")}}</a>
            @else
                <a style="text-decoration: none;color: #9c9fa6;"
                   href="{{'https://'.Request::getHost().'/'.preg_replace("/ru/","uz",explode("/",Request::url(),4)[3],1) }}"> {{strtoupper('UZ')}}</a>
            @endif
        </div>
        <div class="profile">
            <div class="b3" style="">
                <a href="{{route('user.index',app()->getLocale())}}" style="text-decoration: none;font-family: Montserrat-Regular;font-size: 16px;
    color: #9c9fa6;">{{__('main')}}</a>
                <hr>
                <a href="{{route('user.logout',app()->getLocale())}}" style="text-decoration: none;font-family: Montserrat-Regular;font-size: 16px;
    color: #9c9fa6;">{{__('user_logout_button')}}</a>


            </div>
        </div>
    </div>

</div>
