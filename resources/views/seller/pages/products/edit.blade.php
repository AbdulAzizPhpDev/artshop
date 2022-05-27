@extends('seller.layout.seller-layout')
@section('content')
    <div class="main_info">
        <div class="add product_e">
            <form action="{{route('seller.product.update',app()->getLocale())}}" method="post"
                  enctype="multipart/form-data"
                  autocomplete="off">
                @csrf
                <input type="hidden" name="redirect_url" value="{{$redirect_url}}">
                <input type="hidden" name="id" value="{{$product->id}}">
                <div class="title">    {{__('products')}}</div>
                <div class="table_title">{{__('edit_product')}}</div>
                <div class="b1">
                    <div class="b4">
                        <input type="hidden" id="tab_status"
                               value="{{$errors->has('name_uz')? 0: ($errors->has('name_ru')? 1:0 ) }}">
                        <div class="tabs-block">
                            <div class="tabs">

                                <div class="tab {{$errors->has('name_uz') ? "active":""}}">O'zbekcha</div>
                                <div
                                        class="tab {{!$errors->has('name_uz') ? ($errors->has('name_uz') ? "active":"" ) :""}}">
                                    На русском
                                </div>
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
                                    <input name="name_uz" type="text" class="inp" placeholder="nomini kiriting"
                                           required value="{{$product->name_uz}}">
                                    <div class="t1">Qisqa / To'liq tavsif</div>
                                    <textarea name="description_uz" class="inp textarea"
                                              rows="7" required
                                              placeholder="izohni kiriting">{{$product->description_uz}}</textarea>
                                </div>
                                <div class="tab-item">
                                    <div class="t1">@error('name_ru')
                                        {{$message}}
                                        @else
                                            Название
                                            @enderror</div>
                                    <input name="name_ru" type="text" class="inp"
                                           placeholder="Введите название"
                                           required value="{{$product->name_ru}}">
                                    <div class="t1">Краткое описание / Полное описание</div>
                                    <textarea name="description_ru" class="inp textarea"
                                              rows="7" required
                                              placeholder="Введите описание">{{$product->description_ru}}
                                    </textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="b2">
                        <div class="t2">{{__('active')}}</div>
                        <label class="iosCheck blue">
                            <input type="checkbox" name="is_active" {{$product->is_active ? "checked":""}}><i></i>
                        </label>
                        {{--          Image Image Image ImageImage--}}
                        <div class="b3">
                            <div class="t3">
                                @error('image')
                                {{$message}}
                                @else
                                    {{__('image_current')}}
                                    @enderror</div>
                            <div class="t4" id="file_warning">
                                {{__('image_format')}}
                            </div>
                            <div class="input__wrapper">
                                <input name="image" type="file" id="input__file" class="input input__file">
                                <label for="input__file" class="input__file-button">{{__('update')}} </label>
                            </div>
                            <div class="prev_block">
                                <img id="image" class="t3"
                                     src="{{Storage::url($product->image)}}"
                                     style="width: 100px;height: 100px">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="b5">
                    <div class="b6">
                        <div class="b7">

                            <div class="inp_label">
                                {{__('owner_product')}}
                            </div>
                            <input type="text" name="maker_name" class="inp2" value="{{$product->maker_name}}">

                            <div class="inp_label">
                                @error('catalog')
                                {{$message}}
                                @else
                                    {{__('catalog')}}
                                    @enderror
                            </div>
                            <select name="catalog" class="inp2 select2" id="catalog_id">
                                <option value="null">  {{__('catalog')}}</option>
                                @if($catalogs!=null)
                                    @foreach($catalogs as $catalog)
                                        @if($product->category->parent_id==$catalog->id)
                                            <option value="{{$catalog->id}}"
                                                    selected>{{$catalog['name_'.app()->getLocale()]}}</option>
                                        @else
                                            <option value="{{$catalog->id}}">{{$catalog['name_'.app()->getLocale()]}}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                            <div class="inp_label">{{__('product_price')}}</div>
                            <input id="stir_id" type="text" maxlength="12" class="inp2" value="{{$product->price}}">
                            <input type="hidden" id="input_stir_id" name="price" value="{{$product->price}}">

                            <div class="inp_label"
                                 style="font-size: 16px; font-weight: bold;  color: #3e444a; margin-bottom: 20px;">
                                {{__('In_stock_title')}}
                            </div>
                            <input {{$product->is_cash ? "checked" : ""}}  type="radio" name="cash" id="nalichie"
                                   value="1">
                            <label class="text_label" for="nalichie">{{__('In_stock')}}</label>
                            <input {{!$product->is_cash ? "checked" : ""}} type="radio" name="cash" id="netnalichie"
                                   value="2">
                            <label class="text_label" for="netnalichie">{{__('not_In_stock')}}</label>

                        </div>
                        <div class="b7">
                            <div class="inp_label">
                                {{__('owner_phone')}}
                            </div>
                            <input type="text" class="inp2" maxlength="17" id="phone-number"
                                   placeholder="+998-xx-xxx-xx-xx" name="maker_phone_format"
                                   value="{{ $maker_phone}}">
                            <input type="hidden" name="maker_phone" id="input_phone_id"
                                   value="{{  $product->maker_phone}}">


                            <div class="inp_label">{{__('admin_catalog_sub_catalog_title')}}</div>
                            <select class="inp2 select2" style="margin-bottom: 14px!important;" name="category" id="category_id" required>
                                {{__('admin_catalog_sub_catalog_title')}}
                                <option value="null"> {{__('admin_catalog_sub_catalog_title')}}</option>
                                @if($subcatalogs!=null)
                                    @foreach($subcatalogs as $catalog)
                                        @if($product->category->id==$catalog->id)
                                            <option value="{{$catalog->id}}" selected>{{$catalog['name_'.app()->getLocale()]}}</option>
                                        @else
                                            <option value="{{$catalog->id}}">{{$catalog['name_'.app()->getLocale()]}}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>

                            <input type="hidden" value="{{$product->quantity}}" id="quantity_val">
                            <div id="price_id">

                                @if ($product->is_cash)
                                    <div style="margin-top: 20px;" class="inp_label">{{__('seller_d_product_number')}}</div>
                                    <input id="stir_id" type="number" class="inp2" name="quantity" required
                                           value="{{$product->quantity}}">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div style="display: flex;
    justify-content: space-evenly;
    align-content: center;">
                    <a style="display: block;margin-left: inherit; text-decoration: none" class="btn"
                       href="{{$redirect_url}}">{{__('cancel')}}</a>

                    <button type="submit" style="display: block" class="btn save">{{__('save')}}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $("#input__file").on('change', function (event) {
            var image = document.getElementById('image');
            image.src = URL.createObjectURL(event.target.files[0]);
            $('#file_warning').html('{{__("image_uploaded")}}')
        })

        function checkFile() {
            if (!$('#input__file')[0].files.length) {
                $('#file_warning').html('{{__("image_uploaded")}}')
            }
        }

        $('input[name=cash]').on('change', function (event) {
            if (event.target.value == 2) {
                $('#price_id').html(" ")
            } else {
                let quantity = $('#quantity_val').val()
                let text = ' <div style="margin-top: 20px;" class="inp_label">{{__("seller_d_product_number")}}</div>' +
                    '<input id="stir_id" type="number"  class="inp2" name="quantity" value="' + quantity + '" required>'
                $('#price_id').html(text)
            }
        })


    </script>


@endsection
