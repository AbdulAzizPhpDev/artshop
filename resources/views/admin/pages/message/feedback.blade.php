@extends('admin.layouts.admin-layout')
@section('content')
    <div class="main_info">
        <div class="buyers_list active">
            <div class="title">Сообщения</div>
            <div class="feedback">
                <div class="table_title">Обратная связь</div>
                <div class="filter_block">
                    <select class="select">
                        <option>Сообщения</option>
                    </select>
                    <input type="text" class="search" placeholder="Поиск сообщения">

                </div>
                <div class="clear"></div>
                <div class="table_block">
                    <table>
                        <tbody>
                        <tr style="font-weight: bold">
                            <th class="row9">Заказ</th>
                            <th>Действие</th>
                        </tr>
                        <tr>
                            <th>
                                <div class="pr">Волшебный чокер</div>
                                <div class="pr">Количество: <span>5</span></div>
                                <div class="pr">Фио: <span>Анвар Умаров</span></div>
                                <div class="pr">Номер телефона: <span>+998 90 333 33 33</span></div>
                                <div class="pr">Отправлено: <span>04.06.2021</span></div>
                            </th>
                            <th>
                                <div class="b1">
                                    <div class="btn_block">Обработать</div>
                                    <div class="trash"></div>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <div class="pr">Волшебный чокер</div>
                                <div class="pr">Количество: <span>5</span></div>
                                <div class="pr">Фио: <span>Анвар Умаров</span></div>
                                <div class="pr">Номер телефона: <span>+998 90 333 33 33</span></div>
                                <div class="pr">Отправлено: <span>04.06.2021</span></div>
                            </th>
                            <th>
                                <div class="b1">
                                    <div class="btn_block">Обработать</div>
                                    <div class="trash"></div>
                                </div>
                            </th>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="cat_btn">Применить</div>

            </div>
        </div>

    </div>
@endsection