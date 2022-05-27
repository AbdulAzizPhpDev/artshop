<?php

namespace App\Http\Controllers\Admin;

use App\Catalog;
use App\Helper\ImageMaker;
use App\Http\Controllers\Controller;
use App\Http\Requests\CatalogRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class CatalogController extends Controller
{
    protected $paginate = 5;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $catalogs = Catalog::where('parent_id', 0)->orderBy('position')->paginate($this->paginate);
        return view('admin.pages.catalog.index', compact('catalogs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CatalogRequest $request)
    {


        $imageTypeArray = array
        (
            2 => 'jpg',
            3 => 'png',
        );

        $path = null;
        $name = null;
        $count = Catalog::count();
        $catalog = $request->id != 0 ? Catalog::findOrFail($request->get('id')) : new Catalog();
        $catalog->name_uz = $request->name_uz;
        $catalog->name_ru = $request->name_ru;
        $catalog->description_uz = $request->description_uz;
        $catalog->description_ru = $request->description_ru;
        if (!isset($request->isActive)) {
            $catalog->is_active = false;
        } else {
            $catalog->is_active = true;
        }
        if ($request->parent_id != 0) {
            $catalog->parent_id = $request->parent_id;
        } else {
            $catalog->parent_id = 0;
        }


        if ($count==0 || (isset($catalog->id) && $catalog->id==3)) {
            if ($request->parent_id == 0 && $request->id == 0) {
            if ((isset($request->image) && $request->hasFile('image'))) {
                list($width, $height, $type) = getimagesize($request->image);
                $path = "catalog/";
                $name = preg_replace('/\./', '', microtime(true)) . '.jpg';
                $catalog->image = (new ImageMaker(628, 373))->makeImage($request->image, $path, $name);
            }
        } elseif ($request->id != 0) {
            if ((isset($request->image) && $request->hasFile('image'))) {
                list($width, $height, $type) = getimagesize($request->image);
                if (Storage::disk('public')->exists($catalog->image)) {
                    Storage::disk('public')->delete($catalog->image);
                }
                if ($catalog->parent_id == 0) {
                    $path = "catalog/";
                } else {
                    $path = 'catalog/sub_catalog/parent' . $catalog->parent_id . '/';
                }
                $name = preg_replace('/\./', '', microtime(true)) . '.jpg';
                $catalog->image = (new ImageMaker(628, 373))->makeImage($request->image, $path, $name);
            }
        } else {
            if ((isset($request->image) && $request->hasFile('image'))) {
                list($width, $height, $type) = getimagesize($request->image);
                $parent_catalog = Catalog::where('id', $request->parent_id)->first();
                $path = 'catalog/sub_catalog/parent' . $parent_catalog->id . '/';
                $name = preg_replace('/\./', '', microtime(true)) . '.jpg';
                $catalog->image = (new ImageMaker(628, 373))->makeImage($request->image, $path, $name);
            }
        }
        }
        else
        {
            if ($request->parent_id == 0 && $request->id == 0) {
            if ((isset($request->image) && $request->hasFile('image'))) {
                list($width, $height, $type) = getimagesize($request->image);
                $path = "catalog/";
                $name = preg_replace('/\./', '', microtime(true)) . '.jpg';
                $catalog->image = (new ImageMaker(302, 373))->makeImage($request->image, $path, $name);
            }
        } elseif ($request->id != 0) {
            if ((isset($request->image) && $request->hasFile('image'))) {
                list($width, $height, $type) = getimagesize($request->image);
                if (Storage::disk('public')->exists($catalog->image)) {
                    Storage::disk('public')->delete($catalog->image);
                }
                if ($catalog->parent_id == 0) {
                    $path = "catalog/";
                } else {
                    $path = 'catalog/sub_catalog/parent' . $catalog->parent_id . '/';
                }
                $name = preg_replace('/\./', '', microtime(true)) . '.jpg';
                $catalog->image = (new ImageMaker(302, 373))->makeImage($request->image, $path, $name);
            }
        } else {
            if ((isset($request->image) && $request->hasFile('image'))) {
                list($width, $height, $type) = getimagesize($request->image);
                $parent_catalog = Catalog::where('id', $request->parent_id)->first();
                $path = 'catalog/sub_catalog/parent' . $parent_catalog->id . '/';
                $name = preg_replace('/\./', '', microtime(true)) . '.jpg';
                $catalog->image = (new ImageMaker(302, 373))->makeImage($request->image, $path, $name);
            }
        }
        }


        $catalog->save();
        if ($catalog->parent_id == 0) {
            return redirect()->route('admin.catalog.index', ['lang' => app()->getLocale()])->withErrors(['success'=>'success']);

        } else {
            return redirect()->route('admin.catalog.sub_catalog', ['id' => $catalog->parent_id, 'lang' => app()->getLocale()])->withErrors(['success'=>'success']);
        }
    }


    public
    function edit($lang, $id)
    {

        $redirect_url = url()->previous();

        $catalog = Catalog::where('id', $id)->first();
        $parent_catalog = Catalog::where('parent_id', 0)->get();
        return view('admin.pages.catalog.create_edit', compact('catalog', 'parent_catalog', 'redirect_url'));
    }


    public
    function create()
    {
        $redirect_url = url()->previous();
        $parent_catalog = Catalog::where('parent_id', 0)->get();
        return view('admin.pages.catalog.create_edit', compact("parent_catalog", "redirect_url"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function destroy($id)
    {
        //
    }

    public function subCatalog($lang, $id)
    {
        $sub_catalogs = Catalog::where('parent_id', $id)->paginate($this->paginate);
        $catalog = Catalog::where('id', $id)->first();
        return view('admin.pages.catalog.sub_catalog', compact('sub_catalogs', 'catalog'));
    }

    public function updateStatus(Request $request)
    {

        $data = Catalog::findOrFail($request->id);
        $data->is_active = filter_var($request->status, FILTER_VALIDATE_BOOLEAN);
        $data->save();
        return response()->json([
            "status" => $data->is_active
        ]);

    }

    public function search($lang, $search)
    {
        $catalogs = null;
        if (preg_match('/[А-Яа-яЁё]/u', $search)) {
            $catalogs = Catalog::where('parent_id', 0)->
            Where('name_ru', 'LIKE', "%$search%")->
            paginate($this->paginate);
        } else {
            $catalogs = Catalog::where('parent_id', 0)->
            where('name_uz', 'LIKE', "%$search%")->
            paginate($this->paginate);
        }


        return view('admin.pages.catalog.index', compact('catalogs', 'search'));
    }

    public function postSearch(Request $request)
    {

        if ($request->search == null)
            return redirect()->route('admin.catalog.index', ['lang' => app()->getLocale()]);
        return redirect()->route('admin.catalog.search', ["search" => $request->search, 'lang' => app()->getLocale()]);
    }

}
