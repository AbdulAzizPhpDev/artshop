@extends('seller.layout.seller-layout')
@section('content')
    <div class="main_info seller_panel">
        <div class="title">{{__('comments')}}</div>
        <div class="reviews">
            <div class="table_title">{{auth()->user()->name}}</div>
            <div class="filter_block">
                <input type="date" placeholder="Выберите дату" class="pick_date">
            </div>
            <div class="clear"></div>
            <div class="table_block">
                <table>
                    <tbody>
                    <tr style="font-weight: bold">
                        <th class="row6">{{__('product_type_date')}}</th>
                        <th class="row2">{{__('image')}}</th>
                        <th class="row6">{{__('name')}}</th>
                        <th class="row6">{{__('product_type_comment')}}</th>

                    </tr>
                    @if($reviews)
                        @foreach($reviews as $review)
                            <tr>
                                <th>
                                    {{$review->created_at}}
                                </th>
                                <th>
                                    <img src="{{Storage::url($review->product->image)}}">

                                </th>
                                <th>
                                    <div class="pr">{{$review->product['name_'.app()->getLocale()]}}</div>
                                    <div class="pr">{{__('product_type_catalog')}}:<span> {{$review->product->category['name_'.app()->getLocale()]}}</span></div>

                                </th>
                                <th>
                                    {{$review->comment}}
                                </th>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection