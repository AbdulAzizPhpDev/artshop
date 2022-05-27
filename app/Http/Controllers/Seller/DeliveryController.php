<?php

namespace App\Http\Controllers\Seller;

use App\DeliveryPriceTable;
use App\Http\Controllers\Controller;
use App\PaymentSystemTool;
use App\Region;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function delivery()
    {
        $regions = Region::where([
            ['parent_id', 0],
        ])->with(['priceList'])->get();
        $price_list = DeliveryPriceTable::where([
            ['is_active', true],
            ['seller_id', auth()->user()->id]
        ])->get();
        $price_list = count($price_list);

        return view('seller.pages.delivery.index', compact('regions', 'price_list'));
    }

    public function setDeliveryPrice(Request $request)
    {
        $status = DeliveryPriceTable::where([
            ['seller_id', $request->seller_id],
            ['region_id', $request->region_id],
        ])->exists();
        $delivery = null;
        if (!$status) {
            $delivery = new DeliveryPriceTable();
            $delivery->seller_id = $request->seller_id;
            $delivery->region_id = $request->region_id;
            $delivery->price = $request->price;
            $delivery->is_active = true;
            $delivery->save();
        } else {
            $delivery = DeliveryPriceTable::where([
                ['seller_id', $request->seller_id],
                ['region_id', $request->region_id],
            ])->first();
            $delivery->price = $request->price;
            $delivery->is_active = true;
            $delivery->save();
        }
        return response()->json([
            'data' => $delivery
        ]);
    }

    public function changeDeliveryStatus(Request $request)
    {

        $status = DeliveryPriceTable::where([
            ['seller_id', $request->seller_id]
        ])->exists();

        if ($status) {
            $price_list = DeliveryPriceTable::where([
                ['seller_id', $request->seller_id],
            ])->update(['is_active' => filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)]);

            return response()->json([
                'is_active' => filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
            ]);
        }
        return response()->json([
            'is_active' => false
        ]);

    }

    public function changeSingleStatus(Request $request)
    {

        $delivery = DeliveryPriceTable::where([
            ['seller_id', auth()->user()->id],
            ['region_id', $request->id],
        ])->first();

        if (filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)) {
            $delivery->is_active = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        } else {
            $delivery->is_active = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        }

        $delivery->save();
        return response()->json([
            'status' => filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
        ]);
    }

    public function payment()
    {
        $payment = PaymentSystemTool::where('seller_id', auth()->user()->id)->first();
        return view('seller.pages.delivery.setting-payment', compact('payment'));
    }

    public function postPayment(Request $request)
    {

        $payment_status = PaymentSystemTool::where('seller_id', auth()->user()->id)->exists();
        if ($payment_status) {
            $payment = PaymentSystemTool::where('seller_id', auth()->user()->id)->first();
            if (isset($request->cash)) {
                $payment->is_cash = true;
            } else {
                $payment->is_cash = false;
            }
            if (isset($request->paycom)) {
                $payment->merchant_id = $request->merchant_id;
                $payment->key = $request->key;
                $payment->password = $request->password;
                $payment->login = $request->login;
                $payment->system = $request->paycom_name;
                $payment->is_active = true;
                $payment->seller_id = auth()->user()->id;
            } else {
                $payment->is_active = false;
            }
            $payment->save();
        } else {
            $payment = new PaymentSystemTool();
            if (isset($request->cash)) {
                $payment->is_cash = true;
            } else {
                $payment->is_cash = false;
            }
            if (isset($request->paycom)) {
                $payment->merchant_id = $request->merchant_id;
                $payment->key = $request->key;
                $payment->password = $request->password;
                $payment->login = $request->login;
                $payment->system = $request->paycom_name;
                $payment->is_active = true;
            } else {
                $payment->is_active = false;
            }
            $payment->seller_id = auth()->user()->id;
            $payment->save();

        }
        return redirect()->route('seller.delivery.payment', app()->getLocale())->withErrors(['success'=>'success']);
    }
}
