@extends('admin.layouts.admin-layout')
@section('content')
    <div class="main_info">
        <div class="add">
            <form action="{{route('admin.section.store',app()->getLocale())}}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{$self_section->id}}">
                <div class="title">Добавить раздел</div>
                <div class="b1">
                    <div class="b4">
                        <div class="tabs-block">
                            <div class="tabs">
                                <div class="tab">O'zbekcha</div>
                                <div class="tab">На русском</div>

                            </div>
                            <div class="tabs-content">
                                <div class="tab-item">
                                    <div class="t1">Nomi</div>
                                    <input value="{{$self_section->name_uz}}" required name="name_uz" type="text"
                                           class="inp"
                                           placeholder="Nomi kiriting">
                                    <div class="t1">Qisqa / To'liq tavsif</div>
                                    <textarea required name="description_uz" class="inp textarea" rows="7"
                                              placeholder="Введите описание"> {{$self_section->description_uz}}</textarea>
                                </div>
                                <div class="tab-item">
                                    <div class="t1">Название</div>
                                    <input value="{{$self_section->name_ru}}" required name="name_ru" type="text"
                                           class="inp"
                                           placeholder="Введите название">
                                    <div class="t1">Краткое описание / Полное описание</div>
                                    <textarea required name="description_ru" class="inp textarea" rows="7"
                                              placeholder="Введите описание"> {{$self_section->description_ru}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="b2">
                        <div class="t2">{{__('active')}}</div>
                        <label class="iosCheck blue">
                            <input type="checkbox" name="is_active" {{$self_section->is_active ? 'checked' : '' }}>
                            <i></i>
                        </label>

                    </div>
                </div>
                <div class="btns_block">
                    <a class="btn back_b_list"
                       href="{{route('admin.section.index',app()->getLocale())}}">{{__('cancel')}}</a>
                    <button type="submit" class="btn">update</button>
                </div>
            </form>
        </div>
    </div>
@endsection
