@extends('admin.layouts.admin-layout')
@section('content')
    <div class="main_info">
        <div class="admins">
            <div class="title">{{__('admin_dashboard_users')}}</div>
            <div class="table_title">{{__('admins')}}</div>

            <form action="{{route('admin.member.admin.post.search',app()->getLocale())}}" method="post" id="form_id">
                <div class="filter_block">
                    @csrf
                    <input type="text" class="search" placeholder="{{__('search')}}" name="search"
                           value="{{isset($search)?$search: ""}}">
                    <select class="select" name="role" id="role_id">
                        <option value="0">{{__('role')}}</option>
                        @if(isset($role_id))
                            <option value="1" {{$role_id==1 ? "selected":""}} >{{__('admin')}}</option>
                            <option value="4"{{$role_id==4 ? "selected":""}} >{{__('moderator')}}</option>
                        @else
                            <option value="1">{{__('admin')}}</option>
                            <option value="4">{{__('moderator')}}</option>

                        @endif

                    </select>
                    <a class="btn" href="{{route('admin.member.add.admin',app()->getLocale())}}">{{__('admin')}}</a>
                </div>
            </form>


            <div class="clear"></div>
            <div class="table_block">
                <table>
                    <tbody>
                    <tr style="font-weight: bold">
                        <th class="">ID</th>
                        <th class="row_min1">{{__('full_name_abbr')}}</th>
                        <th class="row_min1">{{__('phone')}}</th>
                        <th class="">{{__('date')}}</th>
                        <th class="">{{__('role')}}</th>
                        <th class="">{{__('actions')}}</th>
                    </tr>
                    @if(count($admins)>0)
                        @foreach($admins as $admin)
                            <tr id="table_row_id">
                                <th>{{$admin->id}}</th>
                                <th>{{$admin->name}}</th>
                                <th>{{$admin->phone_number}}</th>
                                <th>{{$admin->created_at}}</th>
                                <th id="get_name_{{$admin->id}}">
                                    @if($admin->role_id==1)
                                        {{__('admin')}}
                                    @elseif($admin->role_id==4)
                                        {{__('moderator')}}
                                    @else
                                        Unknown
                                    @endif
                                </th>
                                <th>
                                    <div class="b1">
                                        <a href="{{route('admin.member.update.admin',['id'=>$admin->id,'lang'=>app()->getLocale()])}}"
                                           class="view buyer_view" data-num="1"></a>
                                        <div class="trash" onclick="showModal({{$admin->id}})"></div>
                                    </div>
                                </th>
                            </tr>
                        @endforeach
                    @endif

                    </tbody>
                </table>
                @component('admin.components.pagination',['pagination'=>$admins])
                @endcomponent
            </div>
        </div>

    </div>

    <input type="hidden" value="{{route('admin.member.admin.index',app()->getLocale())}}" id="redirect_url">
    <div class="popup" id="modal_id" style="display:none;">
        <input type="hidden" value="null" id="delete_product_id">
        <div class="fade"></div>
        <div style="width: 450px;
    background-color: #FFF;
    border-radius: 20px;
    margin: 0 auto;
    margin-top: -200px;
    position: relative;
    top: 50%;
    z-index: 3;
    padding: 55px;
    text-align: center;">
            <div class="t1" style="color: rgba(239,78,78,0.74);margin-bottom: 10px;">{{__('delete')}} <span
                        id="put_name"> </span></div>
            <button id="close" class="btn save" style="background-color: #FFFFFF;
                    border: 2px solid #5eaff0; color: #000;height: 52px">{{__('cancel')}}
            </button>
            <button type="submit" onclick="deleteAdmin()" style="background-color:rgba(239,78,78,0.74);
                    border: 2px solid #5eaff0; color: #000;height: 52px" class="btn save">{{__('accept')}}
            </button>
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

        $('#role_id').on('change', function () {
            $('#form_id').submit()
        })


        function showModal(id) {
            $('#modal_id').show();
            $('#delete_product_id').val(id);
            $('#put_name').html($('#get_name_' + id).text());

        }

        $('#close').click(function () {
            $('#modal_id').hide();
        })

        function deleteAdmin() {
            let id = $('#delete_product_id').val();
            clearTimeout(time);
            time = setTimeout(function () {
                $.ajax({
                    type: 'post',
                    url: '/ajax/member/admins/delete',
                    data: {
                        id: id
                    },
                    beforeSend: function () {
                        $('#modal_id').hide();
                        $('#delete_product_id').val(null);
                        $('#put_name').html('');
                    },
                    success: function (data) {
                        window.location.href = $('#redirect_url').val();
                    }
                });
            }, 350);
        }

    </script>


@endsection

