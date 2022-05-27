<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::where('parent_id', 0)->get();
        return view('admin.pages.region.index', compact('regions'));
    }

    public function addAndUpdateRegion(Request $request)
    {
        $region = $request->id == 0 ? new Region() : Region::where('id', $request->id)->first();
        if ($request->id == 0) {
            if ($request->parent_id == 0) {
                $region->parent_id = 0;
            } else {
                $region->parent_id = $request->parent_id;
            }
        }
        $region->name_ru = $request->name_ru;
        $region->name_uz = $request->name_uz;
        $region->save();
        return redirect()->to($request->redirect_url)->withErrors(['success'=>'success']);;
    }

    public function create()
    {
        $redirect_url = url()->previous();
        return view('admin.pages.region.create', compact('redirect_url'));
    }

    public function update($lang, $id)
    {
        $region = Region::where('id', $id)->first();
        $redirect_url = url()->previous();
        return view('admin.pages.region.update', compact('region', 'redirect_url'))->withErrors(['success'=>'success']);;
    }

    public function district($lang, $region_id)
    {
        $districts = Region::where('parent_id', $region_id)->get();
        $region = Region::where('id', $region_id)->first();
        return view('admin.pages.region.district', compact('districts', 'region', 'region_id'));
    }

    public function Createdistrict($lang, $region_id)
    {
        $redirect_url = url()->previous();
        return view('admin.pages.region.district-create', compact('region_id', 'redirect_url'));
    }

    public function destroy(Request $request)
    {

        $region = Region::where('id', $request->id)->first();

        if ($region->parent_id == 0) {
            Region::where('parent_id', $request->id)->delete();
            $region->delete();

        } else {
            $region->delete();
        }
        return response()->json([
            'status' => true
        ]);

    }


}
