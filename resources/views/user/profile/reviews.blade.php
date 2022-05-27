@extends('admin.layouts.user-layout')
@section('content')
    <div class="main_info">
        <div class="title">{{__('comments')}}</div>
        <div class="reviews">
            <div class="filter_block">
                <form action="{{route('user.profile.reviews.post.search',app()->getLocale())}}" method="post">
                    @csrf
                    <input type="text" name="search" class="search" placeholder="{{__('search')}}"
                           value="{{isset($search) ? $search:"" }}">
                </form>
            </div>
            <div class="clear"></div>
            @if (count($products)>0)
                <div class="table_block">
                    <table>
                        <tbody>
                        <tr style="font-weight: bold">
                            <th class="row6">{{__('admin_regions_register_date')}}</th>
                            <th class="row2">{{__('image')}}</th>
                            <th class="row6">{{__('name')}}</th>
                            <th class="row6">{{__('comment')}}</th>

                        </tr>
                        @foreach($products  as $product)
                            <tr>
                                <th>
                                    {{$product->created_at}}
                                </th>
                                <th>
                                    <img src="{{Storage::url($product->product->image)}}">
                                </th>
                                <th>
                                    <div class="pr">{{$product->product['name_'.app()->getLocale()]}}</div>
                                    <div class="pr">{{__('catalog')}}
                                        :<span> {{$product->product->category['name_'.app()->getLocale()]}}</span>
                                    </div>

                                </th>
                                <th>
                                    {{$product->comment}}
                                </th>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @component('user.components.pagination',['pagination'=>$products])
                    @endcomponent
                </div>
            @endif

        </div>
    </div>
@endsection
