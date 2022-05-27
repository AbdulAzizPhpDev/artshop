<?php

namespace App\Http\Controllers;

use App\Catalog;
use App\DeliveryPriceTable;
use App\Region;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use App\Order;
use App\Product;
use App\OrderList;

class MainController extends Controller
{
    public function districts(Request $request)
    {
        $districts = Region::where('id', $request->id)->with(['district'])->orderBy('id')->first();
        $price_ids = DeliveryPriceTable::where([
            ['region_id', $request->id],
            ['seller_id', $request->seller_id]
        ])->first();
        return response()->json([
            'data' => $districts->district,
            'price' => $price_ids
        ]);
    }

    public function districtSearch(Request $request)
    {
        $districts = Region::whereIn('parent_id', $request->ids)->orderBy('parent_id')->get();

        return response()->json([
            'data' => $districts

        ]);
    }

    public function categories(Request $request)
    {
        $categories = Catalog::where('parent_id', $request->id)->orderBy('name_uz')->get();
        return response()->json([
            'data' => $categories
        ]);
    }

    public function changePhone(Request $request)
    {
        $confirm_number = random_int(100000, 999999);
        $data = '{"messages":[{"recipient":"' . $request->phone . '","message-id":"itsm' . $confirm_number . '","sms":{"originator": "3700","content":{"text":"Код подтверждения: ' . $confirm_number . ' для смены номера телефона. Artshop.itsm.uz"}}}]}';
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

        $arr = [
            'phone' => $request->phone,
            'confirm' => $confirm_number,
            'format' => $request->format_phone
        ];


        session()->put('user_phone', $arr);

        return response()->json([
            'phone' => $request->phone
        ]);

    }

    public function changeLanguage($lang)
    {

        session()->put('lang', $lang);
        app()->setLocale($lang);
        return redirect()->back();
    }

//    public function pdf()
//    {
//        $data = array(
//            "data" => "sdjkfhsjdkfhjksdhfkjshdfjk"
//        );
//
//
//        $pdf = PDF::loadView('pdf', $data)->setPaper('a4', 'landscape');
//
//        return $pdf->download('invoice.pdf');
//
//    }

    public function printDocument($lang,$order_id)
    {

        if (auth()->check()) {
            $order = Order::where(
                [
                    ['id', $order_id],
                    ['is_active', true],
                    ['merchant_id', auth()->user()->id]
                ]
            )->with(['orderList', 'mertchant', 'orderer', 'deliveryTable', 'address'])->first();

            return view('pdf',compact('order'));
        }

        return redirect()->route('user.index', app()->getLocale());

    }
}
