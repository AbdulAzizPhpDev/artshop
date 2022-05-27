@extends('user.layouts.page-layout')
@section('content')
    <div class="login">
        <div class="content">
            <div class="b1">
                <form action="{{route('user.confirm_post',['lang'=>app()->getLocale()])}}" method="post">
                    @csrf
                    <div class="t1">
                        @error('confirm_number')
                        <spam style="color: #c40001">{{$message}}</spam>
                        @else
                            <spam>{{__('user_confirm_label')}}</spam>
                            @enderror
                    </div>
                    <input maxlength="6" type="text" class="input" name="confirm_number" value="{{old('confirm_number')}}">
                    <div class="b2">
                        {{--                        <a class="btn" href="{{route('user.register')}}">Регистрация</a>--}}
                        <a href="{{route('user.resend_sms',['lang'=>app()->getLocale()])}}" class="btn btn_password_reset">{{__('user_confirm_resent_code')}}</a>
                        <button type="submit" class="btn btn_login">{{__('user_confirm_button')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
