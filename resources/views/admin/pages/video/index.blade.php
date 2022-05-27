@extends('admin.layouts.admin-layout')
@section('content')
    <div class="main_info">
        <div class="video">
            <div class="title">{{__('header_info_center')}}</div>
            <div class="video">
                <div class="table_title">{{__('video_site_bar')}}</div>
                <div class="filter_block">
                    <form action="{{route('admin.video.post.search',app()->getLocale())}}" method="post">
                        @csrf
                        <input type="text" class="search" placeholder="{{__('search')}}" name="search"
                               value="{{isset($search)?$search: ""}}">
                    </form>

                    <a class="btn" href="{{route('admin.video.add',app()->getLocale())}}">{{__('add_video')}}</a>

                </div>
                <div class="clear"></div>
                <div class="table_block">
                    <table>
                        <tr style="font-weight: bold">
                            <th class="">ID</th>
                            <th class="row2">{{__('video')}}</th>
                            <th class="">{{__('name')}}</th>
                            <th class="row_min1">{{__('desc')}}</th>
                            <th class="">{{__('section')}}</th>
                            <th class="">{{__('active')}}</th>
                            <th class="">{{__('actions')}}</th>
                        </tr>
                        @if(count($videos)>0)
                            @foreach($videos as $video)
                                <tr>
                                    <th>{{$video->id}}</th>
                                    <th>
                                        <img src="/assets/img/video.png">
                                    </th>
                                    <th>{{$video['name_'.app()->getLocale()]}}</th>
                                    <th>
                                        {{$video['description_'.app()->getLocale()]}}
                                    </th>
                                    <th>{{$video->getSection['name_'.app()->getLocale()]}}</th>
                                    <th class="check">
                                        <label class="iosCheck blue"><input
                                                    id="update_status_{{$video->id}}"
                                                    onchange="updateStatus({{$video->id}},'video')"
                                                    type="checkbox" {{$video->is_active ? "checked" : " " }}>
                                            <i></i>
                                        </label>
                                    </th>

                                    <th>
                                        <div class="b1">
                                            <a href="{{route('admin.video.edit',['id'=>$video->id,'lang'=>app()->getLocale()])}}"
                                               class="view"></a>

                                            <div class="trash" onclick="showModal({{$video->id}})"></div>
                                        </div>
                                    </th>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                    @component('admin.components.pagination',['pagination'=>$videos])
                    @endcomponent
                </div>
            </div>
        </div>

    </div>
    <input type="hidden" value="{{route('admin.video.index',app()->getLocale())}}" id="redirect_url">
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
            <div class="t1" style="color: rgba(239,78,78,0.74);margin-bottom: 10px;">Удалить video</div>
            <button id="close" class="btn save" style="background-color: #FFFFFF;
                    border: 2px solid #5eaff0; color: #000;height: 52px">Отмена
            </button>
            <button type="submit" onclick="deleteVedio()" style="background-color:rgba(239,78,78,0.74);
                    border: 2px solid #5eaff0; color: #000;height: 52px" class="btn save">Принимать
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
        function showModal(id) {
            $('#modal_id').show();
            $('#delete_product_id').val(id);

        }

        $('#close').click(function () {
            $('#modal_id').hide();
        })

        function deleteVedio() {
            let id = $('#delete_product_id').val();
            clearTimeout(time);
            time = setTimeout(function () {
                $.ajax({
                    type: 'post',
                    url: '/admin/video/destroy',
                    data: {
                        id: id
                    },
                    beforeSend: function () {
                        $('#modal_id').hide();
                        $('#delete_product_id').val(null);
                    },
                    success: function (data) {
                        window.location.href = $('#redirect_url').val();
                    }
                });
            }, 350);
        }

    </script>


@endsection
