<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Video;
use App\VideoCount;
use App\VideoLike;
use App\VideoSection;
use Illuminate\Http\Request;
use Carbon\Carbon;


class InfoCenterController extends Controller
{
    public function index()
    {
        $videos = Video::where('is_active', true)->with(['getSection'])->get();
        $sections = VideoSection::where([
            ['parent_id', 0],
            ['is_active', true]
        ])->get();
        return view('user.pages.info-center', compact('videos', 'sections'));
    }

    public function showVideo($lang,$name, $id)
    {
        $video = Video::where([
            ['id', $id],
            ['is_active', true]
        ])->with(['like'])->first();

        if (empty($video)) {
            return redirect()->route('user.info_center.index',['lang'=>app()->getLocale()]);
        }

        $likes = $video->like->where('like', true)->count();
        $dislikes = $video->like->where('like', false)->count();
        $related_videos = Video::where('section_id', $video->section_id)->with(['getSection'])->get();
        return view('user.pages.info-center-view', compact('video', 'related_videos', 'likes', 'dislikes'));
    }

    public function showSectionVideo($lang,$id)
    {
        $videos = Video::where([
            ['is_active', true],
            ['section_id', $id]
        ])->with(['getSection'])->get();


        $sections = VideoSection::where([
            ['parent_id', 0],
            ['is_active', true],
        ])->get();

        $section_item = VideoSection::where([
            ['id', $id]
        ])->first();
        return view('user.pages.info-center', compact('videos', 'sections', 'section_item'));
    }

    public function postSearch(Request $request)
    {

        if ($request->id != 0) {
            if (is_null($request->search)) {
                return redirect()->route('user.info_center.show.section.video', ['id' => $request->id,'lang'=>app()->getLocale()]);
            }

            return redirect()->route('user.info_center.search.section.video',
                ['id' => $request->id, 'search' => $request->search,'lang'=>app()->getLocale()]);
        } else {
            if (is_null($request->search)) {
                return redirect()->route('user.info_center.index',['lang'=>app()->getLocale()]);
            }
            return redirect()->route('user.info_center.search', ['search' => $request->search,'lang'=>app()->getLocale()]);
        }

    }

    public function search($lang,$search)
    {
        $videos = null;
        if (preg_match('/[А-Яа-яЁё]/u', $search)) {
            $videos = Video::where([
                ['is_active', true],
            ])->
            Where('name_ru', 'LIKE', "%$search%")->
            with(['getSection'])->get();

        } else {
            $videos = Video::where([
                ['is_active', true],
            ])->
            Where('name_uz', 'LIKE', "%$search%")->
            with(['getSection'])->get();
        }
        $sections = VideoSection::where([
            ['parent_id', 0],
            ['is_active', true]
        ])->get();
        return view('user.pages.info-center', compact('videos', 'sections', 'search'));
    }

    public function searchSection($lang,$id, $search)
    {
        $videos = null;
        if (preg_match('/[А-Яа-яЁё]/u', $search)) {
            $videos = Video::where([
                ['is_active', true],
                ['section_id', $id],
            ])->
            Where('name_ru', 'LIKE', "%$search%")->
            with(['getSection'])->get();

        } else {
            $videos = Video::where([
                ['is_active', true],
                ['section_id', $id],
            ])->
            Where('name_uz', 'LIKE', "%$search%")->
            with(['getSection'])->get();
        }


        $sections = VideoSection::where([
            ['parent_id', 0],
            ['is_active', true],
        ])->get();

        $section_item = VideoSection::where([
            ['id', $id]
        ])->first();
        return view('user.pages.info-center', compact('videos', 'sections', 'section_item', 'search'));
    }

    public function countVideo(Request $request)
    {
        $video_count = VideoCount::where([
            ['ip', request()->ip()],
            ['video_id', $request->video_id]
        ])->exists();
        if (!$video_count) {
            $video_count = new VideoCount();
            $video_count->ip = request()->ip();
            $video_count->video_id = $request->video_id;
            $video_count->save();

            $count = VideoCount::where('video_id', $request->video_id)->count();

            $video = Video::where('id', $request->video_id)->first();
            $video->view_count = $count;
            $video->save();


            return response()->json([
                'status' => true,
                'count' => $count,
                'id' => $video->id
            ]);
        }

        return response()->json([
            'status' => false,
            'count' => null
        ]);

    }

    public function likeVideo(Request $request)
    {

        if (auth()->check()) {
            $like_status = VideoLike::where([
                ['user_id', auth()->user()->id],
                ['video_id', $request->video_id]
            ])->exists();
            if ($like_status) {
                $like = VideoLike::where([
                    ['user_id', auth()->user()->id],
                    ['video_id', $request->video_id]
                ])->first();
                if ($like->like == filter_var($request->status, FILTER_VALIDATE_BOOLEAN)) {
                    return response()->json([
                        'status' => 'nothing',
                    ]);
                } else {
                    $like->like = filter_var($request->status, FILTER_VALIDATE_BOOLEAN);
                    $like->save();
                    $like_count = VideoLike::where('video_id', $request->video_id)->get();
                    $likes = $like_count->where('like', true)->count();
                    $dislikes = $like_count->where('like', false)->count();
                    return response()->json([
                        'status' => 'update',
                        'like' => $likes,
                        'dislike' => $dislikes,
                        'id' => $request->video_id,
                    ]);
                }

            } else {
                $like = new VideoLike();
                $like->user_id = auth()->user()->id;
                $like->video_id = $request->video_id;
                $like->like = filter_var($request->status, FILTER_VALIDATE_BOOLEAN);
                $like->save();

                $like_count = VideoLike::where('video_id', $request->video_id)->get();
                $likes = $like_count->where('like', true)->count();
                $dislikes = $like_count->where('like', false)->count();
                return response()->json([
                    'status' => 'new',
                    'like' => $likes,
                    'dislike' => $dislikes,
                    'id' => $request->video_id,
                ]);
            }
        }
        return response()->json([
            'status' => 'login'
        ]);

    }

    public function mostViewed($lang,$range)
    {

        $mutable = Carbon::now();
        $from = $mutable->format('Y-m-d');

        if ($range == "days") {
            $to = $mutable->sub(3, 'day')->format('Y-m-d');
        } else {
            $to = $mutable->sub(1, $range)->format('Y-m-d');
        }
        $videos = Video::whereDate('updated_at', '<', $from)->whereDate('updated_at', '>', $to)->orderBy('view_count', 'desc')->get();
        $sections = VideoSection::where([
            ['parent_id', 0],
            ['is_active', true]
        ])->get();

        return view('user.pages.info-center', compact('videos', 'sections', 'range'));


    }
}
