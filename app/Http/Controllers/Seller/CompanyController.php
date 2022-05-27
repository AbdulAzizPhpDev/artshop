<?php

namespace App\Http\Controllers\Seller;

use App\Address;
use App\ExtraInfoUser;
use App\Helper\ImageMaker;
use App\Http\Controllers\Controller;
use App\Order;
use App\Product;
use App\Region;
use App\Requisite;
use App\User;
use App\CustomerRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class CompanyController extends Controller
{
    public function dashboard()
    {
        $products = Product::where('merchant_id', Auth::user()->id)->get();
        $product = $products->count();
        $product_ids = $products->pluck('id')->toArray();
        $rating = CustomerRating::whereIn('product_id',$product_ids)->count('rate');

        $order = Order::where('merchant_id', Auth::user()->id)->count();
        $order = Order::where('merchant_id', Auth::user()->id)->count();
        $order_list = Order::where([
            ['merchant_id', Auth::user()->id],
            ['is_active', true]
        ])->with(['orderer', 'orderList'])->get();
        return view('seller.pages.dashboard', compact('product', 'order', 'order_list','rating'));
    }

    public function about()
    {

        $seller = User::where([
            ['id', Auth::user()->id]
        ])->with(['extraInfo', 'address'])->first();
        $new_phone = "";
        $new_office = "";
        $districts = null;
        if (strlen($seller->phone_number) == 13) {
            $phone = $seller->phone_number;
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
        }

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
        return view('seller.pages.company.about', compact('seller', 'new_phone', 'regions', 'new_office', 'districts'));

    }

    public function storeInformations(Request $request)
    {
        $imageTypeArray = array
        (
            2 => 'jpg',
            3 => 'png',
        );

        $request->validate([
            'phone_number' => 'max:13',
            'password' => 'max:24',
            'user_name' => 'max:32',
            'name' => 'min:3|max:128'
        ]);

        $seller = User::where([
            ['id', Auth::user()->id]
        ])->with(['extraInfo'])->first();

        if (!is_null($request->name) && $request->name != $seller->name) {
            $seller->name = $request->name;
        }
        if (!is_null($request->phone_number) && $request->phone_number != $seller->phone_number) {
            $pattern = "/^\+998\d{9}$/";
            if (preg_match($pattern, $request->phone_number)) {
                $phone_exist = User::where([
                    ['id', '!=', Auth::user()->id],
                    ['phone_number', $request->phone_number]
                ])->exists();
                if ($phone_exist) {
                    return back()->withInput()->withErrors([
                        'phone_number' => '(' . $request->phone_format . ') is already taken'
                    ]);
                }
                $seller->phone_number = $request->phone_number;
            } else {
                return back()->withInput()->withErrors([
                    'phone_number' => '(' . $request->phone_format . ') is wrong format'
                ]);
            }
        }
        if (!empty($request->password)) {
            if (strlen($request->password) < 8)
                return back()->withInput()->withErrors([
                    'password' => 'password must be greater than 8 char'
                ]);
            elseif (strlen($request->password) > 24)
                return back()->withInput()->withErrors([
                    'password' => 'password must be lower than 24 char'
                ]);
            elseif (strlen($request->password) >= 8 && strlen($request->password) <= 24)
                $seller->password = Hash::make($request->password);
        }
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
        $seller->save();
        if ($seller->extraInfo == null && ((isset($request->images)) || (!empty($request->extra['stir'])) || (!empty($request->extra['ownership'])) || (!empty($request->extra['description'])))) {
            $request->validate([
                "extra.office_number" => "max:13",
                "extra.description" => "max:256|min:30",
                "images.order" => "required|image|mimes:jpeg,png,jpg|max:10048|dimensions:max_width=3000,max_height=2000",
                "images.passport" => "required|image|mimes:jpeg,png,jpg|max:10048|dimensions:max_width=3000,max_height=2000",
                "images.logo" => "required|image|mimes:jpeg,png,jpg|max:10048|dimensions:max_width=3000,max_height=2000",
                "images.license" => "required|image|mimes:jpeg,png,jpg|max:10048|dimensions:max_width=3000,max_height=2000",
            ]);
        } else {
            $request->validate([
                "extra.office_number" => "max:13",
                "extra.description" => "max:256|min:30",
                "images.order" => "image|mimes:jpeg,png,jpg|max:10048|dimensions:max_width=3000,max_height=2000",
                "images.passport" => "image|mimes:jpeg,png,jpg|max:10048|dimensions:max_width=3000,max_height=2000",
                "images.logo" => "image|mimes:jpeg,png,jpg|max:10048|dimensions:max_width=3000,max_height=2000",
                "images.license" => "image|mimes:jpeg,png,jpg|max:10048|dimensions:max_width=3000,max_height=2000",
            ]);
        }
        $seller_image = $seller->extraInfo == null ? new ExtraInfoUser() : ExtraInfoUser::where('seller_id', $seller->id)->first();

        if (isset($request->images)) {
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

            $seller = User::where([
                ['id', Auth::user()->id]
            ])->first();

            $seller->is_first = false;
            $seller->save();

        }
        $pattern = "/^\+998\d{9}$/";
        if (preg_match($pattern, $request->extra['office_number'])) {
            $seller_image->office_number = $request->extra['office_number'];
        } else {
            return back()->withInput()->withErrors([
                'office_number' => '(' . $request->extra['office_format'] . ') is wrong format'
            ]);
        }
        $seller_image->seller_id = $seller->id;
        $seller_image->description = $request->extra['description'];
        $seller_image->save();


        if (isset($request->address)) {
            $request->validate([
                "address.region" => "required",
                "address.district" => "required",
                "address.street" => "max:64|min:3",
                "address.house" => "max:32",
            ]);
            $address = $seller->address == null ? new Address() : Address::where([
                ['user_id', $seller->id],
                ['role_id', $seller->role_id]
            ])->first();
            $address->region_id = $request->address['region'];
            $address->district_id = $request->address['district'];
            $address->street = $request->address['street'];
            $address->house = $request->address['house'];
            $address->user_id = $seller->id;
            $address->role_id = $seller->role_id;
            $address->address_type = 1;
            $address->x_coordinate = $request->address['x_coordinate'];
            $address->y_coordinate = $request->address['y_coordinate'];
            $address->save();
        }

        $seller = User::where([
            ['id', Auth::user()->id]
        ])->first();

        $seller->is_first = false;
        $seller->save();
        return back()->withErrors(['success'=>'success']);
    }

    public function requisite()
    {
        $requisites = Requisite::where('seller_id', Auth::user()->id)->first();
        return view('seller.pages.company.requisites', compact('requisites'));
    }

    public function storeRequisites(Request $request)
    {
        $request->validate([
            "official_name" => "required|min:3|max:64",
            "ownership" => "required",
            "stir" => "required|max:9",
            "activity" => "required",
            "first_name" => "required|max:64",
            "last_name" => "required|max:64",
            "meddle_name" => "required|max:64",
            "bank_account" => "required|max:64",
            "bank_info" => "required|max:64",
            "bank_name" => "required|max:64",
            "bank_account2" => "max:64",
            "bank_info2" => "max:64",
            "bank_name2" => "max:64",
        ]);
        $requisite = ($request->id == 0) ? new Requisite() : Requisite::where('id', $request->id)->first();

        $requisite->seller_id = Auth::user()->id;
        $requisite->official_name = $request->official_name;
        $requisite->ownership = $request->ownership;
        $requisite->stir = $request->stir;
        $requisite->activity = $request->activity;
        $requisite->first_name = $request->first_name;
        $requisite->last_name = $request->last_name;
        $requisite->meddle_name = $request->meddle_name;
        $requisite->bank_account = $request->bank_account;
        $requisite->bank_info = $request->bank_info;
        $requisite->bank_name = $request->bank_name;
        $requisite->bank_account2 = $request->bank_account2;
        $requisite->bank_info2 = $request->bank_info2;
        $requisite->bank_name2 = $request->bank_name2;
        $requisite->save();
        return back()->withErrors(['success'=>'success']);

    }

    public function review()
    {
       $reviews =  CustomerRating::where('seller_id', auth()->user()->id)->with(['product'])->get();
        return view('seller.pages.reviews',compact('reviews'));
    }
}
