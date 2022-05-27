<?php

namespace App\Http\Controllers\Seller;

use App\Address;
use App\Http\Controllers\Controller;
use App\Order;
use App\Product;
use App\OrderList;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where([
            ['merchant_id', auth()->user()->id],
            ['is_active', true]
        ])->with(['mertchant', 'orderer', 'orderList', 'deliveryTable', 'address'])->orderBy('created_at','desc')->orderBy('created_at')->paginate(5);
        return view('seller.pages.orders.index', compact('orders'));
    }

    public function postSearch(Request $request)
    {


        if (is_null($request->search && $request->state == "null")) {
            return redirect()->route('seller.order.index', app()->getLocale());
        }
        $search = "null";
        if (!is_null($request->search)) {
            $search = $request->search;
        }
        return redirect()->route('seller.order.search', ['lang' => app()->getLocale(), 'search' => $search, 'state' => $request->state]);

    }

    public function search($lang, $search, $state)
    {
        if ($search != "null" && $state != "null") {
            $orders = Order::where([
                ['merchant_id', auth()->user()->id],
                ['total_price', $search],
                ['state', $state],
                ['is_active', true]
            ])->orderBy('created_at')->
            with(['orderList', 'mertchant', 'deliveryTable', 'address'])->paginate(5);
        } elseif ($search == "null") {
            $orders = Order::where([
                ['merchant_id', auth()->user()->id],
                ['is_active', true],
                ['state', $state]
            ])->orderBy('created_at')->
            with(['orderList', 'mertchant', 'deliveryTable', 'address'])->paginate(5);
        } elseif ($state == "null") {
            $orders = Order::where([
                ['merchant_id', auth()->user()->id],
                ['total_price', $search],
                ['is_active', true]

            ])->orderBy('created_at')->
            with(['orderList', 'mertchant', 'deliveryTable', 'address'])->paginate(5);
        }
        return view('seller.pages.orders.index', compact('orders', 'search', 'state'));

    }

    public function invoice($lang, $id)
    {
        $invoice_items = OrderList::where([
            ['order_id', $id],

        ])->with(['product'])->get();

        $order = Order::where(
            [
                ['id', $id],
                ['is_active', true]
            ]
        )->with(['orderList', 'mertchant', 'orderer', 'deliveryTable', 'address'])->first();

        if (!$order) {
            return redirect()->back();
        }
        return view('seller.pages.orders.invoice', compact('invoice_items', 'order'));
    }

    public function invoiceArchive($lang, $id)
    {
        $invoice_items = OrderList::where([
            ['order_id', $id],

        ])->with(['product'])->get();

        $order = Order::where(
            [
                ['id', $id],
                ['is_active', false]
            ]
        )->with(['orderList', 'mertchant', 'orderer', 'deliveryTable', 'address'])->first();

        if (!$order) {
            return redirect()->back();
        }
        return view('seller.pages.orders.invoice', compact('invoice_items', 'order'));
    }

    public function archive()
    {
        $orders = Order::where([
            ['merchant_id', auth()->user()->id],
            ['is_active', false]
        ])->with(['mertchant', 'orderer', 'orderList', 'deliveryTable', 'address'])->paginate(5);

        return view('seller.pages.orders.archive', compact('orders'));
    }

    public function deleteArchive(Request $request)
    {
        $order = Order::where('id', $request->id)->first();
        if ($order->state == "new") {
            $data = '{"messages":[{"recipient":"' . $order->orderer->phone_number . '","message-id":"itsm' . $order->id . '","sms":{"originator": "3700","content":{"text":"' . __('sms_cancel') . '. № ' . __('sms_your_order') . ': ' . $order->id . '  Artshop.itsm.uz"}}}]}';
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
            $order->is_active = false;
            $order->save();
        } else {
            $order->is_active = false;
            $order->save();
        }
        return response()->json([
            'status' => true
        ]);
    }

    public function archivePostSearch(Request $request)
    {


        if (is_null($request->search && $request->state == "null")) {
            return redirect()->route('seller.order.index', app()->getLocale());
        }
        $search = "null";
        if (!is_null($request->search)) {
            $search = $request->search;
        }
        return redirect()->route('seller.order.search', ['lang' => app()->getLocale(), 'search' => $search, 'state' => $request->state]);

    }

    public function archiveSearch($lang, $search, $state)
    {
        if ($search != "null" && $state != "null") {
            $orders = Order::where([
                ['merchant_id', auth()->user()->id],
                ['total_price', $search],
                ['state', $state],
                ['is_active', false]
            ])->orderBy('created_at')->
            with(['orderList', 'mertchant', 'deliveryTable', 'address'])->paginate(5);
        } elseif ($search == "null") {
            $orders = Order::where([
                ['merchant_id', auth()->user()->id],
                ['is_active', false],
                ['state', $state]
            ])->orderBy('created_at')->
            with(['orderList', 'mertchant', 'deliveryTable', 'address'])->paginate(5);
        } elseif ($state == "null") {
            $orders = Order::where([
                ['merchant_id', auth()->user()->id],
                ['total_price', $search],
                ['is_active', false]
            ])->orderBy('created_at')->
            with(['orderList', 'mertchant', 'deliveryTable', 'address'])->paginate(5);
        }
        return view('seller.pages.orders.index', compact('orders', 'search', 'state'));

    }


    public function activeInvoice(Request $request)
    {
        $order = Order::where('id', $request->order_id)->with(['orderer'])->first();
        $order->state = "accept";
        $order->payment_status = true;
        $order->save();

        $data = '{"messages":[{"recipient":"' . $order->orderer->phone_number . '","message-id":"itsm' . $order->id . '","sms":{"originator": "3700","content":{"text":"' . __('sms_accept') . '. № ' . __('sms_your_order') . ': ' . $order->id . '  Artshop.itsm.uz"}}}]}';
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


        return redirect()->route('seller.order.index', app()->getLocale());
    }

    public function deActiveInvoice(Request $request)
    {
        $order = Order::where('id', $request->order_id)->with(['orderer', 'orderList'])->first();
        $order->state = "cancel";
        $order->payment_status = false;
        $order->save();
        foreach ($order->orderList as $item) {
            $pro = Product::where('id', $item->product_id)->first();
            $pro->quantity = $pro->quantity + $item->product_quantity;
            $pro->save();
        }

        $data = '{"messages":[{"recipient":"' . $order->orderer->phone_number . '","message-id":"itsm' . $order->id . '","sms":{"originator": "3700","content":{"text":"' . __('sms_cancel') . '. № ' . __('sms_your_order') . ': ' . $order->id . '  Artshop.itsm.uz"}}}]}';
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


        return redirect()->route('seller.order.index', app()->getLocale());
    }

    public function updateAddressDistrict(Request $request)
    {
        if (isset($request->address_id)){
            $address = Address::where([
                ['id', $request->address_id],
            ])->first();
            $address->district_id=$request->district_id;
            $address->save();
            return response()->json([
                'status'=>true
            ]);
        }

        return response()->json([
            'status'=>false
        ]);
    }

    public function updateStreet(Request $request)
    {
        if (isset($request->address_id)){
            $address = Address::where([
                ['id', $request->address_id],
            ])->first();
            $address->street=$request->street;
            $address->house=$request->house;
            $address->save();
            return response()->json([
                'status'=>true
            ]);
        }

        return response()->json([
            'status'=>false
        ]);
    }

}
