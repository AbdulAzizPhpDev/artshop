<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Video;
use App\VideoCount;
use App\VideoLike;
use App\VideoSection;
use Illuminate\Http\Request;
use VideoThumbnail;


class VideoController extends Controller
{
    protected $paginate = 5;

    public function index()
    {
        $videos = Video::with(['getSection'])->paginate($this->paginate);
        return view('admin.pages.video.index', compact('videos'));
    }

    public function addVideo()
    {
        $sections = VideoSection::where([
            ['parent_id', 0],
            ['is_active', true]
        ])->get();

        if (count($sections) == 0) {
            return redirect()->route('admin.section.index',app()->getLocale())->withErrors(['video_error' => 'before adding video, you should add section for it']);
        }
        return view('admin.pages.video.add', compact('sections'));
    }

    public function storeVideo(Request $request)
    {
        $video = $request->id != 0 ? Video::findOrFail($request->get('id')) : new Video();
        if ($request->id == 0) {
            $request->validate([
                'video' => 'required|mimes:mp4,ogx,oga,ogv,ogg,webm',
                'name_uz' => 'required|max:64|min:3',
                'name_ru' => 'required|max:64|min:3',


            ]);
        } else {
            $request->validate([
                'name_uz' => 'required|max:64|min:3',
                'name_ru' => 'required|max:64|min:3',


            ]);
        }

        if (!is_numeric($request->section)) {
            return back()->withInput()->withErrors([
                'section' => 'choose section'
            ]);
        }


        if ($request->file()) {
            $catalog = VideoSection::where('id', $request->section)->first();
            $fileName = preg_replace('/\./', '', microtime(true)) . '.' . 'mp4';
            $filePath = $request->file('video')->storeAs('videos/' . $catalog->id, $fileName, 'public');
            $video->video = $filePath;
        }

        $video->name_uz = $request->name_uz;
        $video->name_ru = $request->name_ru;
        $video->description_uz = $request->description_uz;
        $video->description_ru = $request->description_ru;
        $video->section_id = $request->section;

        if (isset($request->is_active)) {
            $video->is_active = true;
        } else {
            $video->is_active = false;
        }
        $video->save();
        return redirect()->route('admin.video.index',app()->getLocale())->withErrors(['success'=>'success']);;

    }

    public
    function editVideo($lang,$id)
    {
        $sections = VideoSection::where([
            ['parent_id', 0],
            ['is_active', true]
        ])->get();
        $video = Video::where('id', $id)->with(['getSection'])->first();

        return view('admin.pages.video.edit', compact('sections', 'video'))->withErrors(['success'=>'success']);;
    }

    public
    function searchVideo($lang,$search)
    {
        $videos = null;
        if (preg_match('/[А-Яа-яЁё]/u', $search)) {
            $videos = Video::
            Where('name_ru', 'LIKE', "%$search%")->
            paginate($this->paginate);
        } else {
            $videos = Video::
            where('name_uz', 'LIKE', "%$search%")->
            paginate($this->paginate);
        }


        return view('admin.pages.video.index', compact('videos', 'search'));
    }

    public
    function postSearchVideo(Request $request)
    {

        if ($request->search == null)
            return redirect()->route('admin.video.index',app()->getLocale());
        return redirect()->route('admin.video.search', ["search" => $request->search,"lang"=>app()->getLocale()]);
    }

    public function destroy(Request $request)
    {
        Video::where('id', $request->id)->delete();
        VideoLike::where('video_id', $request->id)->delete();
        VideoCount::where('video_id', $request->id)->delete();
        return response()->json([
            'status' => true
        ]);
    }

    public
    function updateVideoStatus(Request $request)
    {
        $data = Video::findOrFail($request->id);
        $data->is_active = filter_var($request->status, FILTER_VALIDATE_BOOLEAN);
        $data->save();
        return response()->json([
            "status" => $data->is_active
        ]);
    }

//           Section
    public
    function section()
    {
        $sections = VideoSection::where('parent_id', 0)->with(['videos'])->paginate($this->paginate);
        return view('admin.pages.section.index', compact('sections'));
    }

    public
    function addSection()
    {
//        $sections = VideoSection::where('parent_id', 0)->paginate($this->paginate);
        return view('admin.pages.section.add');
    }

    public
    function storeSection(Request $request)
    {
        $section = $request->id != 0 ? VideoSection::findOrFail($request->get('id')) : new VideoSection();
        $section->name_uz = $request->name_uz;
        $section->name_ru = $request->name_ru;
        $section->description_uz = $request->description_uz;
        $section->description_ru = $request->description_ru;
        $section->name_ru = $request->name_ru;
        if (!isset($request->is_active)) {
            $section->is_active = 0;
        } else {
            $section->is_active = 1;
        }
        if ($request->section != 0) {
            $section->parent_id = $request->section;
        }
        $section->parent_id = 0;

        $section->save();
        return redirect()->route('admin.section.index', app()->getLocale())->withErrors(['success'=>'success']);;
    }

    public
    function editSection($lang,$id)
    {


        $self_section = VideoSection::where('id', $id)->first();

        return view('admin.pages.section.edit', compact('self_section'));
    }

    public
    function searchSection($lang,$search)
    {
        $sections = null;
        if (preg_match('/[А-Яа-яЁё]/u', $search)) {
            $sections = VideoSection::where('parent_id', 0)->
            Where('name_ru', 'LIKE', "%$search%")->
            paginate($this->paginate);
        } else {
            $sections = VideoSection::where('parent_id', 0)->
            where('name_uz', 'LIKE', "%$search%")->
            paginate($this->paginate);
        }


        return view('admin.pages.section.index', compact('sections', 'search'));
    }

    public
    function postSearchSection(Request $request)
    {

        if ($request->search == null)
            return redirect()->route('admin.section.index');
        return redirect()->route('admin.section.search', ["search" => $request->search,'lang'=> app()->getLocale()]);
    }

    public
    function updateSectionStatus(Request $request)
    {
        $data = VideoSection::findOrFail($request->id);
        $data->is_active = filter_var($request->status, FILTER_VALIDATE_BOOLEAN);
        $data->save();
        if (!$data->is_active) {
            Video::where('section_id', $request->id)->update(['is_active' => false]);
        }
        return response()->json([
            "status" => $data->is_active
        ]);
    }

    public function destroySection(Request $request)
    {
        VideoSection::where('id', $request->id)->delete();
        $videos = Video::where('section_id', $request->id)->get();
        foreach ($videos as $video) {
            VideoCount::where('video_id', $video->id)->delete();
            VideoLike::where('video_id', $video->id)->delete();
            $video->delete();
        }
        return response()->json([
            'status' => 'delete all'
        ]);

    }
}
