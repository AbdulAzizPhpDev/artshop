<?php

namespace App\Http\Controllers\User;

use App\Address;
use App\CustomerRating;
use App\Http\Controllers\Controller;
use App\MostViewedProduct;
use App\Order;
use App\OrderList;
use App\Product;
use App\Region;
use App\User;
use App\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;


class ProfileController extends Controller
{
    public function index()
    {
        $products = Product::
        where([
            ['is_popular', true],
            ['is_active', true]
        ])->take(10)->get();
        $viewed_products = MostViewedProduct::where('user_id', Auth::user()->getAuthIdentifier())->with(['product'])->orderBy('created_at', 'desc')->get();
        return view('user.profile.index', compact('products', 'viewed_products'));
    }

    public function information()
    {
        $user = User::where('id', auth()->user()->id)->with(['addressInfo'])->first();
        $regions = Region::where('parent_id', 0)->get();
        $districts = null;
        if ($user->address != null) {
            $districts = Region::where([
                ['parent_id', $user->address->region_id],
            ])->orderBy('name_uz')->get();
        }
        return view('user.profile.information', compact('user', 'regions', 'districts'));
    }

    public function storeInformation(Request $request)
    {
        if (is_null($request->name)) {
            return back()->withErrors([
                'name' => 'поле имени должно быть заполнено'
            ])->withInput();
        }
        $user = User::where('id', Auth::user()->id)->first();
        $user->birth_day = $request->birth_day;
        $user->name = $request->name;
        if ($request->gender == 'male') {
            $user->gender = true;
        } else {
            $user->gender = false;
        }
        $user->save();
        if ($request->address['region'] != 'null' && $request->address['district'] != 'null') {

            $status = Address::where(
                [
                    ['user_id', Auth::user()->id],
                    ['address_type', true]
                ]
            )->exists();
            if ($status) {
                $address = Address::where(
                    [
                        ['user_id', Auth::user()->id],
                        ['address_type', true]
                    ]
                )->first();
                $address->region_id = $request->address['region'];
                $address->district_id = $request->address['district'];
                $address->street = $request->address['street'];
                $address->house = $request->address['house'];
                $address->entrance = $request->address['entrance'];
                $address->floor = $request->address['floor'];
                $address->apartment = $request->address['apartment'];
                $address->postcode = $request->address['postcode'];
                $address->save();
            } else {
                $address = new Address();
                $address->user_id = $user->id;
                $address->role_id = $user->role_id;
                $address->address_type = true;
                $address->postcode = $request->address['postcode'];
                $address->region_id = $request->address['region'];
                $address->district_id = $request->address['district'];
                $address->street = $request->address['street'];
                $address->house = $request->address['house'];
                $address->entrance = $request->address['entrance'];
                $address->floor = $request->address['floor'];
                $address->apartment = $request->address['apartment'];
                $address->save();
            }
        }
        return redirect()->route('user.profile.information', ['lang' => app()->getLocale()])->withErrors(['success'=>'success']);;
    }

    public function security()
    {
        $user = User::where('id', auth()->user()->id)->first();
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
        return view('user.profile.security', compact('user', 'new_phone'));
    }

    public function postSecurity(Request $request)
    {
        if ($request->password != null || $request->new_password != null || $request->confirm_password != null) {
            $request->validate([
                'password' => 'required|min:8|max:24',
                'new_password' => 'required|min:8|max:24',
                'confirm_password' => 'required|min:8|max:24'
            ]);
            $user = User::where('id', auth()->user()->id)->first();
            if (Hash::check($request->password, $user->password)) {
                if ($request->new_password == $request->confirm_password) {
                    $user->password = Hash::make($request->new_password);
                    $user->save();
                    return back()->withErrors([
                        'success' => 'ваш пароль успешно изменен'
                    ]);
                }
                return back()->withErrors([
                    'new_password' => 'ваши пароли разные',
                    'confirm_password' => 'ваши пароли разные'
                ])->withInput([
                    'new_password' => $request->new_password,
                    'confirm_password' => $request->confirm_password
                ]);
            }
            return back()->withErrors([
                'password' => 'ваш пароль неправильный'
            ]);
        }
        return redirect()->route('user.profile.security')->withErrors(['success'=>'success']);
    }

    public function postLoginSecurity(Request $request)
    {

        if (isset($request->confirm_code)) {
            $request->validate([
                'phone_number' => 'min:13',
                'user_name' => 'max:24',
                'confirm_code' => 'min:6|numeric',
            ]);
            if ($request->confirm_code != session()->get('user_phone')['confirm']) {
                return back()->withErrors([
                    'confirm_code' => 'код подтверждения неверен'
                ])->withInput();
            }
            if ($request->user_name != null) {
                if (strlen($request->user_name) <= 2) {
                    return redirect()->back()->withErrors(['user_name' => 'имя пользователя должно содержать более 2 символов'])->withInput();
                }
                if (preg_match("/^\S.*\s.*\S$/", trim($request->user_name))) {
                    return redirect()->back()->withErrors(['user_name' => ' пробелы не допускаются для имени пользователя'])->withInput();
                }
                $user_status = User::where([
                    ['id', '!=', auth()->user()->id],
                    ['user_name', trim($request->user_name)]
                ])->exists();
                if ($user_status) {
                    return redirect()->back()->withErrors(['user_name' => 'Данное имя пользователя существует, попробуйте другое'])->withInput();
                }
            }
            $pattern = "/^\+998\d{9}$/";

            if (preg_match($pattern, $request->phone_number)) {
                $user = User::where('id', auth()->user()->id)->first();
                if ($request->phone_number != $user->phone_number) {
                    $item = User::where([
                        ['id', '!=', auth()->user()->id],
                        ['phone_number', $request->phone_number]
                    ])->exists();
                    if ($item)
                        return redirect()->back()->withErrors(['phone_number' => 'вставленный номер телефона существует, попробуйте другой'])->withInput();
                    $user->phone_number = $request->phone_number;
                }
                if ($request->user_name != null) {
                    $user->user_name = trim($request->user_name);
                }
                $user->save();
                Session::forget('user_phone');
                return redirect()->route('user.profile.security', ['lang' => app()->getLocale()]);
            } else {

                return redirect()->back()->withErrors(['phone_number' => 'введен номер телефона неправильного формата'])->withInput();
            }

        } else {
            $request->validate([
                'phone_number' => 'min:13',
                'user_name' => 'max:24',
            ]);
            session()->forget('user_phone');
            if ($request->user_name != null) {
                if (strlen($request->user_name) <= 2) {
                    return redirect()->back()->withErrors(['user_name' => 'имя пользователя должно содержать более 2 символов'])->withInput();
                }
                if (preg_match("/^\S.*\s.*\S$/", trim($request->user_name))) {
                    return redirect()->back()->withErrors(['user_name' => ' пробелы не допускаются для имени пользователя'])->withInput();
                }
                $user_status = User::where([
                    ['id', '!=', auth()->user()->id],
                    ['user_name', trim($request->user_name)]
                ])->exists();
                if ($user_status) {
                    return redirect()->back()->withErrors(['user_name' => 'Данное имя пользователя существует, попробуйте другое'])->withInput();
                }
            }
            $pattern = "/^\+998\d{9}$/";

            if (preg_match($pattern, $request->phone_number)) {
                $user = User::where('id', auth()->user()->id)->first();
                if ($request->phone_number != $user->phone_number) {
                    $item = User::where([
                        ['id', '!=', auth()->user()->id],
                        ['phone_number', $request->phone_number]
                    ])->exists();
                    if ($item)
                        return redirect()->back()->withErrors(['phone_number' => 'вставленный номер телефона существует, попробуйте другой'])->withInput();
                    $user->phone_number = $request->phone_number;
                }
                if ($request->user_name != null) {
                    $user->user_name = trim($request->user_name);
                }
                $user->save();
                return redirect()->route('user.profile.security', ['lang' => app()->getLocale()])->withErrors(['success'=>'success']);;
            } else {
                return redirect()->back()->withErrors(['phone_number' => 'введен номер телефона неправильного формата'])->withInput();
            }
        }

    }

    public function orders()
    {
        $orders = Order::where('user_id', auth()->user()->id)->orderBy('created_at','desc')->with(['orderList', 'mertchant', 'deliveryTable', 'address'])->paginate(10);

        return view('user.profile.orders', compact('orders'));
    }

    public function postSearch(Request $request)
    {


        if (is_null($request->search && $request->state == "null")) {
            return redirect()->route('user.profile.orders', app()->getLocale());
        }
        $search = "null";
        if (!is_null($request->search)) {
            $search = $request->search;
        }
        return redirect()->route('user.profile.search', ['lang' => app()->getLocale(), 'search' => $search, 'state' => $request->state]);

    }

    public function search($lang, $search, $state)
    {
       if($search!="null" && $state!="null"){
           $orders = Order::where([
               ['user_id', auth()->user()->id],
               ['total_price', $search],
               ['state' , $state]
           ])->orderBy('created_at')->
           with(['orderList', 'mertchant', 'deliveryTable', 'address'])->paginate(10);
       }
       elseif ($search=="null") {
           $orders = Order::where([
               ['user_id', auth()->user()->id],

               ['state' , $state]
           ])->orderBy('created_at')->
           with(['orderList', 'mertchant', 'deliveryTable', 'address'])->paginate(10);
       }elseif ($state=="null"){
           $orders = Order::where([
               ['user_id', auth()->user()->id],
               ['total_price', $search],

           ])->orderBy('created_at')->
           with(['orderList', 'mertchant', 'deliveryTable', 'address'])->paginate(10);
       }
        return view('user.profile.orders', compact('orders','search','state'));

    }

    public function wishlist()
    {
        $wishlists = Wishlist::where('user_id', auth()->user()->id)->with(['getProduct'])->get();
        return view('user.profile.wishlist', compact('wishlists'));
    }

    public function reviews()
    {
        $products = CustomerRating::where('user_id', Auth::user()->id)->with(['product'])->paginate(10);
        return view('user.profile.reviews', compact('products'));
    }

    public function reviewsPostSearch(Request $request)
    {
        if ($request->search == null)
            return redirect()->route('user.profile.reviews', app()->getLocale());
        return redirect()->route('user.profile.reviews.search', ['search' => $request->search, 'lang' => app()->getLocale()]);
    }

    public function reviewsSearch($lang, $search)
    {
        if (preg_match('/[А-Яа-яЁё]/u', $search)) {
            $products = CustomerRating::where([
                ['user_id', Auth::user()->id],
                ['comment', 'LIKE', "%$search%"]
            ])->
            with(['product'])->paginate(10);
        } else {
            $products = CustomerRating::where([
                ['user_id', Auth::user()->id],
                ['comment', 'LIKE', "%$search%"]
            ])->
            with(['product'])->paginate(10);
        }
        return view('user.profile.reviews', compact('products', 'search'));
    }

    public function messages()
    {
        return view('user.profile.messages');
    }

    public function invoice($lang, $order_id)
    {
        $invoice_items = OrderList::where('order_id', $order_id)->with(['product'])->get();
        $order = Order::where('id', $order_id)->with(['orderList', 'mertchant', 'orderer', 'deliveryTable', 'address'])->first();

        return view('user.profile.invoice', compact('invoice_items', 'order'));
    }

    public function activeInvoice(Request $request)
    {
        $order = Order::where('id', $request->order_id)->with(['orderer'])->first();
        $order->status = false;
        $order->save();

        $data = '{"messages":[{"recipient":"' . $order->orderer->phone_number . '","message-id":"itsm' . $order->id . '","sms":{"originator": "3700","content":{"text":"Ваш заказ принят. № вашего заказа: ' . $order->id . '  Artshop.itsm.uz"}}}]}';
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
        return redirect()->route('user.profile.orders');
    }


}

