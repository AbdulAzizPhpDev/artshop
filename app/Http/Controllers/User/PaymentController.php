<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Order;
use App\Click;
use App\Transaction;
use App\Product;
use App\OrderList;
use App\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{

    public function createClickInvoice(Request $request)
    {
        $request->validate(
            [
                'order_id' => 'required',
                'phone_number' => 'required'
            ]
        );
        $order = Order::findOrFail($request->order_id);

        $service_id = 19432;
        $merchant_id = 13940;
        $secret_key = 'KAGZAkHPr1p';
        $merchant_user_id = 22023;

        $amount = 1000;
        $phone_number = '998' . $request->phone_number;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.click.uz/v2/merchant/invoice/create',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'service_id' => $service_id,
                'amount' => $amount,
                'phone_number' => $phone_number,
                'merchant_trans_id' => time() . random_int(100, 999)
            ),
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Auth:' . $merchant_user_id . ':' . sha1(time() . $secret_key) . ':' . time()
            ),
        ));

        $response = curl_exec($curl);
        $data = json_decode($response);

        if (isset($data->error_code) and $data->error_code == 0 and isset($data->invoice_id)) {
            Click::query()
                ->where('order_id', $order->id)
                ->where('status', Click::NEW_INVOICE)
                ->delete();
            Click::query()->create([
                'order_id' => $order->id,
                'invoice_id' => $data->invoice_id
            ]);
        } else {
            return abort(422);
        }

        curl_close($curl);

        return response(true, 200);
    }

    public function checkClickPayment(Request $request)
    {
        $click = Click::query()->where('order_id', $request->order_id)->firstOrFail();

        $service_id = 19432;
        $merchant_id = 13940;
        $secret_key = 'KAGZAkHPr1p';
        $merchant_user_id = 22023;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.click.uz/v2/merchant/invoice/status/' . $service_id . '/' . $click->invoice_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Auth:' . $merchant_user_id . ':' . sha1(time() . $secret_key) . ':' . time()
            ),
        ));


        $response = curl_exec($curl);
        $data = json_decode($response);
        if (isset($data->status) and isset($data->error_code) and isset($data->error_note)) {
            return response()->json([
                'status' => $data->status,
                'error_note' => $data->error_note,
                'error_code' => $data->error_code
            ]);
        } else {
            return abort(422);
        }

    }

    public function paymentCreate(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $click = (!empty($order->manufacturerWithPayment->payment->where('is_active', 2)->first())) ?
            $order->manufacturerWithPayment->payment->where('type', 2)->first() : null;

        if (!$click) {
            return abort(422);
        }
        $merchantID = $click->provider_id;
        $serviceID = $click->service_id;
        $merchant_trans_id = random_int(1000, 9999) . $order->id . substr((string)time(), 3, 9);
        $transAmount = number_format(1000, 2, '.', '');

        Click::query()->updateOrCreate(
            [
                'order_id' => $order->id,
                'status' => Click::NEW_INVOICE,
            ],
            [
                'merchant_trans_id' => $merchant_trans_id,
            ]);

        $link = "https://my.click.uz/services/pay?service_id=19234&merchant_id=11592&amount=1000&transaction_param=25742142219143";

        return redirect()->to($link);
    }


    public function clickPrepare(Request $request)
    {

        $click_trans_id = $request->click_trans_id;
//        $service_id = $request->service_id;
//        $click_paydoc_id = $request->click_paydoc_id;
        $merchant_trans_id = $request->merchant_trans_id;
//        $payment = Click::query()
//            ->where('merchant_trans_id', $merchant_trans_id)
//            ->where('status', Click::NEW_INVOICE)
//            ->first();
//        $amount = $request->amount;
//        $action = $request->action;
//        $error = $request->error;
//        $error_note = $request->error_note;
//        $sign_time = $request->sign_time;
        $sign_string = $request->sign_string;

        $error_code = 0;
        $return_error_note = '';

        return response()->json(
            [
                'click_trans_id' => $request->click_trans_id,
                'merchant_trans_id' => $request->merchant_trans_id,
                'merchant_prepare_id' => $request->merchant_trans_id,
                'error' => 0,
                'error_note' => 'Success',
            ]
        );
    }

    public function clickComplete(Request $request)
    {

        $click_trans_id = $request->click_trans_id;
        $merchant_trans_id = $request->merchant_trans_id;
        $error_code = 0;
        $return_error_note = 'Success';

        $transaction = Transaction::where('trans_prepare_id', $request->merchant_trans_id)->first();

        $order = new Order();
        $order->user_id = $transaction->merchant_prepare_id;
        $order->merchant_id = $transaction->merchant_trans_id;
        if ($transaction->order_id == 0) {
            $order->address_id = 0;
        } else {
            $order->address_id = $transaction->order_id;
        }
        $order->total_price = $transaction->amount;
        $order->payment_method = 'paycom';
        $order->is_active = true;
        $order->state = "accept";
        $order->payment_status = true;

        $order->save();

        $user = User::where('id',$order->user_id)->first();

        $data = '{"messages":[{"recipient":"' . $user->phone_number . '","message-id":"itsm' . $request->merchant_trans_id . '","sms":{"originator": "3700","content":{"text":"' . __('sms_accept') . '. № ' . __('sms_your_order') . ': ' . $request->merchant_trans_id . '  Artshop.itsm.uz"}}}]}';
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

        foreach (unserialize($transaction->sign_string) as $id => $count) {
            $product = Product::where('id', $id)->first();

            $order_list = new OrderList();

            $order_list->order_id = $order->id;
            $order_list->product_id = $id;
            $order_list->product_quantity = $count;

            $order_list->save();

            $product->quantity = $product->quantity - $count;
            $product->save();


            if (!is_null($product->maker_phone)) {
                $data = '{"messages":[{"recipient":"' . $product->maker_phone . '","message-id":"itsm' . $id . '","sms":{"originator": "3700","content":{"text":"Ваши товары № ' . $product->id . ' в количестве ' . $count . ' шт были реализованы: Artshop.itsm.uz"}}}]}';
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

        $transaction->order_id = $order->id;
        $transaction->service_id = $request->service_id;
        $transaction->merchant_trans_id = $request->merchant_trans_id;
        $transaction->action = $request->action;
        $transaction->error = $request->error;
        $transaction->error_note = $request->error_note;
        $transaction->sign_string = $request->sign_string;
        $transaction->save();

        return response()->json(
            [
                'click_trans_id' => $click_trans_id,
                'merchant_trans_id' => $merchant_trans_id,
                'merchant_confirm_id' => null,
                'error' => $error_code,
                'error_note' => $return_error_note,
            ]
        );
    }


}
