@extends('admin.layouts.admin-layout')
@section('content')
    <div class="main_info">
        <div class="sellers">
            <div class="title">{{$region['name_'.app()->getLocale()]}}</div>
            <div class="table_title">{{__('admin_regions_create_districts_title')}}</div>
            <div class="filter_block">

                <a class="btn"
                   href="{{route('admin.region.district.create',['region_id'=>$region_id,'lang' => app()->getLocale()])}}">
                    {{__('admin_regions_add_district')}}
                </a>

            </div>
            <div class="clear"></div>
            <div class="table_block">
                <table>
                    <tbody>
                    <tr style="font-weight: bold">
                        <th class="row_min1">{{__('admin_regions_create_district_title')}}</th>
                        <th>{{__('admin_region_creat_title')}}</th>
                        <th class="">{{__('admin_regions_register_date')}}</th>
                        <th class="">{{__('actions')}}</th>
                    </tr>
                    @if(count($districts)>0)
                        @foreach($districts as $district)
                            <tr id="table_row_id">
                                <th>{{$district['name_'.app()->getLocale()]}}</th>
                                <th>{{$region['name_'.app()->getLocale()]}}</th>
                                <th>{{$district->created_at}}</th>

                                <th>
                                    <div class="b1">

                                        <a class="view"
                                           href="{{route('admin.region.update',['id'=>$district->id,'lang' => app()->getLocale()])}}"></a>
                                        <div onclick="showModal({{$district->id}})" class="trash"></div>
                                    </div>
                                </th>
                            </tr>
                        @endforeach
                    @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="popup" id="modal_id" style="display:none;">
        <input type="hidden" value="null" id="delete_product_id">
        <input type="hidden" value="{{route('admin.region.index',app()->getLocale())}}" id="redirect_url">

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
            <div class="t1"
                 style="color: rgba(239,78,78,0.74);margin-bottom: 10px;">{{__('delete_section')}}</div>
            <button id="close" class="btn save" style="background-color: #FFFFFF;
                    border: 2px solid #5eaff0; color: #000;height: 52px">{{__('cancel')}}
            </button>
            <button type="submit" onclick="deleteCatalog()" style="background-color:rgba(239,78,78,0.74);
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

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function showModal(id) {
            $('#modal_id').show();
            $('#delete_product_id').val(id);

        }

        $('#close').click(function () {
            $('#modal_id').hide();
        })

        function deleteCatalog() {
            clearTimeout(time);
            time = setTimeout(function () {
                $.ajax({
                    type: 'POST',
                    url: '/ajax/delete/catalog',
                    data: {
                        id: $('#delete_product_id').val()
                    },
                    beforeSend: function () {
                        $('#modal_id').hide();

                    },
                    success: function (data) {
                        location.reload()
                    }
                });
            }, 350);
        }
    </script>

@endsection

