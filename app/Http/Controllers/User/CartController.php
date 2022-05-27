<?php

namespace App\Http\Controllers\User;

use App\Catalog;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function cart(Request $request)
    {
        $catalogs = Catalog::where([
            ['is_active', true],
            ['parent_id', 0]
        ])->with(['children'])->get();
        return view('user.pages.cart', compact('catalogs'));
    }


    public function cartDeleteProduct(Request $request)
    {

        $status = null;

        $product = Product::where('id', $request->id)->with(['merchant', 'category'])->first();

        $data = Session::get('cart');

        $data['total_products'] = (int)$data['total_products'] - (int)$data['manufacturers'][$product->merchant_id]['products'][$product->id]['total_quantity'];
        $data['total_price'] = (int)$data['total_price'] - (int)$data['manufacturers'][$product->merchant_id]['products'][$product->id]['total_price'];

        $data['manufacturers'][$product->merchant_id]['total_product_quantity'] -= (int)$data['manufacturers'][$product->merchant_id]['products'][$product->id]['total_quantity'];
        $data['manufacturers'][$product->merchant_id]['total_product_price'] -= (int)$data['manufacturers'][$product->merchant_id]['products'][$product->id]['total_price'];
        $merchant_quantity = $data['manufacturers'][$product->merchant_id]['total_product_quantity'];
        $merchant_price = $data['manufacturers'][$product->merchant_id]['total_product_price'];
        unset($data['manufacturers'][$product->merchant_id]['products'][$product->id]);
        $status = 'delete_p';
        if (count($data['manufacturers'][$product->merchant_id]['products']) == 0) {
            unset($data['manufacturers'][$product->merchant_id]);
            $status = 'delete_m';
        }
        Session::put('cart', $data);
        if (count($data['manufacturers']) == 0) {
            Session::forget('cart');
            $status = 'refresh';
        }


        return response()->json([
            'status' => $status,
            'total_products' => (int)$data['total_products'],
            'total_price' => (int)$data['total_price'],
            'total_merchant_quantity' => $merchant_quantity,
            'total_merchant_price' => $merchant_price,
            'merchant_id' => $product->merchant->id,
            'product_id' => $product->id
        ]);

    }

    public function cartAdd(Request $request)
    {


        if (Session::has('cart')) {
            $data = Session::get('cart');
            $product = Product::where('id', $request->id)->with(['merchant', 'category'])->first();
            $quantity = $product->quantity;
            if (empty($product)) {
                return response()->json([
                    'status' => false,

                ]);
            }
            if (array_key_exists($product->merchant_id, $data['manufacturers'])) {

                if (array_key_exists($product->id, $data['manufacturers'][$product->merchant_id]['products'])) {
                    $quantity2 = $data['manufacturers'][$product->merchant_id]['products'][$product->id]['total_quantity'];
                    if (($quantity2 + $request->quantity) > $quantity) {
                        return response()->json([
                            'status' => false,

                        ]);
                    }
                    $data['manufacturers'][$product->merchant_id]['products'][$product->id]['total_quantity'] += $request->quantity;
                    $data['manufacturers'][$product->merchant_id]['products'][$product->id]['total_price'] += $product->price * $request->quantity;
                    $data['manufacturers'][$product->merchant_id]['total_product_quantity'] += $request->quantity;
                    $data['manufacturers'][$product->merchant_id]['total_product_price'] += $product->price * $request->quantity;

                    $data['total_products'] = (int)$data['total_products'] + $request->quantity;
                    $data['total_price'] = (int)$data['total_price'] + $request->quantity * $product->price;
                    Session::put('cart', $data);
                    return response()->json([
                        'status' => true,

                    ]);

                } else {

                    if (($request->quantity) > $quantity) {
                        return response()->json([
                            'status' => false,

                        ]);
                    }

                    $store_item = ['total_quantity' => $request->quantity, 'total_price' => $product->price * $request->quantity, 'product' => $product];
                    $data['manufacturers'][$product->merchant_id]['products'][$product->id] = $store_item;
                    $data['manufacturers'][$product->merchant_id]['total_product_quantity'] += $request->quantity;
                    $data['manufacturers'][$product->merchant_id]['total_product_price'] += $product->price * $request->quantity;

                    $data['total_products'] = (int)$data['total_products'] + $request->quantity;
                    $data['total_price'] = (int)$data['total_price'] + $request->quantity * $product->price;
                    Session::put('cart', $data);
                    return response()->json([
                        'status' => true,

                    ]);
                }
            } else {
                if (($request->quantity) > $quantity) {
                    return response()->json([
                        'status' => false,

                    ]);
                }
                $store_product = ['total_product_quantity' => $request->quantity, 'total_product_price' => $product->price * $request->quantity, 'products' => null, 'manufacturer' => $product->merchant];
                $store_item = ['total_quantity' => $request->quantity, 'total_price' => $product->price * $request->quantity, 'product' => $product];
                $store_product['products'][$product->id] = $store_item;
                $data['manufacturers'][$product->merchant_id] = $store_product;
                $data['total_products'] = (int)$data['total_products'] + $request->quantity;
                $data['total_price'] = (int)$data['total_price'] + $request->quantity * $product->price;
                Session::put('cart', $data);
                return response()->json([
                    'status' => true,
                ]);
            }

        } else {
            $product = Product::where('id', $request->id)->with(['merchant', 'category'])->first();
            if (empty($product)) {
                return response()->json([
                    'status' => false,
                ]);
            }
            if (($request->quantity) > $product->quantity) {
                return response()->json([
                    'status' => false,

                ]);
            }
            $store_manufacturer = ['total_products' => $request->quantity, 'total_price' => $product->price * $request->quantity, 'manufacturers' => null];
            $store_product = ['total_product_quantity' => $request->quantity, 'total_product_price' => $product->price * $request->quantity, 'products' => null, 'manufacturer' => $product->merchant];
            $store_item = ['total_quantity' => $request->quantity, 'total_price' => $product->price * $request->quantity, 'product' => $product];
            $store_manufacturer['manufacturers'][$product->merchant_id] = $store_product;
            $store_manufacturer['manufacturers'][$product->merchant_id]['products'][$product->id] = $store_item;
            Session::put('cart', $store_manufacturer);
            return response()->json([
                'status' => true,
            ]);
        }
    }

    static public function cartChangeQuantity(Request $request)
    {

        if (Session::has('cart')) {
            $data = Session::get('cart');
            $product = Product::where('id', $request->id)->with(['merchant', 'category'])->first();


            $quantity2 = $data['manufacturers'][$product->merchant_id]['products'][$product->id]['total_quantity'];
            if (($quantity2 + $request->quantity) > $product->quantity) {
                return response()->json([
                    'status' => false,
                    'total_product_quantity' => (int)$data['manufacturers'][$product->merchant_id]['products'][$product->id]['total_quantity'],

                ]);
            }
            //change total products number
            $data['total_products'] = (int)$data['total_products'] - (int)$data['manufacturers'][$product->merchant_id]['products'][$product->id]['total_quantity'];
            $data['total_products'] = (int)$data['total_products'] + (int)$request->quantity;
            //change total price
            $data['total_price'] = (int)$data['total_price'] - (int)$data['manufacturers'][$product->merchant_id]['products'][$product->id]['total_price'];
            $data['total_price'] = (int)$data['total_price'] + (int)$request->quantity * $product->price;
            //change manufacturer's total product  number
            $data['manufacturers'][$product->merchant_id]['total_product_quantity'] -= (int)$data['manufacturers'][$product->merchant_id]['products'][$product->id]['total_quantity'];
            $data['manufacturers'][$product->merchant_id]['total_product_quantity'] += (int)$request->quantity;
            //change manufacturer's total product  price
            $data['manufacturers'][$product->merchant_id]['total_product_price'] -= (int)$data['manufacturers'][$product->merchant_id]['products'][$product->id]['total_price'];
            $data['manufacturers'][$product->merchant_id]['total_product_price'] += (int)$product->price * $request->quantity;
            //change product
            $data['manufacturers'][$product->merchant_id]['products'][$product->id]['total_quantity'] = $request->quantity;
            $data['manufacturers'][$product->merchant_id]['products'][$product->id]['total_price'] = $product->price * $request->quantity;
            Session::put('cart', $data);
            return response()->json([
                'status' => true,
                'total_products' => (int)$data['total_products'],
                'total_price' => (int)$data['total_price'],
                'total_merchant_quantity' => (int)$data['manufacturers'][$product->merchant_id]['total_product_quantity'],
                'total_merchant_price' => (int)$data['manufacturers'][$product->merchant_id]['total_product_price'],
                'total_product_price' => (int)$data['manufacturers'][$product->merchant_id]['products'][$product->id]['total_price'],
                'total_product_quantity' => (int)$data['manufacturers'][$product->merchant_id]['products'][$product->id]['total_quantity'],
                'merchant_id' => $product->merchant->id
            ]);
        }

        return response()->json([
            'status' => false,
        ]);


    }

    public function addQuantity(Request $request)
    {

        Session::put('product_quantity', $request->val);

    }

}
