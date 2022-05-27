@extends('admin.layouts.user-layout')
@section('content')
    <div class="main_info user_panel">
        <div class="title">Сообщения</div>

        <div class="messages">
            <div class="filter_block">
                <select class="select">
                    <option>Cтатус</option>
                </select>
                <select class="select">
                    <option>Ответ на письма</option>
                </select>

            </div>
            <div class="clear"></div>
            <div class="folder">
                В этой папке писем нет
            </div>
        </div>
    </div>
@endsection