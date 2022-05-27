@extends('seller.layout.seller-layout')
@section('content')
    <div class="main_info">
        <div class="add product_e">
            <form action="{{route('seller.product.store',app()->getLocale())}}" method="post" enctype="multipart/form-data"
                  autocomplete="off">
                @csrf

                <div class="title">    {{__('products')}}</div>
                <div class="table_title">{{__('add_product_2')}}</div>
                @if($errors->any())
                    {{ implode('', $errors->all(':message')) }}
                @endif

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
                                           required value="{{old('name_uz')}}">
                                    <div class="t1">Qisqa / To'liq tavsif</div>
                                    <textarea name="description_uz" class="inp textarea"
                                              rows="7" required
                                              placeholder="izohni kiriting">{{old('description_uz')}}</textarea>
                                </div>
                                <div class="tab-item">
                                    <div class="t1">@error('name_ru')
                                        {{$message}}
                                        @else
                                            Название
                                            @enderror</div>
                                    <input name="name_ru" type="text" class="inp"
                                           placeholder="Введите название"
                                           required value="{{old('name_ru')}}">
                                    <div class="t1">Краткое описание / Полное описание</div>
                                    <textarea name="description_ru" class="inp textarea"
                                              rows="7" required
                                              placeholder="Введите описание">{{old('description_ru')}}
                                    </textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="b2">
                        {{--          Image Image Image ImageImage--}}
                        <div class="b3">
                            <div class="t2">{{__('active')}}</div>
                            <label class="iosCheck blue">
                                <input type="checkbox" name="is_active" checked><i></i>
                            </label>
                            <div class="t3">
                                @error('image')
                                {{$message}}
                                @else
                                {{__('image_upload')}}
                                    @enderror</div>
                            <div class="t4" id="file_warning">
                                {{__('admin_users_seller_image_format')}}
                            </div>
                            <div class="input__wrapper">
                                <input name="image" type="file" id="input__file" class="input input__file">
                                <label for="input__file" class="input__file-button">{{__('upload')}}</label>
                            </div>
                            <div class="prev_block">
                                <img id="image" class="t3"
                                     src="{{'/1.jpg'}}"
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
                            <input type="text" name="maker_name" class="inp2" value="{{old('maker_name')}}">

                            <div class="inp_label">
                                @error('catalog')
                                {{$message}}
                                @else
                                {{__('catalog')}}
                                    @enderror
                            </div>
                            <select style="width: 425px;
    height: 40px;" name="catalog" class="inp2 select2" id="catalog_id">
                                <option value="null">{{__('catalog')}}</option>
                                @if($catalogs!=null)
                                    @foreach($catalogs as $catalog)
                                        <option value="{{$catalog->id}}">{{$catalog['name_'.app()->getLocale()]}}</option>
                                    @endforeach
                                @endif
                            </select>

                            <div class="inp_label">{{__('product_price')}}</div>
                            <input id="stir_id" type="text" maxlength="12" class="inp2" >
                            <input type="hidden" id="input_stir_id" name="price" >


                            <div class="inp_label"
                                 style="font-size: 16px;font-weight: bold;color: #3e444a;
                                 margin-bottom: 20px;">
                                {{__('In_stock_title')}}
                            </div>
                            <input type="radio" name="cash" id="nalichie" checked value="1">
                            <label class="text_label" for="nalichie">{{__('In_stock')}}</label>
                            <input type="radio" name="cash" id="netnalichie" value="2">
                            <label class="text_label" for="netnalichie">{{__('not_In_stock')}}</label>

                        </div>


                        <div class="b7">

                            <div class="inp_label">
                                {{__('owner_phone')}}
                            </div>
                            <input type="text" class="inp2" maxlength="17" id="phone-number"
                                   placeholder="+998-xx-xxx-xx-xx" name="maker_phone_format"
                                   value="{{ isset($maker_phone)? $maker_phone: ""}}">
                            <input type="hidden" name="maker_phone" id="input_phone_id"
                                   value="{{  old('extra.maker_phone')}}">


                            <div class="inp_label">{{__('admin_catalog_sub_catalog_title')}}</div>
                            <select style="width: 425px;margin-bottom: 15px;
    height: 40px;" disabled class="inp2 select2" name="category" id="category_id" required>
                                <option value="null"> {{__('admin_catalog_sub_catalog_title')}}</option>
                            </select>

                            <div id="price_id">
                                <div style="margin-top: 20px;" class="inp_label">{{__('seller_d_product_number')}}</div>
                                <input id="stir_id" type="number" class="inp2" name="quantity" required>
                            </div>
                        </div>
                    </div>

{{--                    <div class="inp_label"--}}
{{--                         style="font-size: 16px;font-weight: bold; color: #3e444a;margin-top: 20px;--}}
{{--                         margin-bottom: 20px;">--}}
{{--                       {{__('selling_type_all')}}--}}
{{--                    </div>--}}

{{--                    <input checked type="radio" name="sell_type" id="roznica" value="retail">--}}
{{--                    <label class="text_label" for="roznica">{{__('selling_type_1')}}</label>--}}
{{--                    <input type="radio" name="sell_type" id="optom" value="wholesale">--}}
{{--                    <label class="text_label" for="optom">{{__('selling_type_2')}}</label>--}}
{{--                    <input type="radio" name="sell_type" id="optomrozn" value="both">--}}
{{--                    <label class="text_label" for="optomrozn">{{__('selling_type_3')}}</label>--}}

                </div>

                <div style="display: flex;
    justify-content: space-evenly;
    align-content: center;">
                    <a style="display: block;margin-left: inherit; text-decoration: none" class="btn"
                       href="{{route('seller.product.index',app()->getLocale())}}">
                        {{__('cancel')}}
                    </a>
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
                let text = ' <div style="margin-top: 20px;" class="inp_label">{{__('seller_d_product_number')}}</div>' +
                    '<input id="stir_id" type="number"  class="inp2" name="quantity"  required>'
                $('#price_id').html(text)
            }
        })
      

        $('#catalog_id').on('change', function (event) {
            let id = $(this).find('option:selected').val();
            if (isNaN(id)) {
                $('#category_id').prop('disabled', true);
                $('#category_id').html('')
                $('#category_id').append(new Option('{{__('admin_catalog_sub_catalog_title')}}', 'null'))
            } else {
                clearTimeout(time)
                time = setTimeout(function () {
                    $.ajax({
                        type: 'POST',
                        url: '/ajax/categories',
                        data: {
                            id: id,
                        },

                        success: function (obj) {
                            $('#category_id').prop('disabled', false);
                            $('#category_id').html('')
                            $('#category_id').append(new Option('{{__('admin_catalog_sub_catalog_title')}}', 'null'))
                            $.each(obj.data, function (index, value) {
                                $('#category_id').append(new Option(value.name_{{app()->getLocale()}}, value.id))
                            })
                        }
                    });
                }, 500);
            }
        })
    </script>


@endsection
