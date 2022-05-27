@extends('admin.layouts.admin-layout')
@section('content')
    <div class="main_info">
        <div class="sellers">
            <div class="title">{{__('admin_dashboard_users')}}</div>
            <div class="table_title">{{__('admin_dashboard_sellers')}}</div>
            <div class="filter_block">
                <form action="{{route('admin.member.seller.post.search',['lang'=>app()->getLocale()])}}" method="post">
                    @csrf
                    <input type="text" class="search" placeholder="{{__('search')}}" name="search"
                           value="{{isset($search)?$search: ""}}">
                </form>
                <a class="btn"
                   href="{{route('admin.member.add.seller',['lang'=>app()->getLocale()])}}">{{__('admin_users_seller_add')}}</a>

            </div>
            <div class="clear"></div>
            <div class="table_block">
                <table>
                    <tbody>
                    <tr style="font-weight: bold">
                        <th class="">ID</th>
                        <th class="row_min1">{{__('full_name_abbr')}}</th>
                        <th class="row3">{{__('user_login_label')}}</th>
                        <th class="row3">{{__('admin_regions_register_date')}}</th>
                        <th class="">{{__('actions')}}</th>
                    </tr>
                    @if(count($sellers)>0)
                        @foreach($sellers as $seller)
                            <tr id="table_row_id">
                                <th>{{$seller->id}}</th>
                                <th>{{$seller->name}}</th>
                                <th>
                                    <p>{{$seller->phone_number}}</p>
                                    @if($seller->phone_number)
                                        <hr>
                                    @endif
                                    <p>{{$seller->user_name}}</p>

                                </th>
                                <th>{{$seller->created_at}}</th>

                                <th>
                                    <div class="b1">
                                        <button style="background-color: #FFFFFF;
    border: 2px solid #5eaff0;
    color: #000;
    display: block;
    text-decoration: none;padding-top: 0px;" onclick="updateStatusUserAndSeller({{$seller->id}},'inactive')"
                                                class="btn_block">{{__('block')}}
                                        </button>
                                        <a class="view"
                                           href="{{route('admin.member.update.seller',['id'=>$seller->id,'lang'=>app()->getLocale()])}}"></a>
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
    @error('success')
    <input type="hidden" id="check_success_1" value="1">
    @enderror
@endsection
@section('script')

    <script>

        $(document).ready(function () {
            popUp()
        });

        function popUp() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
            if ($('#check_success_1').val() == 1) {
                Toast.fire({
                    icon: 'success',
                    title: '{{__('data_success')}}'
                })
            }
        }
    </script>

@endsection
