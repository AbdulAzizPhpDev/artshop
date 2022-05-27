@extends('admin.layouts.admin-layout')
@section('content')
    <div class="main_info">
        <form action="{{route('admin.member.post.user')}}" method="post" autocomplete="off">
            @csrf
            <input type="hidden" value="{{$user->id}}" name="id">
            <div class="buyer_info active" id="buyer_info1">
                <div class="title">Пользователь: <span>{{$user->name}}</span></div>
                <div class="buyers">
                    <div class="table_title">Параметры пользователя</div>
                    <div class="t1">
                        @error('name')
                        <span style="color: red">{{$message}}</span>
                        @else
                            full name
                            @enderror
                    </div>
                    <input name="name" type="text" placeholder="Имя" class="field" value="{{$user->name}}">
                    <div class="t1">
                        @error('phone_number')
                        <span style="color: red">{{$message}}</span>
                        @else
                            Телефон
                            @enderror
                    </div>
                    <input type="text" class="field" id="phone-number" maxlength="17"
                           placeholder="+998-90-000-00-00" value="{{$new_phone}}" name="phone_format">
                    <input type="hidden" name="phone_number" id="input_phone_id"
                           value="{{$user->phone_number!=null ? $user->phone_number : old('phone_number')}}">
                    <div class="t1">
                        @error('user_name')
                        <span style="color: red">{{$message}}</span>
                        @else
                            User name
                            @enderror
                    </div>
                    <input name="user_name" type="text" placeholder="test@artshop.uz" class="field"
                           value="{{$user->user_name}}">
                    <div class="b1">
                        <a href="{{route('admin.member.user.index')}}" style="text-decoration: none"
                           class="btn back_b_list">Вернуться</a>
                        <button type="submit" class="btn save">Сохранить</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection