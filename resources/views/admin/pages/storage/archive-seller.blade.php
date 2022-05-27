@extends('admin.layouts.admin-layout')
@section('content')
    <div class="main_info">
        <div class="buyers_list active">
            <div class="title">{{__('storage')}}</div>
            <div class="buyers">
                <div class="table_title">{{__('storage_seller')}}</div>
                <div class="filter_block">
                    <form action="{{route('admin.archive.seller.post.search',app()->getLocale())}}" method="post">
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
                            <th class="row_min1">{{__('admin_users_seller_login_title')}}</th>
                            <th class="">{{__('date')}}</th>
                            <th class="">{{__('actions')}}</th>
                        </tr>

                        @if(count($sellers)>0)
                            @foreach($sellers as $seller)
                                <tr id="table_col_{{$seller->id}}">
                                    <th>{{$seller->id}}</th>
                                    <th>{{$seller->name}}</th>
                                    <th>{{$seller->user_name}}</th>
                                    <th>{{$seller->created_at}}</th>
                                    <th>
                                        <div class="b1">
                                            <button style="background-color: #FFFFFF;
    border: 2px solid #5eaff0;
    color: #000;
    display: block;
    text-decoration: none;
    padding-top: 0px;" onclick="updateStatusUserAndSeller({{$seller->id}},'active')"
                                                    id="update_status_{{$seller->id}}" class="btn_block">{{__('active')}}
                                            </button>

                                            <div class="trash"></div>
                                        </div>
                                    </th>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    @component('admin.components.pagination',['pagination'=>$sellers])
                    @endcomponent

                </div>
            </div>
        </div>

    </div>
@endsection