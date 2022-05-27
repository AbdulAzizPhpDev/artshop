@extends('user.layouts.page-layout')
@section('content')

    <div class="login">
        <div class="content">
            <div class="b1">
                <form action="{{route('user.change_password',app()->getLocale())}}" method="post" autocomplete="off">
                    @csrf
                    <div class="t1" style="text-align: center">
                        {{__('phone')}}
                    </div>
                    <input type="text" class="input" value="{{Session::get('restore')['phone']}}">
                    <div class="t1" style="text-align: center">
                        @error('confirm_number')
                        <spam style="color: #c40001">{{$message}}</spam>
                        @else
                            {{__('password_confirm')}}
                            @enderror
                    </div>
                    <input maxlength="6" type="text" class="input" name="confirm_number" value="{{old('confirm_number')}}">
                    <div class="t1" style="text-align: center">
                        @error('password')
                        <spam style="color: #c40001">{{$message}}</spam>
                        @else
                            {{__('user_login_password_label')}}
                            @enderror
                    </div>
                    <input  class="input" type="password" name="password" required>
                    <div class="t1" style="text-align: center">
                        @error('confirm_password ')
                        <spam style="color: #c40001">{{$message}}</spam>
                        @else
                            {{__('user_register_password_confirm')}}
                            @enderror
                    </div>
                    <input  class="input" type="password" name="confirm_password" required>


                    <div class="b2">
                        <button type="submit" class="btn btn_login">{{__('user_login_button')}}</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection

