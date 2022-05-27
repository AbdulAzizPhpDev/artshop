<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\PaymentSystemTool;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function sendSMS($code, $telephone)
    {
        $data = '{"messages":[{"recipient":"' . $telephone . '","message-id":"itsm' . $code . '","sms":{"originator": "3700","content":{"text":"Код потверждения: ' . $code . '.   Artshop.itsm.uz"}}}]}';
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

        return true;
    }

    public function resendSms(Request $request)
    {
        if (Session::has('user_info')) {
            $confirm_number = random_int(100000, 999999);
            $new_array = Session::get('user_info');
            $new_array['confirm_number'] = $confirm_number;
            Session::put('user_info', $new_array);
            $this->sendSMS($confirm_number, Session::get('user_info')["phone"]);
            return redirect()->route('user.confirm', ['lang' => app()->getLocale()]);
        }
        return redirect()->route('user.logout', ['lang' => app()->getLocale()]);
    }

    public function register()
    {
        return view('user.pages.auth.register');
    }

    public function registerSeller()
    {
        return view('user.pages.auth.seller-register');
    }

    public function registerPost(Request $request)
    {

        $request->validate([
            'full_name' => 'required|string|min:3|max:64|',
            'phone_number' => 'required|min:9',
            'password' => 'required|min:8|max:24',
            'confirm_password' => 'required|min:8|max:24',
            'type' => 'required',
        ]);

        if ($request->type < 2 || $request->type > 3) {
            return redirect()->back()->withErrors(['type' => 'Упс! Что-то пошло не так'])->withInput();
        }

        if ($request->password == $request->confirm_password) {
            $pattern = "/^\+998\d{9}$/";
            if (preg_match($pattern, $request->phone_number)) {


                if (User::where('phone_number', $request->phone_number)->exists()) {
                    return redirect()->back()->withErrors(['phone_number' => __('validation_product_3')])->withInput();
                }
            } else {
                return redirect()->back()->withErrors(['phone_number' => __('validation_product_1')])->withInput();
            }

            $confirm_number = random_int(100000, 999999);
            $phone = $request->get('phone_number');
            $name = $request->get('full_name');
            $password = $request->get('password');
            $type = $request->get('type');
            $this->sendSMS($confirm_number, $phone);
            $info = [
                'confirm_number' => $confirm_number,
                'phone' => $phone,
                'name' => $name,
                'password' => $password,
                'type' => $type
            ];
            Session::put('user_info', $info);

            return redirect()->route('user.confirm', ['lang' => app()->getLocale()]);

        }
        return redirect()->back()->withErrors(['confirm_password' => __('validation_product_2'), 'password' => __('validation_product_2')])->withInput();

    }


    public function login()
    {
        return view('user.pages.auth.login');
    }

    public function loginPost(Request $request)
    {
        $credentials = null;
        if (preg_match("/^\S.*\s.*\S$/", trim($request->phone_number))) {
            return redirect()->back()->withErrors(['phone_number' => __('password_error_1')])->withInput();
        }
        if (is_numeric($request->phone_number)) {
            $request->validate([
                'phone_number' => 'required',
                'password' => 'required|min:8|max:24',
            ]);
            $pattern = "/^\+998\d{9}$/";
            if (preg_match($pattern, $request->phone_number)) {
                $check_user = User::where([
                    ['phone_number', $request->phone_number]
                ])->first();
                if ($check_user == null)
                    return redirect()->back()->withErrors(['phone_number' => $request->phone_number . __('phone_not_find')])->withInput();
                if (!$check_user->is_active)
                    return redirect()->back()->withErrors(['phone_number' => __('password_error_2')])->withInput();
                if (!Hash::check($request->password, $check_user->password))
                    return redirect()->back()->withErrors(['password' => __('password_error_3')])->withInput();
                $credentials = [
                    'phone_number' => $request->phone_number,
                    'password' => $request->password
                ];
            } else {
                return redirect()->back()->withErrors(['phone_number' => __('validation_product_1')])->withInput();
            }

        } else {

            $request->validate([
                'phone_number' => 'required|min:3|max:32',
                'password' => 'required|min:8|max:24',
            ]);
            $check_user = User::where([
                ['user_name', $request->phone_number]
            ])->first();
            if ($check_user == null)
                return redirect()->back()->withErrors(['phone_number' => '"' . $request->phone_number . '" ' . __('user_not_find')])->withInput();
            if (!$check_user->is_active)
                return redirect()->back()->withErrors(['phone_number' => __('password_error_2')])->withInput();
            if (!Hash::check($request->password, $check_user->password))
                return redirect()->back()->withErrors(['password' => __('password_error_3')])->withInput();
            $credentials = [
                'user_name' => $request->phone_number,
                'password' => $request->password
            ];
        }


        if (Auth::attempt($credentials)) {
            if (Auth::user()->role_id === 1 || Auth::user()->role_id === 4) {
                return redirect()->route('admin.dashboard', ['lang' => app()->getLocale()]);
            }

            if (Auth::user()->role_id === 3) {
                return redirect()->route('seller.dashboard', ['lang' => app()->getLocale()]);
            }
            if (Session::has('redirect_url'))
                return redirect()->to(Session::get('redirect_url'));
            return redirect()->route('user.index', ['lang' => app()->getLocale()]);
        }
//        session()->flash('fail', 'Wrong email or password !');
        return redirect()->back()->withErrors(['phone_number' => 'логин или пароль неверный'])->withInput();

    }

    public function logout(Request $request)
    {
        $session_data = null;
        if (auth()->user()) {
            if (Session::has('cart')) {
                $session_data = Session::get('cart');
            }
            Auth::logout();
            $request->session()->invalidate();
            if ($session_data != null) {
                Session::put('cart', $session_data);
            }

        }
        return redirect()->route('user.index', ['lang' => app()->getLocale()]);
    }

    public function confirm()
    {

        return view('user.pages.auth.confirm');
    }

    public function confirmPost(Request $request)
    {
        $request->validate([
            'confirm_number' => 'required|min:6'
        ]);
        if ($request->get('confirm_number') == Session::get('user_info')['confirm_number']) {

            $user = User::create([
                'name' => Session::get('user_info')["name"],
                'phone_number' => Session::get('user_info')["phone"],
                'password' => Hash::make(Session::get('user_info')["password"]),

            ]);


            $user->role_id = Session::get('user_info')['type'];
            $user->save();
            $credentials = [
                'phone_number' => $user->phone_number,
                'password' => Session::get('user_info')["password"],
            ];
            if (Auth::attempt($credentials)) {
                Session::forget('user_info');
                if (Auth::user()->role_id == 3) {

                    $paymen = new PaymentSystemTool();
                    $paymen->is_cash = true;
                    $paymen->is_active = false;
                    $paymen->seller_id = $user->id;
                    $paymen->save();
                    return redirect()->route('seller.dashboard', ['lang' => app()->getLocale()]);
                }
                if (Session::has('redirect_url'))
                    return redirect()->to(Session::get('redirect_url'));
                return redirect()->route('user.index', ['lang' => app()->getLocale()]);
            }
        }
        return redirect()->route('user.confirm', ['lang' => app()->getLocale()])->
        withInput()->
        withErrors([
            'confirm_number' => __('password_error_4')
        ]);

    }

    public function restorePassword()
    {
        return view('user.pages.auth.restore-password', ['lang' => app()->getLocale()]);
    }

    public function postRestorePassword(Request $request)
    {
        $credentials = null;
        if (preg_match("/^\S.*\s.*\S$/", trim($request->phone_number))) {
            return redirect()->back()->withErrors(['phone_number' => __('password_error_1')])->withInput();
        }
        $request->validate([
            'phone_number' => 'required',
        ]);
        $pattern = "/^\+998\d{9}$/";
        if (preg_match($pattern, $request->phone_number)) {
            $check_user = User::where([
                ['phone_number', $request->phone_number]
            ])->first();
            if ($check_user == null)
                return redirect()->back()->withErrors(['phone_number' => $request->phone_format . __('phone_not_find')])->withInput();
            if (!$check_user->is_active)
                return redirect()->back()->withErrors(['phone_number' => __('password_error_2')])->withInput();


            $confirm_number = random_int(100000, 999999);
            $phone = $request->get('phone_number');
            $this->sendSMS($confirm_number, $phone);
            $info = [
                'confirm_number' => $confirm_number,
                'phone' => $phone,
            ];
            Session::put('restore', $info);
            return redirect()->route('user.confirm_password', ['lang' => app()->getLocale()]);
        } else {
            return redirect()->back()->withErrors(['phone_number' => __('validation_product_1')])->withInput();
        }

//        } else {
//
//            $request->validate([
//                'phone_number' => 'required|min:3|max:32',
//            ]);
//            $check_user = User::where([
//                ['user_name', $request->phone_number]
//            ])->first();
//            if ($check_user == null)
//                return redirect()->back()->withErrors(['phone_number' => '"' . $request->phone_number . '"' . ' does not find'])->withInput();
//            if (!$check_user->is_active)
//                return redirect()->back()->withErrors(['phone_number' => __('password_error_2')])->withInput();
//        }


//        if (Auth::attempt($credentials)) {
//            if (Auth::user()->role_id === 1 || Auth::user()->role_id === 4) {
//                return redirect()->route('admin.dashboard');
//            }
//
//            if (Auth::user()->role_id === 3) {
//                return redirect()->route('seller.dashboard');
//            }
//            return redirect()->route('user.index');
//        }
////        session()->flash('fail', 'Wrong email or password !');
//        return redirect()->back()->withErrors(['phone_number' => 'логин или пароль неверный'])->withInput();
    }

    public function confirmPassword()
    {
        return view('user.pages.auth.password-confirm', ['lang' => app()->getLocale()]);
    }

    public function changePassword(Request $request)
    {

        $request->validate([
            'confirm_number' => 'required|min:6',
            'password' => 'required|min:8|max:24',
            'confirm_password' => 'required|min:8|max:24',
        ]);
        if ($request->get('confirm_number') == Session::get('restore')['confirm_number']) {
            if ($request->password == $request->confirm_password) {
                $user = User::where('phone_number', Session::get('restore')['phone'])->first();
                $user->password = Hash::make($request->password);
                $user->save();
                Session::forget('restore');
                return redirect()->route('user.login', ['lang' => app()->getLocale()]);
                return redirect()->route('user.login', ['lang' => app()->getLocale()]);
            }
            return redirect()->back()->withErrors(['confirm_password' => __('password_error_3'), 'password' => __('password_error_3')])->withInput();
        }
        return redirect()->back()->
        withInput()->
        withErrors([
            'confirm_number' => __('password_error_4')
        ]);
    }

}
