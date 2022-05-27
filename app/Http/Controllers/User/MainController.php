<?php

namespace App\Http\Controllers\User;

use App\Catalog;
use App\DeliveryPriceTable;
use App\Http\Controllers\Controller;
use App\Order;
use App\Product;
use App\Region;
use App\User;
use App\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MainController extends Controller
{
    public function index()
    {
        $catalog_page = 7;
        $product_page = 8;
        $catalogs = Catalog::where([
            ['parent_id', 0],
            ['is_active', true]
        ])->take($catalog_page)->get();
        $populars = Product::where([
            ['is_active', true],
            ['is_popular', true],
        ])->with(['wishList'])->take($product_page)->get();
        $products = Product::where([
            ['is_active', true]
        ])->with(['wishList'])->get();

        $news = Product::where([
            ['is_active', true],
            ['is_new', true],
        ])->with(['wishList'])->take($product_page)->get();


        return view('user.pages.index', compact('catalogs', 'populars', 'products', 'news', 'catalog_page', 'product_page'));
    }

    public function about()
    {
        return view('user.pages.about');
    }

    public function help()
    {
        return view('user.pages.help');
    }

    public function checkout($lang, $merchant_id)
    {

        if (!Session::has('cart')) {
            return redirect()->route('user.cart', app()->getLocale());
        }
        if (!array_key_exists($merchant_id, Session::get('cart')['manufacturers']))
            return redirect()->route('user.cart', app()->getLocale());
        $new_office = '';
        $user = null;
        $regions = null;
        if (auth()->check()) {
            $user = User::where([
                ['id', auth()->user()->id],
                ['role_id', 2]
            ])->with(['extraInfo'])->first();

            if (strlen($user->phone_number) == 13) {
                $phone = $user->phone_number;
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
        }
        $data = Session::get('cart')['manufacturers'][$merchant_id];

        $merchant = User::where([
            ['id', $data['manufacturer']->id],
        ])->with([
            'paymentSystem',
            'requisite'
        ])->first();

        $price_ids = DeliveryPriceTable::where([
            ['seller_id', $data['manufacturer']->id],
            ['is_active', true]
        ])->get()->pluck('region_id')->toArray();

        if (!empty($price_ids)) {
            $regions = Region::whereIn('id', $price_ids)->get();
        }

        return view('user.pages.checkout', compact('data', 'regions', 'new_office', 'user', 'merchant'));
    }


    public function buyCheckout($lang, $product_id)
    {


        $new_office = '';
        $user = null;
        $regions = null;
        $product = Product::where('id', $product_id)->first();
        if (auth()->check()) {

            $user = User::where([
                ['id', auth()->user()->id],
                ['role_id', 2]
            ])->with(['extraInfo'])->first();

            if (strlen($user->phone_number) == 13) {
                $phone = $user->phone_number;
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
        }
//        $data = Session::get('cart')['manufacturers'][$merchant_id];

        $merchant = User::where([
            ['id', $product->merchant_id],
        ])->with([
            'paymentSystem',
            'requisite'
        ])->first();

        $price_ids = DeliveryPriceTable::where([
            ['seller_id', $product->merchant_id],
            ['is_active', true]
        ])->get()->pluck('region_id')->toArray();

        if (!empty($price_ids)) {
            $regions = Region::whereIn('id', $price_ids)->get();
        }

        return view('user.pages.checkout-pages.buy_checkout', compact('product','regions', 'new_office', 'user', 'merchant'));
    }

    public function addWishList(Request $request)
    {
        if (auth()->check()) {

            $wish_list_status = Wishlist::where('user_id', auth()->id())->
            where('product_id', $request->wish_list_id)->
            exists();

            if ($wish_list_status) {
                Wishlist::where('user_id', auth()->id())->
                where('product_id', $request->wish_list_id)->
                delete();

            } else {
                $wish_list = new Wishlist;
                $wish_list->product_id = $request->wish_list_id;
                $wish_list->user_id = auth()->id();
                $wish_list->save();
            }
            return response()->json(['status' => !$wish_list_status]);
        }
        return response()->json(['status' => false]);
    }

    public function removeWishList(Request $request)
    {
        $wish_list_status = Wishlist::where('user_id', auth()->id())->
        where('product_id', $request->wish_list_id)->
        exists();
        Wishlist::where('user_id', auth()->id())->
        where('product_id', $request->wish_list_id)->
        delete();
        return response()->json(['id' => $request->wish_list_id]);
    }


    public function wishList()
    {
        if (auth()->check()) {

            $products = Wishlist::where('user_id', Auth::user()->id)->with('getProduct')->get();
            return view('user.pages.wish_list', compact('products'));
        }

        return redirect()->route('user.login', app()->getLocale());
    }

    public function invoice()
    {
        if (!auth()->check()) {
            return redirect()->route('user.cart');
        }
        $order = Order::where('user_id', auth()->user()->id)->with(['mertchant', 'orderer'])->latest()->first();
        return view('user.pages.checkout-pages.invoice', compact('order'));
    }

    public function getCatalogPrePage(Request $request)
    {
        $catalogs = Catalog::where([
            ['parent_id', 0],
            ['is_active', true]
        ])->skip($request->number)->take(8)->get();
        return response()->json([
            "number" => 8,
            "catalogs" => $catalogs
        ]);
    }

    public function getProductPrePage(Request $request)
    {

        if ($request->state == 'popular') {
            $populars = Product::where([
                ['is_active', true],
                ['is_popular', true],
            ])->with(['wishList'])->skip($request->number)->take(2)->get();

            return response()->json([
                "number" => 8,
                "product" => $populars,
                "state" => "popular"
            ]);
        } else {
            $populars = Product::where([
                ['is_active', true],
                ['is_new', true],
            ])->with(['wishList'])->skip($request->number)->take(8)->get();

            return response()->json([
                "number" => 8,
                "product" => $populars,
                "state" => "new"
            ]);
        }

    }


}
