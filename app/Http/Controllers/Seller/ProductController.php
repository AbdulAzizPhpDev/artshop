<?php

namespace App\Http\Controllers\Seller;

use App\Catalog;
use App\Helper\ImageMaker;
use App\Http\Controllers\Controller;
use App\PaymentType;
use App\Product;
use App\OrderList;
use App\ProductImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected $paginate = 10;

    public function index()
    {
        $products = Product::where([
            ['is_active', true],
            ['merchant_id', auth()->user()->id],
        ])->with(['category'])->paginate($this->paginate);
//        dd($products);
        return view('seller.pages.products.index', compact('products'));
    }

    public function archive()
    {
        $products = Product::where([
            ['is_active', false],
            ['merchant_id', auth()->user()->id],
        ])->with(['category'])->paginate($this->paginate);
        return view('seller.pages.products.archive', compact('products'));
    }

    public function add()
    {
        $catalogs = Catalog::where('parent_id', 0)->get();
        return view('seller.pages.products.add', compact('catalogs'));
    }

    public function store(Request $request)
    {


        $imageTypeArray = array
        (
            2 => 'jpg',
            3 => 'png',
        );
        $request->validate([
            'name_uz' => 'required|min:3|max:64',
            'description_uz' => 'max:256',
            'name_ru' => 'required|min:3|max:64',
            'description_ru' => 'max:256',
            'made_in' => 'max:32',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5048|dimensions:max_width=3000,max_height=2000',
        ]);

        if ($request->catalog == "null") {
            return back()->withErrors([
                'catalog' => 'выбрать категория '
            ])->withInput();
        } elseif ($request->category == "null") {
            return back()->withErrors([
                'category' => 'выбрать субкатегория '
            ])->withInput();
        }

        if (!is_null($request->maker_phone)) {
            $pattern = "/^\+998\d{9}$/";
            if (!preg_match($pattern, $request->maker_phone)) {
                return back()->withInput()->withErrors([
                    'maker_name' => 'телефон неправильного формата'
                ]);
            }
        }


        $product = new Product();
        if (isset($request->quantity)) {
            if ($request->quantity > 0) {
                $product->quantity = $request->quantity;
            } else {
                $product->quantity = 1;
            }
        } else {
            $product->quantity = 1;
        }
        if ($request->cash == 1) {
            $product->is_cash = true;
        } else {
            $product->is_cash = false;
        }
        $product->is_new = true;
        $product->merchant_id = auth()->user()->id;
        $product->name_uz = $request->name_uz;
        $product->description_uz = $request->description_uz;
        $product->name_ru = $request->name_ru;
        $product->description_ru = $request->description_ru;
        $product->selling_type = $request->sell_type;
        $product->price = $request->price;
        $product->made_in = $request->made_in;
        $product->maker_phone = $request->maker_phone;
        $product->maker_name = $request->maker_name;
        $product->catalog_id = $request->category;

        if (isset($request->image)) {
            list($width, $height, $type) = getimagesize($request->image);
            $path = "seller/product/" . str_replace(" ", "_", $request->name_uz) . "/";
            $name = preg_replace(' / \./', '', microtime(true)) . '.' . $imageTypeArray[$type];
            $product->image = (new ImageMaker(302, 187))->makeImage($request->image, $path, $name);
        }
        $product->save();
        if ($product->maker_phone != null) {
            $data = '{"messages":[{"recipient":"' . $product->maker_phone . '","message-id":"itsm' . $product->id . '","sms":{"originator": "3700","content":{"text":"Ваше изделие № ' . $product->id . ' было выставлено на продажу.   Artshop.itsm.uz"}}}]}';
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


        return redirect()->route('seller.product.index', app()->getLocale());
    }

    public function edit($lang, $id)
    {
        $redirect_url = url()->previous();
        $product = Product::where('id', $id)->with(['category'])->first();
        $catalogs = Catalog::where('parent_id', 0)->get();
        $subcatalogs = Catalog::where([
            ['parent_id', $product->category->parent->id],
            ['is_active', true]
        ])->orderBy('name_uz')->get();

        $maker_phone = "";

        if (strlen($product->maker_phone) == 13) {
            $phone = $product->maker_phone;
            for ($i = 0, $iMax = strlen($phone); $i < $iMax; $i++) {
                if ($i == 3)
                    $maker_phone .= $phone[$i] . '-';
                elseif ($i == 5)
                    $maker_phone .= $phone[$i] . '-';
                elseif ($i == 8)
                    $maker_phone .= $phone[$i] . '-';
                elseif ($i == 10)
                    $maker_phone .= $phone[$i] . '-';
                else
                    $maker_phone .= $phone[$i];

            }
        }
        return view('seller.pages.products.edit', compact('product', 'catalogs', 'subcatalogs', 'redirect_url', 'maker_phone'));
    }

    public function update(Request $request)
    {

        $product = Product::where([
            ['id', $request->id],
            ['merchant_id', auth()->user()->id]
        ])->first();
        if ($product == null)
            return redirect()->route('seller.product.index')->withErrors([
                "update_error" => "Product is imposable to update"
            ]);
        $imageTypeArray = array
        (
            2 => 'jpg',
            3 => 'png',
        );
        $request->validate([
            'name_uz' => 'required|min:3|max:64',
            'description_uz' => 'max:256',
            'name_ru' => 'required|min:3|max:64',
            'description_ru' => 'max:256',
            'price' => 'required',
            'made_in' => 'max:32',
            'image' => 'image|mimes:jpeg,png,jpg|max:5048|dimensions:max_width=3000,max_height=2000',
        ]);
        if ($request->catalog == "null") {
            return back()->withErrors([
                'catalog' => 'выбрать категория '
            ])->withInput();
        } elseif ($request->category == "null") {
            return back()->withErrors([
                'category' => 'выбрать субкатегория '
            ])->withInput();
        }

        if (!is_null($request->maker_phone)) {
            $pattern = "/^\+998\d{9}$/";
            if (!preg_match($pattern, $request->maker_phone)) {
                return back()->withInput()->withErrors([
                    'maker_name' => 'phone is wrong format'
                ]);
            }
        }

        if (isset($request->image)) {
            if (Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            list($width, $height, $type) = getimagesize($request->image);
            $path = 'seller/product/' . str_replace(' ', '_', trim($request->name_uz)) . '/';
            $name = preg_replace(' / \./', '', microtime(true)) . '.' . $imageTypeArray[$type];
            $product->image = (new ImageMaker(302, 187))->makeImage($request->image, $path, $name);
        }

        $product->name_uz = $request->name_uz;
        $product->description_uz = $request->description_uz;
        $product->name_ru = $request->name_ru;
        $product->description_ru = $request->description_ru;
        $product->selling_type = $request->sell_type;
        $product->price = $request->price;
        $product->made_in = $request->made_in;
        $product->catalog_id = $request->category;
        $product->maker_phone = $request->maker_phone;
        $product->maker_name = $request->maker_name;
        if (isset($request->quantity)) {
            if ($request->quantity > 0) {
                $product->quantity = $request->quantity;
            } else {
                $product->quantity = 1;
            }
        } else {
            $product->quantity = 1;
        }
        if ($request->cash == 1) {
            $product->is_cash = true;
        } else {
            $product->is_cash = false;
        }
        $product->save();

        return redirect()->to($request->redirect_url)->withErrors([
            "success" => "($product->name_uz)".__('product_update_info')
        ]);
    }

    public function updateStatus(Request $request)
    {
        $data = Product::findOrFail($request->id);
        $data->is_active = filter_var($request->status, FILTER_VALIDATE_BOOLEAN);
        $data->save();
        return response()->json([
            "status" => $data->is_active
        ]);
    }

    public function search($lang, $search, $sell_type)
    {
        $products = null;

        if ($search == "null") {
            $products = Product::where([
                ['merchant_id', auth()->user()->id],
                ['is_active', true],
                ['selling_type', $sell_type]
            ])->
            with(['category'])->paginate($this->paginate);
        } else {
            if ($sell_type == 'all') {
                if (preg_match('/[А-Яа-яЁё]/u', $search)) {
                    $products = Product::where([
                        ['merchant_id', auth()->user()->id],
                        ['is_active', true],
                        ['name_ru', 'LIKE', "%$search%"]
                    ])->
                    with(['category'])->paginate($this->paginate);
                } else {
                    $products = Product::where([
                        ['merchant_id', auth()->user()->id],
                        ['is_active', true],
                        ['name_uz', 'LIKE', "%$search%"]
                    ])->
                    with(['category'])->paginate($this->paginate);
                }
            } else {

                if (preg_match('/[А-Яа-яЁё]/u', $search)) {
                    $products = Product::where([
                        ['merchant_id', auth()->user()->id],
                        ['is_active', true],
                        ['selling_type', $sell_type],
                        ['name_ru', 'LIKE', "%$search%"]
                    ])->
                    with(['category'])->paginate($this->paginate);
                } else {
                    $products = Product::where([
                        ['merchant_id', auth()->user()->id],
                        ['is_active', true],
                        ['selling_type', $sell_type],
                        ['name_uz', 'LIKE', "%$search%"]
                    ])->
                    with(['category'])->paginate($this->paginate);
                }
            }
        }

        return view('seller.pages.products.index', compact('products', 'search', 'sell_type'));
    }

    public function postSearch(Request $request)
    {
//        dd($request->all());
        if ($request->search != null) {

            $text = $request->search;
        } else {

            $text = "null";
        }

//        dd($request->all());
        if ($request->search == null && $request->sell_type == "all")
            return redirect()->route('seller.product.index', app()->getLocale());
//        elseif ((boolean)$request->is_active)
//            return redirect()->route('seller.product.post.search', ["search" => $request->search]);
//        elseif ((boolean)$request->is_active)
//            return redirect()->route('seller.product.post.search', ["search" => $request->search]);
//        elseif ((boolean)$request->is_active)
        return redirect()->route('seller.product.post.search', ["search" => $text, 'sell_type' => $request->sell_type, 'lang' => app()->getLocale()]);

    }

    public function searchArchive($lang, $search, $sell_type)
    {
        $products = null;

        if ($search == "null") {
            $products = Product::where([
                ['merchant_id', auth()->user()->id],
                ['is_active', false],
                ['selling_type', $sell_type]
            ])->
            with(['category'])->paginate($this->paginate);
        } else {
            if ($sell_type == 'all') {
                if (preg_match('/[А-Яа-яЁё]/u', $search)) {
                    $products = Product::where([
                        ['merchant_id', auth()->user()->id],
                        ['is_active', false],
                        ['name_ru', 'LIKE', "%$search%"]
                    ])->
                    with(['category'])->paginate($this->paginate);
                } else {
                    $products = Product::where([
                        ['merchant_id', auth()->user()->id],
                        ['is_active', false],
                        ['name_uz', 'LIKE', "%$search%"]
                    ])->
                    with(['category'])->paginate($this->paginate);
                }
            } else {

                if (preg_match('/[А-Яа-яЁё]/u', $search)) {
                    $products = Product::where([
                        ['merchant_id', auth()->user()->id],
                        ['is_active', false],
                        ['selling_type', $sell_type],
                        ['name_ru', 'LIKE', "%$search%"]
                    ])->
                    with(['category'])->paginate($this->paginate);
                } else {
                    $products = Product::where([
                        ['merchant_id', auth()->user()->id],
                        ['is_active', false],
                        ['selling_type', $sell_type],
                        ['name_uz', 'LIKE', "%$search%"]
                    ])->
                    with(['category'])->paginate($this->paginate);
                }
            }
        }
        return view('seller.pages.products.archive', compact('products', 'search', 'sell_type'));
    }

    public function postSearchArchive(Request $request)
    {
        if ($request->search != null) {

            $text = $request->search;
        } else {

            $text = "null";
        }
        if ($request->search == null && $request->sell_type == "all") {
            return redirect()->route('seller.product.archive', app()->getLocale());
        }

//        elseif ((boolean)$request->is_active)
//            return redirect()->route('seller.product.post.search', ["search" => $request->search]);
//        elseif ((boolean)$request->is_active)
//            return redirect()->route('seller.product.post.search', ["search" => $request->search]);
//        elseif ((boolean)$request->is_active)
        return redirect()->route('seller.product.post.archive.search', ["search" => $text, 'sell_type' => $request->sell_type, 'lang' => app()->getLocale()]);

    }

    public function destroy(Request $request)
    {

        $product = Product::where('id', $request->id)->first();

        OrderList::where('product_id', $product->id)->delete();
        MostViewedProduct::where('product_id', $product->id)->delete();


        $images = ProductImages::where('product_id', $request->id)->get();
        if (Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        if (!empty($images)) {
            foreach ($images as $image) {
                if (Storage::disk('public')->exists($image)) {
                    Storage::disk('public')->delete($image);
                }
            }
            ProductImages::where('product_id', $request->id)->delete();
        }
        $check = $product->delete();
        return response()->json([
            'id' => $check
        ]);
    }


}
