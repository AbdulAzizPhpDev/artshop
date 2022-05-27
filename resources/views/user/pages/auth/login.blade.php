@extends('user.layouts.page-layout')
@section('my_style')
    <style>
        input {
            height: 37px !important;
        }
    </style>
@endsection
@section('content')

    <div class="login">
        <div class="content">
            <div class="b1">
                <form action="{{route('user.login_post',['lang'=>app()->getLocale()])}}" method="post"
                      autocomplete="off">
                    @csrf
                    <div class="t1">
                        @error('phone_number')
                        <spam style="color: #c40001">{{$message}}</spam>
                        @else
                            {{__('user_login_label')}}
                            @enderror
                    </div>
                    <input type="text" class="input" id="user_name"
                           placeholder="+998xxxxxxxxx / Anvar_rustamov" name="phone_number"
                           value="{{old('phone_number')}}" required>
                    <div class="t1">
                        @error('password')
                        <spam style="color: #c40001">{{$message}}</spam>
                        @else
                            {{__('user_login_password_label')}}
                            @enderror</div>
                    <input type="password" class="input" name="password" value="{{old('password')}}" required>
                    <div class="b2">
                        <a class="btn" href="{{route('user.register',['lang'=>app()->getLocale()])}}">
                            {{__('user_login_register')}}
                        </a>
                        <a class="btn btn_password_reset"
                           href="{{route('user.restore_password',['lang'=>app()->getLocale()])}}">
                            {{__('user_login_password_restore')}}
                        </a>
                        <button type="submit" class="btn btn_login">{{__('user_login_button')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

