@extends('admin.layouts.admin-layout')
@section('content')
    <div class="main_info">
        <div class="addadmin">
            <form action="{{route('admin.member.post.admin',app()->getLocale())}}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{$admin->id}}">
                <div class="title">{{__('admin_edit')}}</div>
                <div class="table_title">{{__('login_and_pass')}}</div>

                <div class="t1">
                    @error('name')
                    <spam style="color: #c40001">{{$message}}</spam>
                    @else
                        {{__('full_name')}}
                        @enderror
                </div>
                <input value="{{$admin->name}}" name="name" type="text" placeholder="Отчество"
                       class="field" required>
                <div class="t1">
                    @error('user_name')
                    <spam style="color: #c40001">{{$message}}</spam>
                    @else
                        {{__('admin_users_seller_login_title')}}
                        @enderror
                </div>
                <input value="{{$admin->user_name}}" name="user_name" type="text" placeholder="test@artshop.uz"
                       class="field" required>
                <div class="clear"></div>
                <div class="b2">
                    <div class="t1">
                        @error('password')
                        <spam style="color: #c40001">{{$message}}</spam>
                        @else
                             {{__('user_login_password_label')}}
                            @enderror</div>
                    <input name="password" type="password" placeholder=" {{__('user_login_password_label')}}"
                           class="field field_min">
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
                        <option value="null">Роль</option>
                        <option value="1" {{$admin->role_id==1 ? "selected":" "}}>{{__('admin')}}</option>
                        <option value="4" {{$admin->role_id==4 ? "selected":" "}} >{{__('moderator')}}</option>
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
