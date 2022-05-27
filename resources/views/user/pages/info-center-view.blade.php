@extends('user.layouts.page-layout')
@section('content')

    <div class="infocentr_view">
        <div class="content">
            <div class="navi">
                <a href="{{route('user.index',app()->getLocale())}}" class="navi_point">{{__('main')}}</a>
                <a href="{{route('user.info_center.index',app()->getLocale())}}"
                   class="navi_point">{{__('header_info_center')}}</a>
                <a class="navi_point">{{$video->name_uz}} </a>
            </div>

            <div class="view" style="margin-top:100px ">
                <video id="video_{{$video->id}}" onplay="videoCount({{$video->id}})" class="infovideo" preload="none"
                       controls="controls">
                    <source src="{{Storage::url($video->video)}}">
                </video>
                <div class="t1">{{$video['name_'.app()->getLocale()]}}</div>
                <div class="data">
                    <div class="t2">{{date( 'Y-m-d', strtotime($video->created_at) )}}</div>
                    <div class="t2">{{__('section')}}: {{$video->getSection['name_'.app()->getLocale()]}}</div>
                    <div id="infovideo_view_{{$video->id}}" class="views">{{$video->view_count}}</div>
                </div>
                <div class="buttons">
                    <div style="cursor: pointer" onclick="videoLike({{$video->id}},true)" id="like_{{$video->id}}"
                         class="like">{{$likes}}
                    </div>
                    <div style="cursor: pointer" onclick="videoLike({{$video->id}},false)" id="dislike_{{$video->id}}"
                         class="dislike">{{$dislikes}}
                    </div>
                    <div class="share"></div>

                </div>
                <div class="data" style="margin-top: 20px">
                    @if(!empty($video['description_'.app()->getLocale()]))
                        <p class="infovideo_author"
                           style="font-size: 20px">{{$video['description_'.app()->getLocale()]}}</p>
                    @endif
                </div>


            </div>
            <div class="t3">{{__('user_video_s')}}</div>
            <div class="regular slider">
                @if(count($related_videos)>0)
                    @foreach($related_videos as $related_video)
                        <div class="padd_block">
                            <div class="featured_block">
                                <video id="video_{{$related_video->id}}" onplay="videoCount({{$related_video->id}})"
                                       class="featured_video" preload="none" controls="controls">
                                    <source src="{{Storage::url($related_video->video)}}">
                                </video>
                                <div class="featured_block_info">
                                    <a href="{{route('user.info_center.show.video',['name'=>str_replace(' ', '', $video->getSection['name_'.app()->getLocale()]),'id'=>$related_video->id
    ,'lang'=>app()->getLocale()])}}" class="t4">{{$related_video['name_'.app()->getLocale()]}} </a>

                                    <div class="infodata">
                                        <div class="infovideo_author">admin</div>
                                        <div id="infovideo_view_{{$video->id}}"
                                             class="infovideo_views">{{$video->view_count}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
        </div>
    </div>
@endsection
