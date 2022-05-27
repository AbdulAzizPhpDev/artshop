@extends('user.layouts.page-layout')
@section('my_style')
    <style>
        input {
            height: 37px !important;
        }
    </style>
@endsection
@section('content')
    <div class="register">
        <div class="content">
            <div class="b1">
                <div class="tabs-block">
                    <div class="tabs">
                        @if(app()->getLocale()=='uz')
                            <a style="line-height: 30px!important;height: 60px;"
                               class="tab active">{{__('user_register_user_title')}}</a>
                        @else
                            <a style="line-height: 60px!important;height: 60px;"
                               class="tab active">{{__('user_register_user_title')}}</a>
                        @endif
                         @if(app()->getLocale()=='uz')
                        <a style="line-height: 30px!important;height: 60px;"
                           href="{{route('user.register_seller',['lang'=>app()->getLocale()])}}" class="tab">
                            {{__('user_register_seller_title')}}
                        </a>
                         @else
                          <a style="line-height: 60px!important;height: 60px;"
                           href="{{route('user.register_seller',['lang'=>app()->getLocale()])}}" class="tab">
                            {{__('user_register_seller_title')}}
                        </a>
                           @endif
                    </div>
                    <div class="tabs-content">
                        <form action="{{route('user.register_post',['lang'=>app()->getLocale()])}}" method="post"
                              autocomplete="off">
                            @csrf
                            <input type="hidden" value="2" name="type">
                            <div class="tab-item">
                                <div class="b2">
                                    <div class="t1">{{__('user_register_user_title')}}</div>
                                    <div class="t2">* {{__('user_register_warning')}}.</div>
                                    <div class="t3">
                                        @error('full_name')
                                        <span style="color: red" >{{$message}}</span>

                                        @else
                                            {{__('user_register_user_name_label')}}
                                            <span> *</span>
                                            @enderror
                                    </div>
                                    <input type="text" name="full_name" required>
                                    <div class="t3">
                                        @error('phone_number')
                                        <span style="color: red" >{{$message}}</span>
                                        @else
                                            {{__('user_register_phone_label')}}
                                            @enderror
                                            <span> *</span></div>
                                    <input id="phone-number" maxlength="17" type="text" required>
                                    <input type="hidden" name="phone_number" id="input_phone_id">
                                    <div class="t3">

                                        @error('password')
                                        <span style="color: red" >{{$message}}</span>
                                        @else
                                            {{__('user_login_password_label')}}
                                            @enderror
                                            <span> *</span>
                                    </div>

                                    <input type="password" name="password" required>
                                    <div class="t3">
                                        @error('confirm_password')
                                        <span style="color: red" >{{$message}}</span>
                                        @else
                                            {{__('user_register_password_confirm')}}
                                            @enderror

                                            <span> *</span></div>
                                    <input type="password" name="confirm_password" required>
                                    <div class="b3">
                                        <div class="input_cont">
                                            <input type="checkbox" id="imnotrobot" required>
                                            <label for="imnotrobot">
                                                {{__('user_register_robot')}}
                                            </label>
                                        </div>
                                        <div class="t4"><a href="">{{__('privacy_title')}} /</a>
                                            <span>  <a href=""> {{__('terms_title')}}</a></span>
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
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
