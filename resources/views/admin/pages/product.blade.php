@extends('admin.layouts.admin-layout')
@section('content')
    <div class="main_info">
        <div class="title">Каталог</div>
        <div class="products">
            <div class="table_title">Товары</div>
            <div class="filter_block">
                <input type="text" class="search" placeholder="Поиск">
                <select class="select">
                    <option>Все категории</option>
                    <option>Украшения</option>
                    <option>Аксессуары</option>
                    <option>Куклы и игрушки</option>
                    <option>Картины и панно</option>
                </select>
                <select class="select">
                    <option>Продавцы</option>
                    <option>2</option>
                    <option>3</option>
                </select>
                <select class="select">
                    <option>Показать по 5</option>
                    <option>2</option>
                    <option>3</option>
                </select>
            </div>
            <div class="btn">Изменить товар</div>
            <div class="clear"></div>
            <div class="table_block">
                <table>
                    <tr style="font-weight: bold">
                        <th class="row1">ID</th>
                        <th class="row2">Фото</th>
                        <th class="row3">Название</th>
                        <th class="row4">Цена</th>
                        <th class="row5">Кол-во</th>
                        <th class="row6">Продавцы</th>
                        <th class="row7">Активность</th>
                        <th class="row8">Действия</th>
                    </tr>
                    <tr>
                        <th>01</th>
                        <th>Фото</th>
                        <th>Волшебный чокер</th>
                        <th>19 000 сум</th>
                        <th>20 шт</th>
                        <th>OOO TEST Company</th>
                        <th class="check"><label class="iosCheck blue"><input type="checkbox"><i></i></label></th>
                        <th>
                            <div class="b1">
                                <div class="fav"></div>
                                <div class="view"></div>
                                <div class="trash"></div>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th>01</th>
                        <th>Фото</th>
                        <th>Волшебный чокер</th>
                        <th>19 000 сум</th>
                        <th>20 шт</th>
                        <th>OOO TEST Company</th>
                        <th class="check"><label class="iosCheck blue"><input type="checkbox"><i></i></label></th>
                        <th>
                            <div class="b1">
                                <div class="fav"></div>
                                <div class="view"></div>
                                <div class="trash"></div>
                            </div>
                        </th>
                    </tr>
                </table>
                <div class="pagination">
                    <div class="left_arrow"></div>
                    <div class="page_number active">1</div>
                    <div class="page_number">2</div>
                    <div class="page_number">3</div>
                    <div class="page_number">4</div>
                    <div class="page_number">5</div>
                    <div class="page_number">6</div>
                    <div class="right_arrow"></div>
                </div>
            </div>
            <div class="confirm">Применить</div>
        </div>
    </div>
@endsection
