@extends('user.layouts.page-layout')
@section('content')
    <div class="about">
        <div class="content">
            <div class="navi">
                <a href="/" class="navi_point">{{__('main')}}</a>
                <a class="navi_point">{{__('header_about')}}</a>
            </div>
            <div class="page_title">{{__('help_text_1')}}</div>
            <div class="page_subtitle">
                {{__('help_text_2')}}
            </div>
            <div class="b1">
                <div class="b2">
                    <img src="/assets/img/about_1.jpg" class="img">
                    <div class="t1">
                        {{__('help_text_3')}}
                    </div>
                </div>
                <div class="b2">
                    <img src="/assets/img/about_2.jpg" class="img">
                    <div class="t1">
                        {{__('help_text_4')}}

                    </div>
                </div>
            </div>
            <div class="b3">
                <div class="b4">
                    <div class="t2"><span>Artshop</span>
                        {{__('help_text_5')}}
                    </div>
                    <div class="t2" style="margin-top: 91px"><span>Artshop</span>
                        {{__('help_text_6')}}
                    </div>
                </div>
                <div class="logo"></div>
            </div>
        </div>
    </div>
@endsection
