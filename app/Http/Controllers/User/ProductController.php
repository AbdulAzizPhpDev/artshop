<?php

namespace App\Http\Controllers\User;

use App\CustomerRating;
use App\Http\Controllers\Controller;
use App\MostViewedProduct;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index($lang, $id)
    {


        $product = Product::where('id', $id)->with(['category', 'merchant', 'reviews'])->first();
        if (Auth::check() && Auth::user()->role_id == 2) {
            $viewed = MostViewedProduct::where('user_id', Auth::user()->getAuthIdentifier())->get();
            $ids = $viewed->pluck('product_id')->toArray();
            if (count($viewed) == 0) {
                $viewed_product = new MostViewedProduct();

                $viewed_product->user_id = Auth::user()->getAuthIdentifier();
                $viewed_product->product_id = $product->id;
                $viewed_product->save();
            } elseif (count($viewed) < 10) {
                if (!in_array($product->id, $ids)) {
                    $viewed_product = new MostViewedProduct();

                    $viewed_product->user_id = Auth::user()->getAuthIdentifier();
                    $viewed_product->product_id = $product->id;
                    $viewed_product->save();
                }
            } elseif (count($viewed) >= 10) {
                if (!in_array($product->id, $ids)) {
                    MostViewedProduct::where('user_id', Auth::user()->getAuthIdentifier())->first()->delete();
                    $viewed_product = new MostViewedProduct();

                    $viewed_product->user_id = Auth::user()->getAuthIdentifier();
                    $viewed_product->product_id = $product->id;
                    $viewed_product->save();
                }


            }

        }
        $average = round($product->reviews->avg('rate'));
        return view('user.pages.product_view', compact('product', 'average'));
    }

    public function addReview(Request $request)
    {
        if (Auth::check()) {
            $status = CustomerRating::where([
                ['user_id', Auth::user()->id],
                ['product_id', $request->id]
            ])->exists();
            if ($status) {
                return response()->json([
                    'status' => 'exist'
                ]);
            } else {
                $rate = new CustomerRating;
                $rate->product_id = $request->id;
                $rate->user_id = Auth::user()->id;
                $rate->rate = $request->star;
                $rate->seller_id = $request->seller_id;
                $rate->comment = $request->review;
                $rate->save();
                return response()->json([
                    'user_name' => Auth::user()->name,
                    'rate' => $rate,
                    'status' => 'success'
                ]);
            }
        } else {
            return redirect()->route('user.login');
        }
    }

    public function getContactInfo(Request $request)
    {
        $product = Product::where('id', $request->product_id)->with(['merchant'])->first();
        $phone = null;
        $email = null;
        if (!is_null($product->merchant->phone_number)) {
            $phone = $product->merchant->phone_number;
        }

        return response()->json([
            'phone' => $phone,
            'name' => $product->merchant->name,
            'email' => $email
        ]);

    }

    public function searchProducts(Request $request)
    {
        $products = null;
        if (preg_match('/[А-Яа-яЁё]/u', $request->search)) {
            $products = Product::where('is_active', true)->where('name_ru', 'LIKE', "%$request->search%")->orderBy('name_ru')->take(5)->get();
        } else {
            $products = Product::where('is_active', true)->where('name_uz', 'LIKE', "%$request->search%")->orderBy('name_uz')->take(5)->get();

        }

        return response()->json([
            "products" => $products
        ]);
    }

}
