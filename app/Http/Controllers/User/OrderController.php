<?php

namespace App\Http\Controllers\User;

use App\Address;
use App\Http\Controllers\Controller;
use App\Order;
use App\OrderList;
use App\Product;
use App\Transaction;
use App\PaymentSystemTool;
use App\DeliveryPriceTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class   OrderController extends Controller
{
    public function create(Request $request)
    {


        if (!Session::has('cart')) {
            return redirect()->route('user.cart', app()->getLocale());
        }

        if (!auth()->check()) {
            return redirect()->route('user.login', app()->getLocale());
        }
        $data = Session::get('cart')['manufacturers'];
         foreach ($data[$request->merchant_id]['products'] as $key => $product) {
            $products = Product::where('id', $key)->first();
            if ($products->quantity < 0 ||$products->quantity==0 ) {
               return redirect()->route('user.cart', app()->getLocale());
            }
         }
        if ($request->payment_way != 'paycom') {

            $data = Session::get('cart')['manufacturers'];

            $address = null;

            if ($request->delivery_type == "active") {

                $address = new Address();
                $address->user_id = auth()->user()->id;
                $address->address_type = false;
                $address->role_id = 2;
                $address->region_id = $request->address['region'];
//                $address->district_id = $request->address['district'];
                // $address->house = $request->address['house'];
                // $address->street = $request->address['street'];
//                 $address->house = "...";
//                $address->street = "...";
                // $address->entrance = $request->address['entrance'];
                // $address->floor = $request->address['floor'];
                // $address->apartment = $request->address['apartment'];
//                $address->postcode = 0;
//                $address->reference_point = $request->reference_point;
                $address->save();
            }

            $order = new Order();
            $order->user_id = auth()->user()->id;
            $order->merchant_id = $request->merchant_id;
            if ($address == null) {
                $order->address_id = 0;
            } else {
                $order->address_id = $address->id;
            }

            $order->total_price = $data[$request->merchant_id]['total_product_price'];
            $order->payment_method = $request->payment_way;
            $order->payment_status = false;
            $order->is_active = true;
            $order->state = "new";

            $order->save();
            foreach ($data[$request->merchant_id]['products'] as $key => $product) {
                $order_list = new OrderList();

                $order_list->order_id = $order->id;
                $order_list->product_id = $key;
                $order_list->product_quantity = $product['total_quantity'];

                $order_list->save();

                $products = Product::where('id', $key)->first();
                $products->quantity = $products->quantity - $product['total_quantity'];
                $products->save();


                if (!is_null($product['product']->maker_phone)) {
                    $data = '{"messages":[{"recipient":"' . $product['product']->maker_phone . '","message-id":"itsm' . $key . '","sms":{"originator": "3700","content":{"text":"Ваши товары № ' . $product['product']->id . ' в количестве ' . $product['total_quantity'] . ' шт были реализованы: Artshop.itsm.uz"}}}]}';
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'http://91.204.239.44/broker-api/send',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => $data,
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: Basic aXRzbTpoVTEwQFFjMw==',
                            'Content-Type: application/json'
                        ),
                    ));
                    $response = curl_exec($curl);
                    curl_close($curl);
                }
            }
            $data = Session::get('cart');
            $data['total_products'] = (int)$data['total_products'] - (int)$data['manufacturers'][$request->merchant_id]['total_product_quantity'];
            $data['total_price'] = (int)$data['total_price'] - (int)$data['manufacturers'][$request->merchant_id]['total_product_price'];


            unset($data['manufacturers'][$request->merchant_id]);

            if (count($data['manufacturers']) == 0) {
                Session::forget('cart');
            } else {
                Session::put('cart', $data);
            }

            if ($order->payment_method == 'bank') {
                return redirect()->route('user.invoice', app()->getLocale());
            }
        }


        if ($request->payment_way == 'paycom') {
            $address = null;
            if ($request->delivery_type == "active") {

                $address = new Address();
                $address->user_id = auth()->user()->id;
                $address->address_type = false;
                $address->role_id = 2;
                $address->region_id = $request->address['region'];
//                $address->district_id = $request->address['district'];
                // $address->house = $request->address['house'];
                // $address->street = $request->address['street'];
//                 $address->house = "...";
//                $address->street = "...";
                // $address->entrance = $request->address['entrance'];
                // $address->floor = $request->address['floor'];
                // $address->apartment = $request->address['apartment'];
//                $address->postcode = 0;
//                $address->reference_point = $request->reference_point;
                $address->save();
            }
            $data = Session::get('cart')['manufacturers'];
            $array_data = [];
            foreach ($data[$request->merchant_id]['products'] as $key => $product) {
                $array_data[$key] = $product['total_quantity'];

            }
            $delivery_price = null;
            $transaction = new Transaction();
            $transaction->trans_prepare_id = (int)(microtime(true) * 1000);
            if ($address == null) {
                $transaction->order_id = 0;
            } else {
                $transaction->order_id = $address->id;
            }

            if ($address == null) {
                $transaction->amount = $data[$request->merchant_id]['total_product_price'];
            } else {
                $delivery_price = DeliveryPriceTable::where('region_id', $request->address['region'])->
                where('seller_id', $request->merchant_id)->first();
                $transaction->amount = (int)$data[$request->merchant_id]['total_product_price'] + (int)($delivery_price->price);
            }

            $transaction->sign_string = serialize($array_data);
            $transaction->merchant_prepare_id = auth()->user()->id;
            $transaction->merchant_trans_id = $request->merchant_id;
            $transaction->save();

            $payment = PaymentSystemTool::where('seller_id', $request->merchant_id)->first();
            return redirect()->to("https://my.click.uz/services/pay?service_id=$payment->login&merchant_id=$payment->merchant_id&amount=$transaction->amount&transaction_param=$transaction->trans_prepare_id&merchant_user_id=$payment->password&return_url=" . route('user.profile.orders', app()->getLocale()));
        }
        return redirect()->route('user.profile.orders', app()->getLocale());

    }

    public function bayProduct(Request $request)
    {
        $product = Product::where('id', $request->product_id)->first();
        if ($product->quantity == 0 || $product->quantity < 0) {
            return redirect()->route('user.index', app()->getLocale());
        }

        if (!auth()->check()) {
            return redirect()->route('user.login', app()->getLocale());
        }
        if ($request->payment_way != 'paycom') {


            $address = null;

            if ($request->delivery_type == "active") {

                $address = new Address();
                $address->user_id = auth()->user()->id;
                $address->address_type = false;
                $address->role_id = 2;
                $address->region_id = $request->address['region'];
//                $address->district_id = $request->address['district'];
                // $address->house = $request->address['house'];
                // $address->street = $request->address['street'];
//                 $address->house = "...";
//                $address->street = "...";
                // $address->entrance = $request->address['entrance'];
                // $address->floor = $request->address['floor'];
                // $address->apartment = $request->address['apartment'];
//                $address->postcode = 0;
//                $address->reference_point = $request->reference_point;
                $address->save();
            }

            $order = new Order();
            $order->user_id = auth()->user()->id;
            $order->merchant_id = $request->merchant_id;
            if ($address == null) {
                $order->address_id = 0;
            } else {
                $order->address_id = $address->id;
            }
            if (Session::has('product_quantity')) {
                $order->total_price = $product->price * Session::get('product_quantity');
            } else {
                $order->total_price = $product->price;
            }

            $order->payment_method = $request->payment_way;
            $order->payment_status = false;
            $order->is_active = true;
            $order->state = "new";

            $order->save();

            $order_list = new OrderList();

            $order_list->order_id = $order->id;
            $order_list->product_id = $product->id;
            if (Session::has('product_quantity')) {
                $order_list->product_quantity = Session::get('product_quantity');
            } else {

                $order_list->product_quantity = 1;
            }


            $order_list->save();
            if (Session::has('product_quantity')) {
                $product->quantity = $product->quantity - Session::get('product_quantity');
            } else {
                $product->quantity = $product->quantity - 1;
            }

            $product->save();


            Session::forget('product_quantity');


            if ($order->payment_method == 'bank') {
                return redirect()->route('user.invoice', app()->getLocale());
            }
            return redirect()->route('user.profile.orders', app()->getLocale());
        }


        if ($request->payment_way == 'paycom') {
            $address = null;
            if ($request->delivery_type == "active") {

                $address = new Address();
                $address->user_id = auth()->user()->id;
                $address->address_type = false;
                $address->role_id = 2;
                $address->region_id = $request->address['region'];
//                $address->district_id = $request->address['district'];
                // $address->house = $request->address['house'];
                // $address->street = $request->address['street'];
//                 $address->house = "...";
//                $address->street = "...";
                // $address->entrance = $request->address['entrance'];
                // $address->floor = $request->address['floor'];
                // $address->apartment = $request->address['apartment'];
//                $address->postcode = 0;
//                $address->reference_point = $request->reference_point;
                $address->save();
            }

            if (Session::has('product_quantity')) {
                $array_data[$product->id] = Session::get('product_quantity');
            } else {
                $array_data[$product->id] = 1;
            }


            $delivery_price = null;
            $transaction = new Transaction();
            $transaction->trans_prepare_id = (int)(microtime(true) * 1000);
            if ($address == null) {
                $transaction->order_id = 0;
            } else {
                $transaction->order_id = $address->id;
            }

            if ($address == null) {
                if (Session::has('product_quantity')) {
                    $transaction->amount = $product->price * Session::get('product_quantity');
                } else {
                    $transaction->amount = $product->price;
                }


            } else {
                $delivery_price = DeliveryPriceTable::where('region_id', $request->address['region'])->
                where('seller_id', $request->merchant_id)->first();
                if (Session::has('product_quantity')) {
                    $transaction->amount = ($product->price * Session::get('product_quantity')) + (int)($delivery_price->price);
                } else {
                    $transaction->amount = $product->price + (int)($delivery_price->price);
                }
//                $transaction->amount = (int)$data[$request->merchant_id]['total_product_price'] + (int)($delivery_price->price);
            }

            $transaction->sign_string = serialize($array_data);
            $transaction->merchant_prepare_id = auth()->user()->id;
            $transaction->merchant_trans_id = $request->merchant_id;
            $transaction->save();

            $payment = PaymentSystemTool::where('seller_id', $request->merchant_id)->first();
            return redirect()->to("https://my.click.uz/services/pay?service_id=$payment->login&merchant_id=$payment->merchant_id&amount=$transaction->amount&transaction_param=$transaction->trans_prepare_id&merchant_user_id=$payment->password&return_url=" . route('user.profile.orders', app()->getLocale()));
        }
        return redirect()->route('user.profile.orders', app()->getLocale());

    }

}
