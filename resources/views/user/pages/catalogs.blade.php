@extends('user.layouts.page-layout')
@section('content')
    <div class="catalog">
        <div class="content">
            <div class="navi">
                <a href="" class="navi_point">Главная</a>
                <a href="" class="navi_point">Каталог</a>

            </div>
            <div class="page_title">Каталог</div>
            <div class="b5">
                <div class="filter">
                    <div class="t1">Фильтр</div>
                    <div class="filter_sect">
                        <div class="t2">Тип продаж</div>
                        <div class="check_cnt">
                            <input type="checkbox" id="roznica" value=""><label for="roznica">В розницу</label>
                        </div>
                        <div class="check_cnt">
                            <input type="checkbox" id="optom" value=""><label for="optom">Оптом</label>
                        </div>
                    </div>
                    <div class="filter_sect">
                        <div class="t2">Регион</div>
                        <div class="check_cnt">
                            <input type="checkbox" id="tashkent" value=""><label>Ташкент</label>
                        </div>
                        <div class="check_cnt">
                            <input type="checkbox" id="tashobl" value=""><label>Ташкентская область</label>
                        </div>
                        <div class="check_cnt">
                            <input type="checkbox" id="namobl" value=""><label>Наманганская область</label>
                        </div>
                        <div class="check_cnt">
                            <input type="checkbox" id="fergobl" value=""><label>Ферганская область</label>
                        </div>
                        <div class="check_cnt">
                            <input type="checkbox" id="andobl" value=""><label>Андижанская область</label>
                        </div>
                    </div>
                    <div class="filter_sect">
                        <div class="t2">Регион</div>
                        <div class="check_cnt">
                            <input type="checkbox" id="tashkent" value=""><label>Ташкент</label>
                        </div>
                        <div class="check_cnt">
                            <input type="checkbox" id="tashobl" value=""><label>Ташкентская область</label>
                        </div>
                        <div class="check_cnt">
                            <input type="checkbox" id="namobl" value=""><label>Наманганская область</label>
                        </div>
                        <div class="check_cnt">
                            <input type="checkbox" id="fergobl" value=""><label>Ферганская область</label>
                        </div>
                        <div class="check_cnt">
                            <input type="checkbox" id="andobl" value=""><label>Андижанская область</label>
                        </div>
                    </div>
                    <div class="filter_sect">
                        <div class="t2">Регион</div>
                        <div class="check_cnt">
                            <input type="checkbox" id="tashkent" value=""><label>Ташкент</label>
                        </div>
                        <div class="check_cnt">
                            <input type="checkbox" id="tashobl" value=""><label>Ташкентская область</label>
                        </div>
                        <div class="check_cnt">
                            <input type="checkbox" id="namobl" value=""><label>Наманганская область</label>
                        </div>
                        <div class="check_cnt">
                            <input type="checkbox" id="fergobl" value=""><label>Ферганская область</label>
                        </div>
                        <div class="check_cnt">
                            <input type="checkbox" id="andobl" value=""><label>Андижанская область</label>
                        </div>
                    </div>
                    <div class="filter_sect">
                        <div class="t2">Тип оплаты</div>
                        <div class="check_cnt">
                            <input type="checkbox" value=""><label>Наличные</label>
                        </div>
                        <div class="check_cnt">
                            <input type="checkbox" value=""><label>Банковская карта</label>
                        </div>
                        <div class="check_cnt">
                            <input type="checkbox" value=""><label>Payme</label>
                        </div>
                        <div class="check_cnt">
                            <input type="checkbox" value=""><label>Click</label>
                        </div>
                    </div>
                    <div class="filter_sect">
                        <div class="t2">Цена</div>
                        <div id="price-slider">
                            <div id="slider-range" class="ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content"><div class="ui-slider-range ui-corner-all ui-widget-header" style="left: 12%; width: 48%;"></div><span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="left: 12%;"></span><span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="left: 60%;"></span></div>
                            <div class="box">
                                <input type="text" id="amount-min" readonly="">
                                <input type="text" id="amount-max" readonly="">
                            </div>
                        </div>
                        <div class="btn">Применить</div>
                        <div class="btn clear">Сброс</div>

                    </div>
                </div>
                <div class="b6">
                    <div class="b4">
                        <div class="prod_cont_cat">
                            <div class="product">
                                <img src="/img/prod_img_test.png" class="product_img">
                                <div class="b2">
                                    <a href="/product_view.php" class="product_name">Женские украшения
                                        ручной работы</a>
                                    <div class="in_stock">В наличии:<span> 10 шт</span></div>
                                    <div class="in_stock">Цена:<span> 15 000/шт</span></div>
                                </div>
                                <div class="b3">
                                    <div class="action tcart"></div>
                                    <div class="action phone"></div>
                                    <div class="action wishlist"></div>
                                </div>
                            </div>
                        </div>
                        <div class="prod_cont_cat">
                            <div class="product">
                                <img src="/img/prod_img_test.png" class="product_img">
                                <div class="b2">
                                    <a href="/product_view.php" class="product_name">Женские украшения
                                        ручной работы</a>
                                    <div class="in_stock">В наличии:<span> 10 шт</span></div>
                                    <div class="in_stock">Цена:<span> 15 000/шт</span></div>
                                </div>
                                <div class="b3">
                                    <div class="action tcart"></div>
                                    <div class="action phone"></div>
                                    <div class="action wishlist"></div>
                                </div>
                            </div>
                        </div>
                        <div class="prod_cont_cat">
                            <div class="product">
                                <img src="/img/prod_img_test.png" class="product_img">
                                <div class="b2">
                                    <a href="/product_view.php" class="product_name">Женские украшения
                                        ручной работы</a>
                                    <div class="in_stock">В наличии:<span> 10 шт</span></div>
                                    <div class="in_stock">Цена:<span> 15 000/шт</span></div>
                                </div>
                                <div class="b3">
                                    <div class="action tcart"></div>
                                    <div class="action phone"></div>
                                    <div class="action wishlist"></div>
                                </div>
                            </div>
                        </div>
                        <div class="prod_cont_cat">
                            <div class="product">
                                <img src="/img/prod_img_test.png" class="product_img">
                                <div class="b2">
                                    <a href="/product_view.php" class="product_name">Женские украшения
                                        ручной работы</a>
                                    <div class="in_stock">В наличии:<span> 10 шт</span></div>
                                    <div class="in_stock">Цена:<span> 15 000/шт</span></div>
                                </div>
                                <div class="b3">
                                    <div class="action tcart"></div>
                                    <div class="action phone"></div>
                                    <div class="action wishlist"></div>
                                </div>
                            </div>
                        </div>
                        <div class="prod_cont_cat">
                            <div class="product">
                                <img src="/img/prod_img_test.png" class="product_img">
                                <div class="b2">
                                    <a href="/product_view.php" class="product_name">Женские украшения
                                        ручной работы</a>
                                    <div class="in_stock">В наличии:<span> 10 шт</span></div>
                                    <div class="in_stock">Цена:<span> 15 000/шт</span></div>
                                </div>
                                <div class="b3">
                                    <div class="action tcart"></div>
                                    <div class="action phone"></div>
                                    <div class="action wishlist"></div>
                                </div>
                            </div>
                        </div>

                    </div>
{{--                    <div class="pagination" style="margin-top: 40px">--}}
{{--                        <div class="left_arrow"></div>--}}
{{--                        <div class="page_number active">1</div>--}}
{{--                        <div class="page_number">2</div>--}}
{{--                        <div class="page_number">3</div>--}}
{{--                        <div class="page_number">4</div>--}}
{{--                        <div class="page_number">5</div>--}}
{{--                        <div class="page_number">6</div>--}}
{{--                        <div class="right_arrow"></div>--}}
{{--                    </div>--}}
                </div>

            </div>

        </div>
    </div>
{{--    <div class="catalog">--}}
{{--        <div class="content">--}}
{{--            <div class="navi">--}}
{{--                <a href="{{route('user.index')}}" class="navi_point">Главная</a>--}}
{{--                <a class="navi_point">Каталог</a>--}}
{{--            </div>--}}

{{--            <div class="page_title">Украшения</div>--}}
{{--            <div class="b5">--}}
{{--                <div class="filter">--}}
{{--                    <div class="t1">Фильтр</div>--}}
{{--                    <div class="filter_sect">--}}
{{--                        <div class="t2">Search by name</div>--}}

{{--                        <input type="text" style="width: 100%;--}}
{{--    height: 30px;--}}
{{--    padding: 1px;--}}
{{--    border: 2px solid #e2e2e2;--}}
{{--    border-radius: 5px;--}}
{{--    " name="search" placeholder="search by name">--}}


{{--                    </div>--}}
{{--                    <div class="filter_sect">--}}
{{--                        <div class="t2">Тип продаж</div>--}}
{{--                        <div class="check_cnt">--}}
{{--                            <input type="checkbox" id="roznica" value=""><label for="roznica">В розницу</label>--}}
{{--                        </div>--}}
{{--                        <div class="check_cnt">--}}
{{--                            <input type="checkbox" id="optom" value=""><label for="optom">Оптом</label>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    @if(!is_null($catalogs))--}}
{{--                        <div class="t1 ">Каталог</div>--}}
{{--                        @foreach($catalogs as $catalog)--}}
{{--                            @if(count($catalog->children)>0)--}}

{{--                                <div class="">--}}
{{--                                    <div class="t2">{{$catalog->name_uz}}</div>--}}
{{--                                    @foreach($catalog->children as $child)--}}
{{--                                        <div class="check_cnt">--}}
{{--                                            <input type="checkbox" id="{{$child->id}}"><label--}}
{{--                                                for="{{$child->id}}">{{$child->name_uz}}</label>--}}
{{--                                        </div>--}}
{{--                                    @endforeach--}}
{{--                                </div>--}}
{{--                            @endif--}}
{{--                        @endforeach--}}
{{--                    @endif--}}
{{--                    <div class="filter_sect">--}}
{{--                    </div>--}}
{{--                    <div class="filter_sect">--}}
{{--                        <div class="t2">Регион</div>--}}
{{--                        @if(count($regions)>0)--}}
{{--                            @foreach($regions as $region)--}}
{{--                                <div class="check_cnt">--}}
{{--                                    <input type="checkbox" id="{{$region->id}}"--}}
{{--                                           value=""><label>{{$region->name_uz}}</label>--}}
{{--                                </div>--}}
{{--                            @endforeach--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                    <div class="filter_sect">--}}
{{--                        <div class="t2">Тип оплаты</div>--}}
{{--                        <div class="check_cnt">--}}
{{--                            <input type="checkbox" value=""><label>Наличные</label>--}}
{{--                        </div>--}}
{{--                        <div class="check_cnt">--}}
{{--                            <input type="checkbox" value=""><label>Банковская карта</label>--}}
{{--                        </div>--}}
{{--                        <div class="check_cnt">--}}
{{--                            <input type="checkbox" value=""><label>Payme</label>--}}
{{--                        </div>--}}
{{--                        <div class="check_cnt">--}}
{{--                            <input type="checkbox" value=""><label>Click</label>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="filter_sect">--}}
{{--                        <div class="t2">Цена</div>--}}
{{--                        <div class="btn">Применить</div>--}}
{{--                        <div class="btn clear">Сброс</div>--}}

{{--                    </div>--}}

{{--                </div>--}}
{{--                <div class="b6">--}}
{{--                    @if(count($products)>0)--}}
{{--                        <div class="b4">--}}
{{--                            @foreach($products as $product)--}}
{{--                                @component('user.components.product_catalog',['product'=>$product])--}}
{{--                                @endcomponent--}}
{{--                            @endforeach--}}
{{--                        </div>--}}
{{--                        @component('admin.components.pagination',['pagination'=>$products])--}}
{{--                        @endcomponent--}}
{{--                    @endif--}}
{{--                </div>--}}

{{--            </div>--}}

{{--        </div>--}}
{{--    </div>--}}
@endsection
