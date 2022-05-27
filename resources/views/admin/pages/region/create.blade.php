@extends('admin.layouts.admin-layout')
@section('content')
    <div class="main_info">
        <div class="add product_e addseller">
            <div class="title">{{__('admin_region_creat_title')}}</div>
            <form action="{{route('admin.region.store',['lang' => app()->getLocale()])}}" method="post">
                @csrf
                <input type="hidden" name="id" value="0">
                <input type="hidden" name="parent_id" value="0">
                <input type="hidden" name="redirect_url" value="{{$redirect_url}}">

                <div class="b5">
                    <div class="inp_titles"> {{__('admin_regions_add')}}</div>

                    <div class="b6">
                        <div class="b7">
                            <div class="inp_label">{{__('admin_region_name_uz')}}</div>
                            <input type="text" required class="inp2" name="name_uz">

                        </div>
                        <div class="b7">
                            <div class="inp_label">{{__('admin_region_name_ru')}}</div>
                            <input type="text" required class="inp2" name="name_ru">
                        </div>
                    </div>

                </div>

                <button style="background-color: #FFFFFF;
    border: 2px solid #5eaff0;
    color: #000;
    display: block;
    text-decoration: none;" type="submit" class="btn save">{{__('save')}}</button>
            </form>
        </div>
    </div>
@endsection

