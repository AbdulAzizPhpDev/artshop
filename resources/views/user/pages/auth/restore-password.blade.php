@extends('user.layouts.page-layout')
@section('content')

    <div class="login">
        <div class="content">
            <div class="b1">
                <form action="{{route('user.post_restore_password',app()->getLocale())}}" method="post" autocomplete="off">
                    @csrf
                    <div class="t1" style="text-align: center">
                        @error('phone_number')
                        <spam style="color: #c40001">{{$message}}</spam>
                        @else
                            {{__('password_reset')}}
                            @enderror
                    </div>
                    <input id="phone-number" maxlength="17" type="text" class="input" required
                           placeholder="+998-XX-XXX-XX-XX" name="phone_format">
                    <input type="hidden" name="phone_number" id="input_phone_id">
                    <div class="b2">

                        <button type="submit" class="btn btn_login">{{__('user_login_button')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

