@extends('admin.layouts.admin-layout')
@section('content')
    <div class="main_info">
        <div class="buyers_list active">
            <div class="title">{{__('storage')}}</div>
            <div class="buyers">
                <div class="table_title">{{__('storage_user')}}</div>
                <div class="filter_block">
                    <form action="{{route('admin.archive.user.post.search',app()->getLocale())}}" method="post">
                        @csrf
                        <input type="text" class="search" placeholder="{{__('search')}}" name="search"
                               value="{{isset($search)?$search: ""}}">
                    </form>

                </div>
                <div class="clear"></div>
                <div class="table_block">
                    <table>
                        <tbody>
                        <tr style="font-weight: bold">
                            <th class="">ID</th>
                            <th class="row_min1">{{__('full_name_abbr')}}</th>
                            <th class="row_min1">{{__('phone')}}</th>
                            <th class="">{{__('date')}}</th>
                            <th class="">{{__('actions')}}</th>
                        </tr>
                        @if(count($users)>0)
                            @foreach($users as $user)
                                <tr id="table_col_{{$user->id}}">
                                    <th>{{$user->id}}</th>
                                    <th>{{$user->name}}</th>
                                    <th>{{$user->phone_number}}</th>
                                    <th>{{$user->created_at}}</th>
                                    <th>
                                        <div class="b1">
                                            <button style="background-color: #FFFFFF;
    border: 2px solid #5eaff0;
    color: #000;
    display: block;
    text-decoration: none;
    padding-top: 0px;" onclick="updateStatusUserAndSeller({{$user->id}},'active')"
                                                    id="update_status_{{$user->id}}" class="btn_block">{{__('active')}}
                                            </button>

                                            <div class="trash"></div>
                                        </div>
                                    </th>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    @component('admin.components.pagination',['pagination'=>$users])
                    @endcomponent

                </div>
            </div>
        </div>

    </div>
@endsection