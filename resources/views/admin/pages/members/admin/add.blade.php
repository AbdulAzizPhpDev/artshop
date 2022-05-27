@extends('admin.layouts.admin-layout')
@section('content')
    <div class="main_info">
        <div class="addadmin">
            <form action="{{route('admin.member.post.admin',app()->getLocale())}}" method="post">
                @csrf
                <input type="hidden" name="id" value="0">
                <div class="title">{{__('admin_add')}}</div>
                <div class="table_title">{{__('login_and_pass')}}</div>
                <div class="t1">
                    @error('name')
                    <spam style="color: #c40001">{{$message}}</spam>
                    @else
                        {{__('full_name')}}
                        @enderror

                </div>
                <input value="{{old('name')}}" name="name" type="text" placeholder=" {{__('full_name')}}" class="field"
                       required>

                <div class="t1">
                    @error('user_name')
                    <spam style="color: #c40001">{{$message}}</spam>
                    @else
                        {{__('admin_users_seller_login_title')}}
                        @enderror
                </div>
                <input value="{{old('user_name')}}" name="user_name" type="text" placeholder="test@artshop.uz"
                       class="field" required>
                <div class="clear"></div>
                <div class="b2">
                    <div class="t1">
                        @error('password')
                        <spam style="color: #c40001">{{$message}}</spam>
                        @else
                            {{__('user_login_password_label')}}
                            @enderror</div>
                    <input value="{{old('password')}}" name="password" type="password" placeholder="{{__('user_login_password_label')}}"
                           class="field field_min" required>
                </div>
                <div class="b2 b2_min">
                    <div class="t1">
                        @error('role')
                        <spam style="color: #c40001">{{$message}}</spam>
                        @else
                        {{__('choose_role')}}
                            @enderror
                    </div>
                    <select class="select" name="role" required>

                        <option value="1">{{__('admin')}}</option>
                        <option value="4">{{__('moderator')}}</option>
                    </select>
                </div>
                <div class="clear"></div>

                <div class="b1">
                    <a href="{{route('admin.member.admin.index',app()->getLocale())}}" style="text-decoration: none"
                       class="btn back_b_list">{{__('cancel')}}</a>
                    <button type="submit" class="btn buyer_save">{{__('save')}}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
