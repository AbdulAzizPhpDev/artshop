@extends('admin.layouts.admin-layout')
@section('content')
    <div class="main_info">
        <div class="subcategory active" id="subcategory1">
            <div class="title">{{__('admin_catalog_sub_catalog_title')}}</div>
            <div class="table_title">{{$catalog['name_'.app()->getLocale()]}}</div>
            @if(count($sub_catalogs)>0)
                <div class="table_block">
                    <table>
                        <tr style="font-weight: bold">
                            <th class="row1">ID</th>
                            <th class="row2">{{__('image')}}</th>
                            <th class="row9">{{__('name')}}</th>
                            <th class="row7">{{__('active')}}</th>
                            <th class="row8">{{__('actions')}}</th>
                        </tr>
                        @foreach($sub_catalogs as $sub_catalog)

                            <tr>
                                <th>{{$sub_catalog->id}}</th>
                                <th><img src="{{$sub_catalog->image ? Storage::url($sub_catalog->image): "/1.jpg" }}"
                                         alt="image"></th>
                                <th>{{$sub_catalog->name_uz}}</th>
                                <th class="check">
                                    <label class="iosCheck blue">
                                        <input type="checkbox" id="update_status_{{$sub_catalog->id}}"
                                               onchange="updateCatalogStatus({{$sub_catalog->id}})" {{$sub_catalog->is_active ? "checked" : ""}}>
                                        <i></i>
                                    </label>
                                </th>
                                <th>
                                    <div class="b1">
                                        <a class="view"
                                           href="{{route('admin.catalog.edit',['id'=>$sub_catalog->id,'lang'=>app()->getLocale()])}}"></a>
                                        {{--                                        <a class="view" data-num="1"></a>--}}
                                        <div class="trash"></div>
                                    </div>
                                </th>
                            </tr>
                        @endforeach
                    </table>
                    <div class="pagination">
                        {{--                                                                        @dd($sub_catalogs->previousPageUrl())--}}
                        @if( $sub_catalogs->previousPageUrl())
                            @if(!$sub_catalogs->onFirstPage())
                                <a href="{{$sub_catalogs->previousPageUrl()}}" class="left_arrow"></a>
                            @endif
                            @if($sub_catalogs->currentPage()>=3)
                                <a href="{{$sub_catalogs->getOptions()["path"]."?page=".(1)}}"
                                   style=" text-decoration: none!important;"
                                   class="page_number {{$sub_catalogs->currentPage() ==1 ? "active" :""}}">1</a>
                            @endif
                            @if($sub_catalogs->currentPage()<=2)
                                @for($i=0;$i<$sub_catalogs->lastPage(), $i<4;$i++)
                                    <a href="{{$sub_catalogs->getOptions()["path"]."?page=".($i+1)}}"
                                       style=" text-decoration: none!important;"
                                       class="page_number {{$sub_catalogs->currentPage() ==$i+1 ? "active" :""}}">{{$i+1}}</a>
                                @endfor
                            @elseif($sub_catalogs->currentPage()+2>$sub_catalogs->lastPage())
                                @foreach($sub_catalogs->getUrlRange($sub_catalogs->lastPage()-2,$sub_catalogs->lastPage()) as $index=>$page)
                                    <a href="{{$page}}"
                                       style=" text-decoration: none!important;"
                                       class="page_number {{$sub_catalogs->currentPage() ==$index ? "active" :""}}">{{$index}}</a>
                                @endforeach
                            @else
                                @foreach($sub_catalogs->getUrlRange($sub_catalogs->currentPage()-1,$sub_catalogs->currentPage()+1) as $index=>$page)
                                    <a href="{{$page}}"
                                       style=" text-decoration: none!important;"
                                       class="page_number {{$sub_catalogs->currentPage() ==$index ? "active" :""}}">{{$index}}</a>
                                @endforeach
                            @endif

                            @if($sub_catalogs->currentPage()<$sub_catalogs->lastPage()-1)
                                <a href="{{$sub_catalogs->getOptions()["path"]."?page=".$sub_catalogs->lastPage()}}"
                                   style=" text-decoration: none!important;"
                                   class="page_number {{$sub_catalogs->currentPage() ==$sub_catalogs->lastPage() ? "active" :""}}">{{$sub_catalogs->lastPage()}}</a>
                            @endif
                            @if($sub_catalogs->hasMorePages())
                                <a href="{{$sub_catalogs->nextPageUrl()}}" class="right_arrow"></a>
                            @endif
                        @endif
                    </div>
                </div>
            @endif

            <a class="cat_btn" style="background-color: #FFFFFF; border: 2px solid #5eaff0;
    color: #000;display: block;text-decoration: none"
               href="{{route('admin.catalog.index',['lang'=>app()->getLocale()])}}"
               id="back">{{__('back')}}</a>
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