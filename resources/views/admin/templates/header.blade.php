<div class="header end">
    <div class="b1" style="width: 200px!important;">
        <div class="lang">
            @if(app()->getLocale()=="uz")
                <a style="text-decoration: none;"
                   href="{{'https://'.Request::getHost().'/'.preg_replace("/uz/","ru",explode("/",Request::url(),4)[3],1)}}"> {{mb_strtoupper('RU',"utf-8")}}</a>
            @else
                <a style="text-decoration: none;"
                   href="{{'https://'.Request::getHost().'/'.preg_replace("/ru/","uz",explode("/",Request::url(),4)[3],1) }}"> {{strtoupper('UZ')}}</a>
            @endif
        </div>
        <div class="profile">
            <div class="b3" style="">

                <a href="{{route('user.index',['lang'=>app()->getLocale()])}}" style="text-decoration: none;font-family: Montserrat-Regular;font-size: 16px;
    color: #888888;">{{__('main')}}</a>
                <hr>
                <a href="{{route('user.logout',['lang'=>app()->getLocale()])}}" style="text-decoration: none;font-family: Montserrat-Regular;font-size: 16px;
    color: #888888;">{{__('user_logout_button')}}</a>
            </div>
        </div>
    </div>
</div>

