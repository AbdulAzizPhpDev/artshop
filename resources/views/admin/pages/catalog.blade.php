@extends('admin.layouts.admin-layout')
@section('content')
    <div class="content">
        <div class="sidebar">
            <a href="index.php" class="cat">Панель инструментов</a>
            <div class="accordion">
                <div class="accordion_item">
                    <h3 class="title_block cat">Каталог</h3>
                    <div class="info">
                        <a href="products.php" class="subcat">Товары</a>
                        <a class="subcat">Категория</a>
                    </div>
                </div>
                <div class="accordion_item">
                    <h3 class="title_block cat">Пользователи</h3>
                    <div class="info">
                        <a class="subcat">Покупатели</a>
                        <a class="subcat">Продавцы</a>
                        <a class="subcat">Администраторы</a>
                    </div>
                </div>
                <div class="accordion_item">
                    <h3 class="title_block cat">Сообщения</h3>
                    <div class="info">
                        <daiv class="subcat">Обратная связь</daiv>
                    </div>
                </div>
                <div class="accordion_item">
                    <h3 class="title_block cat">Инфоцентр</h3>
                    <div class="info">
                        <a class="subcat">Видеоматериалы</a>
                        <a class="subcat">Разделы</a>
                    </div>
                </div>
                <div class="accordion_item">
                    <h3 class="title_block cat">Хранилище</h3>
                    <div class="info">
                        <a class="subcat">Архив покупателя</a>
                        <a class="subcat">Архив продавцов</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="main_info">
            <div class="category active">
                <div class="title">Каталог</div>

                <div class="table_title">Категории</div>
                <div class="filter_block">
                    <input type="text" class="search" placeholder="Поиск">
                    <div class="btn">Добавить категорию</div>

                </div>
                <div class="clear"></div>
                <div class="table_block">
                    <table>
                        <tr style="font-weight: bold">
                            <th class="row1">ID</th>
                            <th class="row2">Фото</th>
                            <th class="row9">Название</th>
                            <th class="row7">Активность</th>
                            <th class="row8">Действия</th>
                        </tr>
                        <tr>
                            <th>01</th>
                            <th>Фото</th>
                            <th>Волшебный чокер</th>
                            <th class="check"><label class="iosCheck blue"><input type="checkbox"><i></i></label></th>
                            <th>
                                <div class="b1">
                                    <div class="comp"></div>
                                    <div class="view" data-num="1"></div>
                                    <div class="trash"></div>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th>01</th>
                            <th>Фото</th>
                            <th>Волшебный чокер</th>
                            <th class="check"><label class="iosCheck blue"><input type="checkbox"><i></i></label></th>
                            <th>
                                <div class="b1">
                                    <div class="comp"></div>
                                    <div class="view" data-num="2"></div>
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
                <div class="cat_btn">Применить</div>
            </div>
            <div class="subcategory" id="subcategory1">
                <div class="title">Подкатегории</div>
                <div class="table_title">Украшения</div>
                <div class="table_block">
                    <table>
                        <tr style="font-weight: bold">
                            <th class="row1">ID</th>
                            <th class="row2">Фото</th>
                            <th class="row9">Название</th>
                            <th class="row7">Активность</th>
                            <th class="row8">Действия</th>
                        </tr>
                        <tr>
                            <th>01</th>
                            <th>Фото</th>
                            <th>Волшебный чокер</th>
                            <th class="check"><label class="iosCheck blue"><input type="checkbox"><i></i></label></th>
                            <th>
                                <div class="b1">
                                    <div class="comp"></div>
                                    <div class="view" data-num="1"></div>
                                    <div class="trash"></div>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th>01</th>
                            <th>Фото</th>
                            <th>Волшебный чокер</th>
                            <th class="check"><label class="iosCheck blue"><input type="checkbox"><i></i></label></th>
                            <th>
                                <div class="b1">
                                    <div class="comp"></div>
                                    <div class="view" data-num="2"></div>
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
                <div class="cat_btn" id="back">Вернуться</div>
            </div>

        </div>
    </div>

@endsection
