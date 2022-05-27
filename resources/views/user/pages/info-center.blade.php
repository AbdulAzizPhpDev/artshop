@extends('user.layouts.page-layout')
@section('content')
@section('my_style')
    <style>
        a:hover.t2, a:hover.infovideo_title {
            color: #FBB03B !important;
        }

        .view_active {
            background-color: #262F91;
            color: #FFFFFF !important;
        }
    </style>
@endsection

<div class="infocentr">
    <div class="content">
        <div class="navi">
            <a href="{{route('user.index',['lang'=>app()->getLocale()])}}" class="navi_point">{{__('main')}}</a>
            @if(isset($section_item))
                <a href="{{route('user.info_center.index',['lang'=>app()->getLocale()])}}"
                   class="navi_point">{{__('header_info_center')}}</a>
                <a class="navi_point">{{ucfirst($section_item->name_uz)}}</a>
            @elseif(isset($range))
                <a href="{{route('user.info_center.index',['lang'=>app()->getLocale()])}}"
                   class="navi_point">{{__('header_info_center')}}</a>
                <a class="navi_point">{{ucfirst(__($range))}}</a>
            @else
                <a class="navi_point">{{__('header_info_center')}}</a>
            @endif

        </div>
        <div class="page_title">{{__('header_info_center')}}</div>
        <div class="b1">

                <form action="{{route('user.info_center.post.search',['lang'=>app()->getLocale()])}}" method="post">
                    @csrf
                    <input type="text" placeholder="{{__('user_video_search')}}" class="search" name="search"
                           value="{{isset($search) ? $search : ""  }}">
                    <input type="hidden" name="id" value="{{isset($section_item) ? $section_item->id : 0  }}">
                </form>

            <a href="{{route('user.info_center.viewed',['range'=>'day','lang'=>app()->getLocale()])}}"
               class="days_filter {{route('user.info_center.viewed',['range'=>'day','lang'=>app()->getLocale()]) == url()->current() ? "view_active" : ""}} ">
                {{__('user_video_filter_day')}}
            </a>
            <a href="{{route('user.info_center.viewed',['range'=>'days','lang'=>app()->getLocale()])}}"
               class="days_filter {{route('user.info_center.viewed',['range'=>'days','lang'=>app()->getLocale()]) == url()->current() ? "view_active" : ""}} ">
                {{__('user_video_filter_days')}}
            </a>
            <a href="{{route('user.info_center.viewed',['range'=>'week','lang'=>app()->getLocale()])}}"
               class="days_filter {{route('user.info_center.viewed',['range'=>'week','lang'=>app()->getLocale()]) == url()->current() ? "view_active" : ""}} ">
                {{__('user_video_filter_week')}}
            </a>
            <a href="{{route('user.info_center.viewed',['range'=>'month','lang'=>app()->getLocale()])}}"
               class="days_filter {{route('user.info_center.viewed',['range'=>'month','lang'=>app()->getLocale()]) == url()->current() ? "view_active" : ""}} ">
                {{__('user_video_filter_month')}}
            </a>
        </div>
        <div class="b2">
            <div class="sidebar">
                @if(count($sections)>0)
                    <a class="t1">{{__('section_site_bar')}}</a>
                    @foreach($sections as $section)
                        @if(isset($section_item))
                            @if($section_item->id==$section->id)
                                <a style="color: #262F91;"
                                   href="{{route('user.info_center.show.section.video',['id'=>$section->id,'lang'=>app()->getLocale()])}}"
                                   class="t2">{{ucfirst($section['name_'.app()->getLocale()])}}</a>
                            @else
                                <a href="{{route('user.info_center.show.section.video',['id'=>$section->id,'lang'=>app()->getLocale()])}}"
                                   class="t2">{{ucfirst($section['name_'.app()->getLocale()])}}</a>
                            @endif
                        @else
                            <a href="{{route('user.info_center.show.section.video',['id'=>$section->id,'lang'=>app()->getLocale()])}}"
                               class="t2">{{ucfirst($section['name_'.app()->getLocale()])}}</a>
                        @endif
                    @endforeach
                @endif
            </div>
            <div class="b3">
                @if(count($videos)>0)
                    @foreach($videos as $video)
                        <div class="infoblock">

                            <video id="video_{{$video->id}}" onplay="videoCount({{$video->id}})" class="infovideo"
                                   preload="none" controls>
                                <source src="{{Storage::url($video->video)}}">
                            </video>
                            <a href="{{route('user.info_center.show.video',[
                            'name'=>str_replace(' ', '', $video->getSection->name_uz),
                            'id'=>$video->id,
                             'lang'=>app()->getLocale()
                             ])}}"
                               class="infovideo_title">{{$video['name_'.app()->getLocale()]}} </a>
                            <div class="infodata">
                                <div class="infovideo_author">admin</div>
                                <div id="infovideo_view_{{$video->id}}"
                                     class="infovideo_views">{{$video->view_count}}</div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@section('my_script')

@endsection
