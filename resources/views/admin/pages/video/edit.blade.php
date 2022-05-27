@extends('admin.layouts.admin-layout')
@section('content')

    <div class="main_info">
        <div class="add">
            <form action="{{route('admin.video.store',['lang'=>app()->getLocale()])}}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{$video->id}}">
                <div class="title">{{__('edit_video')}}</div>
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
                                           placeholder="Введите название" value="{{$video->name_uz}}">

                                    <div class="t1">
                                        @error('description_uz')
                                        <span style="color: #c40001">  {{$message}}</span>
                                        @else
                                            Qisqa / To'liq tavsif
                                            @enderror
                                    </div>
                                    <textarea required name="description_uz" class="inp textarea" rows="7"
                                              placeholder="Введите описание">{{$video->description_uz}}</textarea>
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
                                           placeholder="Введите название" value="{{$video->name_ru}}">
                                    <div class="t1">
                                        @error('description_ru')
                                        <span style="color: #c40001">  {{$message}}</span>
                                        @else
                                            Краткое описание / Полное описание
                                            @enderror
                                    </div>
                                    <textarea required name="description_ru" class="inp textarea" rows="7"
                                              placeholder="Введите описание">{{$video->description_ru}}</textarea>
                                </div>
                            </div>
                        </div>
                        <select class="select" name="section">
                            {{--                            <optgroup label="select one option">--}}
                            @error('section')
                            <option value="null" selected style="color: #c40001">{{$message}}</option>
                            @else
                                @enderror
                                @if(count($sections)>0)
                                    @foreach($sections as $section)
                                        <option value="{{$section->id}}"
                                            {{$section->id==$video->getSection->id ? "selected" : " "}} >{{$section->name_uz}}</option>
                                    @endforeach
                                @endif
                        </select>
                    </div>
                    <div class="b2">

                        <div class="b3">
                            <div class="t2">{{__('active')}}</div>
                            <label class="iosCheck blue">
                                <input type="checkbox" name="is_active" {{$video->is_active ? "checked": "" }}>
                                <i></i>
                            </label>
                            <div class="t3" style="margin-top: 20px" id="title_id">
                                @error('video')
                                <span style="color: #c40001">  {{$message}}</span>
                                @else
                                    {{__('update_video')}}
                                    @enderror
                            </div>
                            <div class="t4">{{__('format_video')}}</div>
                            <div class="input__wrapper">
                                <input name="video" type="file" id="video_id"
                                       class="input input__file" onchange="checkVideoFile()"
                                >
                                <label style="width: auto" for="video_id" class="input__file-button">{{__('download_video')}}</label>
                            </div>
                            <div class="prev_block">
                                <img style="height: 200px; width: 200px" src="/assets/img/video.png">
                            </div>

                        </div>
                    </div>
                </div>
                <div class="btns_block">
                    <a class="btn back_b_list" href="{{route('admin.video.index',['lang'=>app()->getLocale()])}}">{{__('cancel')}}</a>
                    <button type="submit" class="btn">{{__('save')}}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function checkVideoFile() {
            $('#title_id').html('видео загружено')
        }
    </script>
@endsection

