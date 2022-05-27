@extends('admin.layouts.admin-layout')
@section('content')
    <div class="main_info">
        <div class="add">
            <form action="{{route('admin.video.store',app()->getLocale())}}" method="post"
                  enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="0">
                <div class="title">{{__('add_video')}}</div>
                <div class="b1">
                    <div class="b4">
                        <div class="tabs-block">
                            <div class="tabs">
                                    <div class="tab">O'zbekcha</div>
                                <div class="tab">На русском</div>

                            </div>
                            <div class="tabs-content">
                                <div class="tab-item">
                                    <div class="t1">
                                        @error('name_uz')
                                        <span style="color: #c40001">  {{$message}}</span>
                                        @else
                                            Nomi
                                            @enderror
                                    </div>
                                    <input required name="name_uz" type="text" class="inp"
                                           placeholder="Nomini kiriting" value="{{old('name_uz')}}">

                                    <div class="t1">
                                        @error('description_uz')
                                        <span style="color: #c40001">  {{$message}}</span>
                                        @else
                                            Qisqa / To'liq tavsif
                                            @enderror
                                    </div>
                                    <textarea required name="description_uz" class="inp textarea" rows="7"
                                              placeholder="Tavsif kiriting">{{old('description_uz')}}</textarea>
                                </div>
                                <div class="tab-item">
                                    <div class="t1">
                                        @error('name_ru')
                                        <span style="color: #c40001">  {{$message}}</span>
                                        @else
                                            Название
                                            @enderror

                                    </div>
                                    <input required name="name_ru" type="text" class="inp"
                                           placeholder="Введите название" value="{{old('name_ru')}}">
                                    <div class="t1">
                                        @error('description_ru')
                                        <span style="color: #c40001">  {{$message}}</span>
                                        @else
                                            Краткое описание / Полное описание
                                            @enderror
                                    </div>
                                    <textarea required name="description_ru" class="inp textarea" rows="7"
                                              placeholder="Введите описание">{{old('description_ru')}}</textarea>
                                </div>
                            </div>
                        </div>
                        <select class="select" name="section">
                            {{--                            <optgroup label="select one option">--}}
                            @error('section')
                            <option value="null" selected style="color: #c40001">{{$message}}</option>
                            @else
                                <option value="null" selected>{{__('section')}}</option>
                                @enderror
                                @if(count($sections)>0)
                                    @foreach($sections as $section)
                                        <option value="{{$section->id}}">{{$section['name_'.app()->getLocale()]}}</option>
                                    @endforeach
                                @endif
                        </select>
                    </div>
                    <div class="b2">

                        <div class="b3">
                            <div class="t2">{{__('active')}}</div>
                            <label class="iosCheck blue">
                                <input type="checkbox" name="is_active" checked>
                                <i></i>
                            </label>
                            <div class="t3" style="margin-top: 20px" id="title_id">
                                @error('video')
                                <span style="color: #c40001">  {{$message}}</span>
                                @else
                                    {{__('download_video')}}
                                    @enderror
                            </div>
                            <div class="t4">Форматы: MPEG, MPEG-1, MPEG-2, MPEG-4, HD</div>
                            <div class="input__wrapper">
                                        <input required name="video" onchange="checkVideoFile()" type="file" id="video_id"
                                       class="input input__file"
                                >
                                <label for="video_id" class="input__file-button">{{__('upload')}}</label>
                            </div>
                            <div id="upload_id" class="prev_block" style="display: none">
                                <img style="height: 200px; width: 200px" src="/assets/img/video.png">
                            </div>
                            <div class="clear"></div>

                        </div>
                    </div>
                </div>
                <div class="btns_block">
                    <a class="btn back_b_list" href="{{route('admin.video.index',app()->getLocale())}}">Вернуться</a>
                    <button type="submit" class="btn">Сохранить</button>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('script')
    <script>
        function checkVideoFile() {
            $('#upload_id').removeAttr('style')
            $('#title_id').html('видео загружено')

        }
    </script>


@endsection

