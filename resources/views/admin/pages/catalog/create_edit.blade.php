@extends('admin.layouts.admin-layout')
@section('content')

    <div class="main_info">
        <form
            action="{{!isset($catalog) ? route('admin.catalog.store',['lang'=>app()->getLocale()]) : route('admin.catalog.update',['lang'=>app()->getLocale()])}}"
            method="post"
            enctype="multipart/form-data" accept-charset="UTF-8">

            @csrf
            <input type="hidden" value="{{isset($catalog)? $catalog->id : 0}}" name="id">
            <div class="add">
                <div class="title">
                    @if (isset($catalog))
                        {{__('admin_catalog_edit')}}
                    @else
                        {{__('admin_catalog_add')}}
                    @endif
                </div>
                <div class="b1">
                    <div class="b4">
                        <input type="hidden" id="tab_status"
                               value="{{$errors->has('name_uz')? 0: ($errors->has('name_ru')? 1:0 ) }}">
                        <div class="tabs-block">
                            <div class="tabs">

                                <div class="tab {{$errors->has('name_uz') ? "active":""}}">O'zbek tilida</div>
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
                                    <input name="name_uz" type="text" class="inp" placeholder="Sarlavhani kiriting"
                                           required value="{{isset($catalog) ? $catalog->name_uz :old('name_uz')}}">
                                    <div class="t1">{{__('admin_catalog_creat_description_title')}}</div>
                                    <textarea name="description_uz" class="inp textarea"
                                              rows="7"
                                              placeholder="izohni kiriting">{{isset($catalog) ? $catalog->name_uz :old('description_uz')}}</textarea>
                                </div>
                                <div class="tab-item">
                                    <div class="t1">@error('name_ru')
                                        {{$message}}
                                        @else
                                            Название
                                            @enderror</div>
                                    <input name="name_ru" type="text" class="inp"
                                           placeholder="Введите название"
                                           required value="{{isset($catalog) ? $catalog->name_ru :old('name_ru')}}">
                                    <div class="t1">Краткое описание / Полное описание</div>
                                    <textarea name="description_ru" class="inp textarea"
                                              rows="7"
                                              placeholder="Введите описание">{{isset($catalog) ? $catalog->name_uz :old('description_ru')}}
                                    </textarea>
                                </div>
                            </div>
                        </div>

                        @if (!isset($catalog)  ||  $catalog->parent_id!=0)
                            <select class="select" name="parent_id">
                                <option value="0">{{__('admin_catalog_creat_main_catalog')}}</option>
                                @if(!empty($parent_catalog) )
                                    @foreach($parent_catalog as $catalog_item)
                                        @if(isset($catalog))
                                            @if($catalog_item->id!=$catalog->id)
                                                <option
                                                    value="{{$catalog_item->id}}" {{$catalog_item->id==$catalog->parent_id ? "selected" : ""}} >
                                                    {{$catalog_item->name_uz}}
                                                </option>
                                            @endif
                                        @else
                                            <option
                                                value="{{$catalog_item->id}}">
                                                {{$catalog_item->name_uz}}
                                            </option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        @endif
                    </div>

                    <div class="b2">

                        <div class="b3">
                            <div class="t2">{{__('active')}}</div>
                            <label class="iosCheck blue">
                                <input name="isActive"
                                       type="checkbox" {{isset($catalog) ? ($catalog->is_active ? "checked" : "") : "checked" }}>
                                <i></i>
                            </label>

                            <img id="image" class="t3" style="width: 100%;height: 100%"
                                 src="{{(isset($catalog) && $catalog->is_active && $catalog->image)? Storage::url($catalog->image) : '/1.jpg'}}"
                            >
                            <div class="t4" id="file_warning">{{__('image_format')}} </div>
                            <div class="input__wrapper">
                                <input name="image" type="file" name="file" id="input__file" class="input input__file"
                                >
                                <label for="input__file"
                                       class="input__file-button">{{(isset($catalog) && $catalog->image) ? __('update'): __('upload')}}</label>
                            </div>

                        </div>

                    </div>

                </div>
                <div class="btns_block" style="margin:0">
                    <a class="btn back_b_list" href="{{$redirect_url}}">{{__('cancel')}}</a>
                    <button type="submit" style="background-color: #FFFFFF;
                    border: 2px solid #5eaff0; color: #000;height: 52px" onclick="checkFile()"
                            class="btn save">{{__('save')}}
                    </button>

                </div>
            </div>
        </form>
    </div>

@endsection
@section('script')

    <script>
        $("#input__file").on('change', function (event) {
            var image = document.getElementById('image');
            image.src = URL.createObjectURL(event.target.files[0]);
            $('#file_warning').html('image is uploaded')
        })

        function checkFile() {
            if (!$('#input__file')[0].files.length) {
                $('#file_warning').html('uplaod image')
            }
        }
    </script>

@endsection
