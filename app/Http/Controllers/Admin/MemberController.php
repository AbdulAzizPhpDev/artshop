<?php

namespace App\Http\Controllers\Admin;

use App\Address;
use App\Archive;
use App\ExtraInfoUser;
use App\Helper\ImageMaker;
use App\Http\Controllers\Controller;
use App\Region;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use function GuzzleHttp\Promise\all;

use App\Catalog;

use App\Product;
use App\ProductImages;
use App\PaymentSystemTool;




class MemberController extends Controller
{

    protected $paginate = 5;

    public function usersIndex()
    {
        $users = User::where([
            ['role_id', 2],
            ['is_active', true]
        ])->paginate($this->paginate);
        return view('admin.pages.members.user.index', compact('users'));
    }

    public function editUser($lang, $id)
    {
        $user = User::where([
            ['role_id', 2],
            ['is_active', true],
            ['id', $id]
        ])->first();
        if ($user == null)
            return redirect()->route('admin.member.user.index', ['lang' => app()->getLocale()]);

        $new_phone = "";
        $phone = $user->phone_number;
        for ($i = 0, $iMax = strlen($phone); $i < $iMax; $i++) {

            if ($i == 3)
                $new_phone .= $phone[$i] . '-';
            elseif ($i == 5)
                $new_phone .= $phone[$i] . '-';
            elseif ($i == 8)
                $new_phone .= $phone[$i] . '-';
            elseif ($i == 10)
                $new_phone .= $phone[$i] . '-';
            else
                $new_phone .= $phone[$i];

        }


        return view('admin.pages.members.user.edit', compact('user', 'new_phone'))->withErrors(['success'=>'success']);;
    }

    public function updateUser(Request $request)
    {
//        dd($request->all());
        $request->validate([
            'name' => 'required|max:40|min:3',
            'user_name' => 'max:32|min:3',
            'phone_number' => 'required | max:13 ',
        ]);

        if ($request->user_name != null) {
//            if (strlen($request->user_name) <= 2) {
//                return redirect()->back()->withErrors(['user_name' => 'user name must be more than 2 characters'])->withInput();
//            }
            if (preg_match("/^\S.*\s.*\S$/", trim($request->user_name))) {
                return redirect()->back()->withErrors(['user_name' => ' white spaces is not allowed for user name'])->withInput();
            }
            $user_status = User::where([
                ['id', '!=', $request->id],
                ['user_name', trim($request->user_name)]
            ])->exists();
            if ($user_status) {
                return redirect()->back()->withErrors(['user_name' => 'Given user name exist, try another one '])->withInput();
            }
        }
        $pattern = "/^\+998\d{9}$/";
        if (preg_match($pattern, $request->phone_number)) {
            $user = User::where('id', $request->id)->first();
            if ($request->phone_number != $user->phone_number) {
                $item = User::where([
                    ['id', '!=', $request->id],
                    ['phone_number', $request->phone_number]
                ])->exists();
                if ($item)
                    return redirect()->back()->withErrors(['phone_number' => 'inserted phone number is exist, try another'])->withInput();
                $user->phone_number = $request->phone_number;
            }
            if ($request->user_name != null) {
                $user->user_name = trim($request->user_name);
            }
            $user->name = $request->name;
            $user->save();
            return redirect()->route('admin.member.user.index', ['lang' => app()->getLocale()])->withErrors([
                'success' => $user->name . ' profile updated successfully'
            ]);
        } else {
            return redirect()->back()->withErrors(['phone_number' => 'inserted phone number is wrong format'])->withInput();
        }

    }

    public function searchUser($lang, $search)
    {
        $users = null;
        if (is_numeric($search)) {
            $users = User::where([
                ['role_id', 2],
                ['is_active', true]
            ])->
            where('phone_number', 'LIKE', "%$search%")->
            paginate($this->paginate);
        } else {
            $users = User::where([
                ['role_id', 2],
                ['is_active', true]
            ])->
            where('name', 'LIKE', "%$search%")->
            paginate($this->paginate);

        }
        return view('admin.pages.members.user.index', compact('users', 'search'));
    }

    public function postSearchUser(Request $request)
    {
        if ($request->search == null)
            return redirect()->route('admin.member.user.index', ['lang' => app()->getLocale()]);
        return redirect()->route('admin.member.user.search', ["search" => $request->search, 'lang' => app()->getLocale()]);
    }

    public function userAndSellerInactive(Request $request)
    {
        $user = User::where('id', (int)$request->id)->first();
        if ($user->is_active) {
            $user->is_active = false;
            $user->save();
            $archive = Archive::where('user_id', $user->id)->first();
            if ($archive == null) {
                $archive = new Archive();
                $archive->user_id = $user->id;
                $archive->role_id = $user->role_id;
                $archive->save();
            } else {
                $archive->is_active = true;
                $archive->count = $archive->count + 1;
                $archive->save();
            }
            return response()->json([
                'status' => true
            ]);
        }
        return response()->json([
            'status' => false
        ]);
    }


//    Seller SellerSeller SellerSeller SellerSeller Seller Seller

//    public function sellerInactive(Request $request)
//    {
//        $user = User::where('id', $request->id)->first();
//        if ($user->status) {
//            $user->status = false;
//            $user->save();
//            return response()->json([
//                'status' => true
//            ]);
//        }
//        return response()->json([
//            'status' => false
//        ]);
//    }

    public function sellersIndex()
    {
        $sellers = User::where([
            ['role_id', 3],
            ['is_active', true]
        ])->paginate($this->paginate);

        return view('admin.pages.members.seller.index', compact('sellers'));
    }

    public function addSeller()
    {
        $regions = Region::where('parent_id', 0)->get();
        return view('admin.pages.members.seller.add', compact('regions'));
    }

    public function updateSeller($lang, $id)
    {
        $seller = User::where('id', $id)->with(['extraInfo', 'address'])->first();

        $new_office = '';
        $districts = null;
        if ($seller->extraInfo && strlen($seller->extraInfo->office_number) == 13) {
            $phone = $seller->extraInfo->office_number;
            for ($i = 0, $iMax = strlen($phone); $i < $iMax; $i++) {
                if ($i == 3)
                    $new_office .= $phone[$i] . '-';
                elseif ($i == 5)
                    $new_office .= $phone[$i] . '-';
                elseif ($i == 8)
                    $new_office .= $phone[$i] . '-';
                elseif ($i == 10)
                    $new_office .= $phone[$i] . '-';
                else
                    $new_office .= $phone[$i];

            }
        }
        $regions = Region::where('parent_id', 0)->get();
        if ($seller->address != null) {
            $districts = Region::where([
                ['parent_id', $seller->address->region_id],
            ])->orderBy('name_uz')->get();
        }

        return view('admin.pages.members.seller.edit', compact('seller', 'new_office', 'regions', 'districts'))->withErrors(['success'=>'success']);
    }

    public function searchSeller($lang,$search)
    {
        $sellers = null;
        if (is_numeric($search)) {
            $sellers = User::where([
                ['role_id', 3],
                ['is_active',true]
            ])->
            where('phone_number', 'LIKE', "%$search%")->
            paginate($this->paginate);
        } else {
            $sellers = User::where(
                [
                    ['role_id', 3],
                    ['is_active',true]
                ]
            )->
            where('name', 'LIKE', "%$search%")->
            paginate($this->paginate);

        }
        return view('admin.pages.members.seller.index', compact('sellers', 'search'));
    }

    public function postSearchSeller(Request $request)
    {
        if ($request->search == null)
            return redirect()->route('admin.member.seller.index',app()->getLocale());
        return redirect()->route('admin.member.seller.search', ["search" => $request->search,"lang"=>app()->getLocale()]);
    }

//       ADMIN ADMIN ADMIN ADMINADMINADMINADMINADMINADMIN

    public function adminsIndex()
    {
        $admins = User::whereIn('role_id', [1, 4])->
        where('id', '!=', auth()->user()->id)->
        orderBy('role_id')->
        paginate($this->paginate);
        return view('admin.pages.members.admin.index', compact('admins'));
    }


    public function addAdmin()
    {
        return view('admin.pages.members.admin.add');
    }

    public function updateAdmin($lang, $id)
    {
        $admin = User::where('id', $id)->first();
        return view('admin.pages.members.admin.edit', compact('admin'));
    }

    public function searchAdmin($lang, $search)
    {
        $admins = null;
        if (is_numeric($search)) {
            $admins = User::whereIn('role_id', [1, 4])->
            where('id', '!=', auth()->user()->id)->
            where('phone_number', 'LIKE', "%$search%")->
            paginate($this->paginate);
        } else {
            $admins = User::whereIn('role_id', [1, 4])->
            where('id', '!=', auth()->user()->id)->
            where('name', 'LIKE', "%$search%")->
            paginate($this->paginate);

        }
        return view('admin.pages.members.admin.index', compact('admins', 'search'));
    }

    public function searchAdminWithRole($lang, $role_id, $search)
    {
        $admins = null;

        if (is_numeric($search)) {
            $admins = User::where('role_id', $role_id)->
            where('id', '!=', auth()->user()->id)->
            where('phone_number', 'LIKE', "%$search%")->
            paginate($this->paginate);
        } else {
            $admins = User::where('role_id', $role_id)->
            where('id', '!=', auth()->user()->id)->
            where('name', 'LIKE', "%$search%")->
            paginate($this->paginate);

        }

        return view('admin.pages.members.admin.index', compact('admins', 'search', 'role_id'));
    }

    public function searchRole($lang, $role_id)
    {
        $admins = User::where('role_id', $role_id)->
        where('id', '!=', auth()->user()->id)->
        paginate($this->paginate);
        return view('admin.pages.members.admin.index', compact('admins', 'role_id'));

    }

    public function postSearchAdmin(Request $request)
    {
        if ($request->search == null && $request->role == 0) {
            return redirect()->route('admin.member.admin.index', app()->getLocale());
        } elseif ($request->search != null && $request->role == 0) {
            return redirect()->route('admin.member.admin.search', ["search" => $request->search, "lang" => app()->getLocale()]);
        } elseif ($request->search == null && $request->role != 0) {
            return redirect()->route('admin.member.admin.role', ["role_id" => $request->role, "lang" => app()->getLocale()]);
        }
        return redirect()->route('admin.member.admin.search.role', ["search" => $request->search, "role_id" => $request->role, "lang" => app()->getLocale()]);

    }


    public function store(Request $request)
    {
        if ($request->role == 1 || $request->role == 4) {
            if ($request->id == 0) {
                $request->validate(
                    [
                        'name' => 'required|max:128|min:3',
                        'user_name' => 'required|max:32|min:3|unique:users',
                        'password' => 'required|max:32|min:8',

                    ]);
            } else {
                $request->validate(
                    [
                        'name' => 'required|max:128|min:3',
                        'role' => 'required'
                    ]);
            }

            $member = $request->id != 0 ? User::findOrFail($request->get('id')) : new User();
            if ($request->user_name != null) {
                if ($member->user_name != $request->user_name) {

                    if (!preg_match("/^[a-zA-Z]{1}/", trim($request->user_name))) {
                        return redirect()->back()->withErrors(['user_name' => 'First char must be word'])->withInput();
                    }
                    if (preg_match("/^\S.*\s.*\S$/", trim($request->user_name))) {
                        return redirect()->back()->withErrors(['user_name' => ' white spaces is not allowed for user name'])->withInput();
                    }
                }
            }
            if ($request->id != 0) {
                if ($member->user_name != $request->user_name) {
                    $request->validate([
                        'user_name' => 'required|max:32|min:3|unique:users',
                    ]);
                    $member->user_name = $request->user_name;
                }
            } else {
                $member->user_name = $request->user_name;
                $member->is_first = false;

            }
            $member->name = $request->name;
            $member->role_id = $request->role;
            if ($request->id == 0) {
                $member->password = Hash::make($request->password);
            } else {
                if (!empty($request->password)) {
                    $request->validate([
                        'password' => 'required | max:32 | min:8',
                    ]);
                    $member->password = Hash::make($request->password);
                }
            }

            $member->save();
            return redirect()->route('admin.member.admin.index', app()->getLocale())->withErrors(['success'=>'success']);


        } elseif ($request->role == 3) {

            $request->validate([
                'user_name' => 'required | max:32 | min:3 ',
            ]);

            $seller = $request->id != 0 ? User::findOrFail($request->get('id')) : new User();

            if ($request->user_name != null) {
                if ($seller->user_name != $request->user_name) {

                    if (!preg_match("/^[a-zA-Z]{1}/", trim($request->user_name))) {
                        return redirect()->back()->withErrors(['user_name' => 'First char must be word'])->withInput();
                    }
                    if (preg_match("/^\S.*\s.*\S$/", trim($request->user_name))) {
                        return redirect()->back()->withErrors(['user_name' => ' white spaces is not allowed for user name'])->withInput();
                    }
                    if (strlen($request->user_name) <= 2) {
                        return redirect()->back()->withErrors(['user_name' => 'user name must be more than 2 characters'])->withInput();
                    }
                    $user_status = User::where([
                        ['id', '!=', Auth::user()->id],
                        ['user_name', trim($request->user_name)]
                    ])->exists();
                    if ($user_status) {
                        return redirect()->back()->withErrors(['user_name' => 'Given user name exist, try another one '])->withInput();
                    }
                    $seller->user_name = trim($request->user_name);
                }
            }

            if (!empty($request->password) && $request->id == 0) {
                if (strlen($request->password) <= 8)
                    return back()->withInput()->withErrors([
                        'password' => 'password must be greater than 8 char'
                    ]);
                elseif (strlen($request->password) >= 24)
                    return back()->withInput()->withErrors([
                        'password' => 'password must be lower than 24 char'
                    ]);
                elseif (strlen($request->password) >= 8 && strlen($request->password) <= 24)
                    $seller->password = Hash::make($request->password);

            } elseif (!empty($request->password) && $request->id != 0) {
                if (strlen($request->password) < 8)
                    return back()->withInput()->withErrors([
                        'password' => 'password must be greater than 8 char'
                    ]);
                elseif (strlen($request->password) >= 24)
                    return back()->withInput()->withErrors([
                        'password' => 'password must be lower than 24 char'
                    ]);
                elseif (strlen($request->password) >= 8 && strlen($request->password) <= 24)

                    $seller->password = Hash::make($request->password);
            } elseif (empty($request->password) && $request->id == 0) {
                return back()->withInput()->withErrors([
                    'password' => 'password must be inserted'
                ]);
            }

            $seller->role_id = 3;
            if ($request->id == 0) {
                $seller->is_active = true;
            }
            $seller->is_first = true;
            if (!empty($request->name)) {
                if (strlen($request->name) >= 3) {
                    $seller->name = $request->name;
                } else {
                    return back()->withInput()->withErrors([
                        'name' => 'insert at lest 3 chars'
                    ]);
                }
            } else {
                $seller->name = 'temporary name ' . preg_replace(' / \./', '', microtime(true));
            }

            $seller->save();

            $paymen = new PaymentSystemTool();
            $paymen->is_cash = true;
            $paymen->is_active = false;
            $paymen->seller_id = $seller->id;
            $paymen->save();

            if ($request->id == 0 && ((isset($request->images)) || (!empty($request->extra['stir']))  || (!empty($request->extra['description'])))) {

                $request->validate([
                    "images.order" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
                    "images.passport" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
                    "images.logo" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
                    "images.license" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
                ]);
            } elseif ($request->id != 0 && ((isset($request->images)) || (!empty($request->extra['stir']))  || (!empty($request->extra['description'])))) {

                $request->validate([
                    "images.order" => "image|mimes:jpeg,png,jpg,gif,svg|max:2048",
                    "images.passport" => "image|mimes:jpeg,png,jpg,gif,svg|max:2048",
                    "images.logo" => "image|mimes:jpeg,png,jpg,gif,svg|max:2048",
                    "images.license" => "image|mimes:jpeg,png,jpg,gif,svg|max:2048",
                ]);

            }

            if (isset($request->images)) {
                $check_info = ExtraInfoUser::where('seller_id', $seller->id)->exists();
                $seller_image = ($request->id != 0) && ($check_info) ? ExtraInfoUser::where('seller_id', $seller->id)->first() : new ExtraInfoUser();

                if (!empty($request->office_number) && strlen($request->office_number) == 13) {
                    $request->validate([
                        'office_number' => 'required | max:13',
                    ]);
                    $pattern = "/^\+998\d{9}$/";
                    if (preg_match($pattern, $request->office_number)) {
                        $seller_image->office_number = $request->office_number;
                    } else {
                        return back()->withInput()->withErrors([
                            'office_number' => 'inserted phone number is wrong format'
                        ]);
                    }
                }
                $imageTypeArray = array
                (
                    2 => 'jpg',
                    3 => 'png',
                );

                foreach ($request->images as $image => $key) {
                    if (isset($request->delete)) {
                        if (Storage::disk('public')->exists(explode('/', $request->delete[$image], 3)[2])) {
                            Storage::disk('public')->delete(explode('/', $request->delete[$image], 3)[2]);
                        }
                    }
                    list($width, $height, $type) = getimagesize($key);
                    $path = "seller/images/" . $seller->id . "/";
                    $name = $image . preg_replace(' / \./', '', microtime(true)) . '.' . $imageTypeArray[$type];
                    $col_name = 'image_' . $image;

                    $seller_image->$col_name = (new ImageMaker($width, $height))->makeImage($key, $path, $name);
                }
                $seller_image->seller_id = $seller->id;
                $seller_image->description = $request->extra['description'];
                $seller_image->save();
            }
            if (isset($request->address['district']) && !is_null($request->address['region'])) {
                $address = ($request->id != 0 && Address::where('user_id', $seller->id)->exists()) ? Address::where('user_id', $seller->id)->first() : new Address();
                $address->user_id = $seller->id;
                $address->role_id = 3;
                $address->street = $request->address['street'];
                $address->house = $request->address['house'];
                $address->district_id = $request->address['district'];
                $address->region_id = $request->address['region'];
                $address->save();
            }


            return redirect()->route('admin.member.seller.index', ['lang' => app()->getLocale()])->withErrors(['success'=>'success']);

        }
        return redirect()->back()->withErrors([
            'role' => 'choose the role'
        ])->withInput();

    }


    public function destroyAdmin(Request $request)
    {
        User::where(
            [
                ['id', $request->id],

            ]
        )->delete();
        return response()->json([
            'status' => true
        ]);
    }

    public function destroySeller(Request $request)
    {


//        $product = Product::where('merchant_id', $request->id)->get();
//
//        $images = ProductImages::where('product_id', $request->id)->get();
//        if (Storage::disk('public')->exists($product->image)) {
//            Storage::disk('public')->delete($product->image);
//        }
//        if (!empty($images)) {
//            foreach ($images as $image) {
//                if (Storage::disk('public')->exists($image)) {
//                    Storage::disk('public')->delete($image);
//                }
//            }
//            ProductImages::where('product_id', $request->id)->delete();
//        }
//        $check = $product->delete();

        User::where(
            [
                ['id', $request->id],
                ['role_id', 3]

            ]
        )->delete();

    }

    public function destroyUser(Request $request)
    {
        User::where(
            [
                ['id', $request->id],
                ['role_id', 2]
            ]
        )->delete();
    }
}
