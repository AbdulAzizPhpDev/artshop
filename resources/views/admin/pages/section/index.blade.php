@extends('admin.layouts.admin-layout')
@section('content')
    <div class="main_info">
        <div class="parts">
            <div class="title">{{__('header_info_center')}}</div>
            <div class="parts">
                <div class="table_title">
                    @error('video_error')
                    {{$message}}
                    @else
                        {{__('section_site_bar')}}
                        @enderror
                </div>
                <div class="filter_block">
                    <form action="{{route('admin.section.post.search',app()->getLocale())}}" method="post">
                        @csrf
                        <input type="text" class="search" placeholder="Поиск" name="search"
                               value="{{isset($search)?$search: ""}}">
                    </form>

                    <a class="btn" href="{{route('admin.section.add',app()->getLocale())}}">{{__('section')}}</a>

                </div>
                <div class="clear"></div>
                <div class="table_block">
                    <table>
                        <tr style="font-weight: bold">
                            <th class="">ID</th>
                            {{--                            <th class="row2">{{__('image')}}</th>--}}
                            <th class="row9">{{__('name')}}</th>
                            <th class="">{{__('active')}}</th>
                            <th class="">{{__('actions')}}</th>
                        </tr>
                        @if(count($sections)>0)
                            @foreach($sections as $section)
                                <input type="hidden" value="{{count($section->videos)}}"
                                       id="number_of_product_{{$section->id}}">
                                <tr>
                                    <th>{{$section->id}}</th>

                                    {{--                                    <th>--}}
                                    {{--                                        <img src="/assets/img/video.png">--}}
                                    {{--                                    </th>--}}

                                    <th>{{$section['name_'.app()->getLocale()]}}</th>
                                    <th class="check">
                                        <label class="iosCheck blue">
                                            <input id="update_status_{{$section->id}}"
                                                   onchange="updateStatus({{$section->id}},'section')"
                                                   type="checkbox" {{$section->is_active ? "checked" : " " }} ><i></i>
                                        </label>
                                    </th>

                                    <th>
                                        <div class="b1">
                                            <a href="{{route('admin.section.edit',['id'=>$section->id,'lang'=>app()->getLocale()])}}"
                                               class="view"></a>

                                            <div class="trash" onclick="showModal({{$section->id}})"></div>
                                        </div>
                                    </th>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <th colspan="5">
                                    Empty
                                </th>

                            </tr>
                        @endif

                    </table>
                    @component('admin.components.pagination',['pagination'=>$sections])
                    @endcomponent
                </div>
            </div>
        </div>

    </div>
    <input type="hidden" value="{{route('admin.section.index',app()->getLocale())}}" id="redirect_url">
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
            <div class="t1" style="color: rgba(239,78,78,0.74);margin-bottom: 10px;">{{__('delete_section')}}</div>
            <button id="close" class="btn save" style="background-color: #FFFFFF;
                    border: 2px solid #5eaff0; color: #000;height: 52px">{{__('cancel')}}
            </button>
            <button type="submit" onclick="deleteVedio()" style="background-color:rgba(239,78,78,0.74);
                    border: 2px solid #5eaff0; color: #000;height: 52px" class="btn save">{{__('accept')}}
            </button>
            <div class="t1" style="margin-bottom: 10px;"></div>
            <h3 style="margin-bottom: 10px;">если удалить раздел <span id="product_num" style="color: red">  </span>
                продукт будет уничтожен</h3>
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
            $('#product_num').html($('#number_of_product_' + id).val());

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
                    url: '/ajax/section/destroy',
                    data: {
                        id: id
                    },
                    beforeSend: function () {
                        $('#modal_id').hide();
                        $('#delete_product_id').val(null);
                        $('#product_num').val(null);
                    },
                    success: function (data) {
                        window.location.href = $('#redirect_url').val();
                    }
                });
            }, 350);
        }

    </script>


@endsection
