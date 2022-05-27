@extends('user.layouts.page-layout')
@section('content')
    <div class="register">
        <div class="content">
            <div class="b1">
                <div class="tabs-block">
                    <div class="tabs">
                        @if(app()->getLocale()=='uz')
                            <a style="line-height: 30px!important;height: 60px;"
                               href="{{route('user.register',['lang'=>app()->getLocale()])}}" class="tab">
                                {{__('user_register_user_title')}}
                            </a>
                        @else
                            <a style="line-height: 60px!important;height: 60px;"
                               href="{{route('user.register',['lang'=>app()->getLocale()])}}" class="tab">
                                {{__('user_register_user_title')}}
                            </a>
                        @endif
                         @if(app()->getLocale()=='uz')
                        <a style="line-height: 30px!important;height: 60px;"
                           class="tab active"> {{__('user_register_seller_title')}}</a>

                            @else
 <a style="line-height: 60px!important;height: 60px;"
                           class="tab active"> {{__('user_register_seller_title')}}</a>
                             @endif

                    </div>
                    <div class="tabs-content">
                        <div class="tab-item">
                            <form action="{{route('user.register_post',['lang'=>app()->getLocale()])}}" method="post"
                                  autocomplete="off">
                                @csrf
                                <input type="hidden" value="3" name="type">

                                <div class="b2">
                                    <div class="t1"> {{__('user_register_seller_title')}}</div>
                                    @error('type')
                                    <div class="t1" style="color: red">{{$message}}</div>
                                    @enderror
                                    <div class="t2">* {{__('user_register_warning')}}.</div>

                                    <div class="t3">
                                        @error('full_name')
                                       <span style="color: red" >{{$message}}</span>
                                        @else
                                            {{__('user_register_seller_name_label')}}
                                            <span> *</span>
                                            @enderror
                                    </div>
                                    <input type="text" name="full_name" required value="{{old('full_name')}}">
                                    <div class="t3">
                                        @error('phone_number')
                                       <span style="color: red" >{{$message}}</span>
                                        @else
                                            {{__('user_register_phone_label')}}
                                            @enderror

                                            <span> *</span></div>
                                    <input id="phone-number" name="phone_format" maxlength="17" type="text" required
                                           value="{{old('phone_format')}}">
                                    <input type="hidden" name="phone_number" id="input_phone_id"
                                           value="{{old('phone_number')}}">
                                    <div class="t3">

                                        @error('password')
                                       <span style="color: red" >{{$message}}</span>
                                        @else
                                            {{__('user_login_password_label')}}
                                            @enderror
                                            <span> *</span>
                                    </div>

                                    <input type="password" name="password" required value="{{old('password')}}">
                                    <div class="t3">
                                        @error('confirm_password')
                                       <span style="color: red" >{{$message}}</span>
                                        @else
                                            {{__('user_register_password_confirm')}}
                                            @enderror

                                            <span> *</span></div>
                                    <input type="password" name="confirm_password" required
                                           value="{{old('confirm_password')}}">
                                    <div class="b3">
                                        <div class="input_cont">
                                            <input type="checkbox" id="imnotrobot">
                                            <label for="imnotrobot">  {{__('user_register_robot')}}</label>
                                        </div>
                                        <div class="t4"><a href="">{{__('privacy_title')}} /</a>
                                            <span><a href=""> {{__('terms_title')}}</a></span>
                                        </div>
                                    </div>
                                    <div class="input_cont">
                                        <input type="checkbox" id="policy"><label class="policy" for="policy">
                                            {{__('privacy_text')}}
                                            <a
                                                    href="">{{__('privacy_text_2')}}</a> {{__('privacy_text_3')}}
                                        </label>
                                    </div>
                                    <button type="submit" class="btn reg_btn">{{__('user_login_register')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
