<?php

namespace App\Http\Controllers\Admin;

use App\Archive;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    protected $paginate = 5;

    public function sellerIndex()
    {
        $sellers = User::where([
            ['role_id', 3],
            ['is_active', false]
        ])->paginate($this->paginate);
        return view('admin.pages.storage.archive-seller', compact('sellers'));
    }

    public function purchaserIndex()
    {
        $users = User::where([
            ['role_id', 2],
            ['is_active', false]
        ])->paginate($this->paginate);
        return view('admin.pages.storage.archive-purchaser', compact('users'));
    }

    public function searchUser($search)
    {
        $users = null;
        if (is_numeric($search)) {
            $users = User::where('role_id', 2)->
            where('phone_number', 'LIKE', "%$search%")->
            paginate($this->paginate);
        } else {
            $users = User::where('role_id', 2)->
            where('name', 'LIKE', "%$search%")->
            paginate($this->paginate);

        }
        return view('admin.pages.storage.archive-purchaser', compact('users', 'search'));
    }

    public function postSearchUser(Request $request)
    {
        if ($request->search == null)
            return redirect()->route('admin.archive.purchaser');
        return redirect()->route('admin.archive.user.search', ["search" => $request->search]);
    }

    public function searchSeller($search)
    {
        $sellers = null;
        if (is_numeric($search)) {
            $sellers = User::where('role_id', 3)->
            where('phone_number', 'LIKE', "%$search%")->
            paginate($this->paginate);
        } else {
            $sellers = User::where('role_id', 3)->
            where('name', 'LIKE', "%$search%")->
            paginate($this->paginate);

        }
        return view('admin.pages.storage.archive-seller', compact('sellers', 'search'));
    }

    public function postSearchSeller(Request $request)
    {
        if ($request->search == null)
            return redirect()->route('admin.archive.seller');
        return redirect()->route('admin.archive.seller.search', ["search" => $request->search]);
    }

    public function userAndSellerActive(Request $request)
    {
        $user = User::where('id', (int)$request->id)->first();
        if (!$user->is_active) {
            $user->is_active = true;
            $user->save();
            $archive = Archive::where('user_id', $user->id)->first();
//            if ($archive == null) {
//                $archive = new Archive();
//                $archive->user_id = $user->id;
//                $archive->role_id = $user->role_id;
//                $archive->save();
//            } else {
            $archive->is_active = false;
            $archive->save();

            return response()->json([
                'status' => true
            ]);
        }
        return response()->json([
            'status' => false
        ]);
    }
}
