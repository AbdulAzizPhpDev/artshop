@extends('admin.layouts.admin-layout')
@section('content')
    <div class="main_info">
        <div class="category active">
            <div class="title">{{__('catalog')}}</div>

            <div class="table_title">{{__('admin_catalog_categories')}}</div>
            <div class="filter_block">
                <form action="{{route('admin.catalog.post.search',['lang'=>app()->getLocale()])}}" method="post">
                    @csrf
                    <input name="search" type="text" class="search" placeholder="{{__('search')}}"
                           value="{{isset($search) ? $search: ''}}">
                </form>
                <a class="btn" href="{{route('admin.catalog.create',['lang'=>app()->getLocale()])}}">
                    {{__('admin_catalog_add')}}
                </a>

            </div>
            @if(count($catalogs)>0)
                <div class="clear"></div>
                <div class="table_block">
                    <table>
                        <tr style="font-weight: bold">
                            <th class="row1">ID</th>
                            <th class="row2">{{__('image')}}</th>
                            <th class="row9">{{__('name')}}</th>
                            <th class="row7">{{__('active')}}</th>
                            <th class="row8">{{__('actions')}}</th>
                        </tr>
                        @foreach($catalogs as $catalog)
                            <tr>
                                <th>{{$catalog->id}}</th>
                                <th><img src="{{$catalog->image ? Storage::url($catalog->image): "/1.jpg" }}"
                                         alt="image" height="100"
                                         width="100"></th>

                                <th>{{$catalog['name_'.app()->getLocale()]}}</th>
                                <th class="check">
                                    <label class="iosCheck blue">
                                        <input type="checkbox" id="update_status_{{$catalog->id}}"
                                               onchange="updateStatus({{$catalog->id}},'catalog')" {{$catalog->is_active ? "checked" : ""}}>
                                        <i></i>
                                    </label>
                                </th>
                                <th>
                                    <div class="b1">
                                        <a class="comp"
                                           href="{{route('admin.catalog.sub_catalog',['id'=>$catalog->id,'lang'=>app()->getLocale()])}}"></a>
                                        <a class="view"
                                           href="{{route('admin.catalog.edit',['id'=>$catalog->id,'lang'=>app()->getLocale()])}}"
                                        ></a>
                                        <div class="trash"></div>
                                    </div>
                                </th>
                            </tr>
                        @endforeach

                    </table>
                    <div class="pagination">

                        @if( $catalogs->lastPage()>1)
                            @if(!$catalogs->onFirstPage())
                                <a href="{{$catalogs->previousPageUrl()}}" class="left_arrow"></a>
                            @endif
                            @if($catalogs->currentPage()>=3)

                                <a href="{{$catalogs->getOptions()["path"]."?page=".(1)}}"
                                   style=" text-decoration: none!important;"
                                   class="page_number {{$catalogs->currentPage() ==1 ? "active" :""}}">1</a>
                            @endif
                            @if($catalogs->currentPage()<=2)

                                @for($i=0;$i<$catalogs->lastPage(), $i<4;$i++)
                                    <a href="{{$catalogs->getOptions()["path"]."?page=".($i+1)}}"
                                       style=" text-decoration: none!important;"
                                       class="page_number {{$catalogs->currentPage() ==$i+1 ? "active" :""}}">{{$i+1}}</a>
                                @endfor
                            @elseif($catalogs->currentPage()+2>$catalogs->lastPage())
                                @foreach($catalogs->getUrlRange($catalogs->lastPage()-2,$catalogs->lastPage()) as $index=>$page)
                                    <a href="{{$page}}"
                                       style=" text-decoration: none!important;"
                                       class="page_number {{$catalogs->currentPage() ==$index ? "active" :""}}">{{$index}}</a>
                                @endforeach
                            @else
                                @foreach($catalogs->getUrlRange($catalogs->currentPage()-1,$catalogs->currentPage()+1) as $index=>$page)
                                    <a href="{{$page}}"
                                       style=" text-decoration: none!important;"
                                       class="page_number {{$catalogs->currentPage() ==$index ? "active" :""}}">{{$index}}</a>
                                @endforeach
                            @endif

                            @if($catalogs->currentPage()<$catalogs->lastPage()-1)
                                <a href="{{$catalogs->getOptions()["path"]."?page=".$catalogs->lastPage()}}"
                                   style=" text-decoration: none!important;"
                                   class="page_number {{$catalogs->currentPage() ==$catalogs->lastPage() ? "active" :""}}">{{$catalogs->lastPage()}}</a>
                            @endif
                            @if($catalogs->hasMorePages())
                                <a href="{{$catalogs->nextPageUrl()}}" class="right_arrow"></a>
                            @endif
                        @endif
                    </div>
                </div>

            @endif
        </div>
        @error('success')

        <input type="hidden" id="check_success_1" value="1">
        @enderror
    </div>


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