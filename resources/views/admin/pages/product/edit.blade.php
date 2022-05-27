@extends('admin.layouts.admin-layout')
@section('content')
    <div class="main_info">
        <div class="add product_e">
            <input disabled type="hidden" name="id" value="{{$product->id}}">
            <div class="title">Каталог</div>
            <div class="table_title">Редактировать товар</div>
            <div class="b1">
                <div class="b4">
                    <input disabled type="hidden" id="tab_status"
                           value="{{$errors->has('name_uz')? 0: ($errors->has('name_ru')? 1:0 ) }}">
                    <div class="tabs-block">
                        <div class="tabs">

                            <div class="tab">Ўзбек тилида</div>
                            <div class="tab"> На русском</div>
                        </div>
                        <div class="tabs-content">
                            <div class="tab-item">
                                <div class="t1">
                                    @error('name_uz')
                                    {{$message}}
                                    @else
                                        Nomi
                                        @enderror
                                </div>
                                <input disabled name="name_uz" type="text" class="inp" placeholder="Введите название"
                                       required value="{{$product->name_uz}}">
                                <div class="t1">Краткое описание / Полное описание</div>
                                <textarea disabled name="description_uz" class="inp textarea"
                                          rows="7" required
                                          placeholder="izohni kiriting">{{$product->description_uz}}</textarea>
                            </div>
                            <div class="tab-item">
                                <div class="t1">@error('name_ru')
                                    {{$message}}
                                    @else
                                        Название
                                        @enderror</div>
                                <input disabled name="name_ru" type="text" class="inp"
                                       placeholder="Введите название"
                                       required value="{{$product->name_ru}}">
                                <div class="t1">Краткое описание / Полное описание</div>
                                <textarea disabled name="description_ru" class="inp textarea"
                                          rows="7" required
                                          placeholder="Введите описание">{{$product->description_ru}}
                                    </textarea>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="b2">
                    <div class="t2">Активность</div>
                    <label class="iosCheck blue">
                        <input disabled type="checkbox" name="is_active" {{$product->is_active ? "checked":""}}><i></i>
                    </label>
                    <div class="t2">Popular</div>
                    <label class="iosCheck blue">
                        <input disabled type="checkbox" name="is_active" {{$product->is_popular ? "checked":""}}><i></i>
                    </label>
                    <div class="t2">New</div>
                    <label class="iosCheck blue">
                        <input disabled type="checkbox" name="is_active" {{$product->is_new ? "checked":""}}><i></i>
                    </label>
                    {{--          Image Image Image ImageImage--}}
                    <div class="b3">
                        <div class="t3">
                            Current photo
                        </div>

                        <div class="prev_block">
                            <img id="image" class="t3"
                                 src="{{Storage::url($product->image)}}"
                                 style="width: 100%;height: 100%">
                        </div>
                    </div>

                </div>
            </div>

            <div class="b5">
                <div class="b6">
                    <div class="b7">
                        <div class="inp_label">
                            @error('catalog')
                            {{$message}}
                            @else
                                Категория
                                @enderror
                        </div>
                        <select disabled name="catalog" class="inp2 select2" id="catalog_id">
                            <option>{{$product->category->parent->name_ru}}</option>
                            {{--                                @if($catalogs!=null)--}}
                            {{--                                    @foreach($catalogs as $catalog)--}}
                            {{--                                        @if($product->category->parent_id==$catalog->id)--}}
                            {{--                                            <option value="{{$catalog->id}}" selected>{{$catalog->name_uz}}</option>--}}
                            {{--                                        @else--}}
                            {{--                                            <option value="{{$catalog->id}}">{{$catalog->name_uz}}</option>--}}
                            {{--                                        @endif--}}
                            {{--                                    @endforeach--}}
                            {{--                                @endif--}}
                        </select>
                        <div class="inp_label">Цена товара</div>
                        <input disabled id="stir_id" type="text" maxlength="12" class="inp2"
                               value="{{$product->price}}">
                        <input disabled type="hidden" id="input_stir_id" name="price" value="{{$product->price}}">
                        <div class="inp_label">количество продукта</div>
                        <input disabled id="stir_id" type="number" max="100000000" min="1"
                               value="{{$product->quantity}}"
                               class="inp2"
                               name="quantity">
                        <div class="inp_label" style="margin-bottom: 23px">Наличие товара</div>
                        <input disabled type="radio" name="cash" id="nalichie"
                               {{$product->is_cash ? "checked" : " "}} value="1">
                        <label class="text_label" for="nalichie">В наличии</label>
                        <input disabled type="radio" name="cash" id="netnalichie"
                               {{!$product->is_cash ? "checked" : " "}} value="2">
                        <label class="text_label" for="netnalichie">Нет в наличии</label>
                    </div>
                    <div class="b7">
                        <div class="inp_label">SubКатегория</div>
                        <select disabled class="inp2 select2" name="category" id="category_id" required>subcatalogs
                            <option value="null"> {{$product->category->name_ru}}</option>
                            {{--                                @if($subcatalogs!=null)--}}
                            {{--                                    @foreach($subcatalogs as $catalog)--}}
                            {{--                                        @if($product->category->id==$catalog->id)--}}
                            {{--                                            <option value="{{$catalog->id}}" selected>{{$catalog->name_uz}}</option>--}}
                            {{--                                        @else--}}
                            {{--                                            <option value="{{$catalog->id}}">{{$catalog->name_uz}}</option>--}}
                            {{--                                        @endif--}}
                            {{--                                    @endforeach--}}
                            {{--                                @endif--}}
                        </select>
                        <div class="inp_label">Производитель</div>
                        <input disabled type="text" class="inp2" name="made_in" maxlength="32"
                               value="{{$product->made_in}}">


                    </div>
                </div>
                <div class="inp_label" style="margin-bottom: 23px">Тип продаж</div>
                <input disabled {{$product->selling_type=='retail' ? "checked" : " "}} type="radio" name="sell_type"
                       id="roznica" value="retail">
                <label class="text_label" for="roznica">Только в розницу</label>
                <input disabled {{$product->selling_type=='wholesale' ? "checked" : " "}} type="radio" name="sell_type"
                       id="optom" value="wholesale">
                <label class="text_label" for="optom">Только оптом</label>
                <input disabled {{$product->selling_type=='both' ? "checked" : " "}} type="radio" name="sell_type"
                       id="optomrozn" value="both">
                <label class="text_label" for="optomrozn">Оптом и в розницу</label>
                <div id="payment_id">
{{--                    @if(!$product->paymentType->cash)--}}
{{--                        <div class="inp_label" style="margin: 33px 0px 23px 0px">Способ оплаты</div>--}}

{{--                        <input disabled {{$product->paymentType->click ? "checked" : " "}} type="checkbox" id="payme"--}}
{{--                               name="payme" value="1">--}}
{{--                        <label class="text_label" for="payme">Оплата через Payme</label>--}}

{{--                        <input disabled {{$product->paymentType->bank_account ? "checked" : " "}} type="checkbox"--}}
{{--                               id="bank"--}}
{{--                               name="bank" value="1">--}}
{{--                        <label class="text_label" for="bank">Банковская оплата</label>--}}
{{--                    @endif--}}
                </div>

            </div>
            <div class="btns_block" style="display: table-row;">
                <a style="display: block;" class="btn back_b_list"
                   href="{{route('admin.product.index',app()->getLocale())}}">{{__('back')}}</a>


            </div>

        </div>
    </div>

@endsection