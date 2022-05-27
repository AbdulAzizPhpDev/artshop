<?php

namespace App\Http\Controllers\User;

use App\Catalog;
use App\Http\Controllers\Controller;
use App\Product;
use App\Region;
use App\User;
use App\Address;
use App\PaymentSystemTool;
use App\Requisite;

use Illuminate\Http\Request;

class CatalogController extends Controller
{
    protected $paginate = 12;

    public function index()
    {

        $regions = Region::where('parent_id', 0)->get();


        $products = Product::where([
            ['is_active', true]
        ])->take($this->paginate)->get();

        $max = $products->max('price');
        $min = $products->min('price');
        return view('user.pages.filters.products-by-catalogs', compact('products', 'regions', 'max', 'min'));
    }

    public function productsByCatalog($lang, $catalog_id)
    {
        $regions = Region::where('parent_id', 0)->get();
        $catalog = Catalog::where([
            ['is_active', true],
            ['id', $catalog_id]
        ])->with(['children'])->first();
        $category_ids = $catalog->children->pluck('id')->toArray();
        $products = Product::where([
            ['is_active', true]
        ])->
        whereIn('catalog_id', $category_ids)->
        get();
        $max = $products->max('price');
        $min = $products->min('price');
        return view('user.pages.filters.products-by-catalog', compact('catalog', 'products', 'regions', 'max', 'min'));
    }

    public function searchInCatalog(Request $request)
    {

        $regions = Region::where('parent_id', 0)->get();
        $catalog = Catalog::where([
            ['is_active', true],
            ['id', $request->catalog_id]
        ])->with(['children'])->first();

        $category_ids = $catalog->children->pluck('id')->toArray();
        $products = [];

        if (isset($request->selling_type) &&
            (in_array("retail", $request->selling_type, TRUE) ||
                in_array("wholesale", $request->selling_type, TRUE))) {

            //Region
            if (isset($request->region)) {
//district
                if (isset($request->district)) {
                    //                        payment
                    if (isset($request->payment)) {

                        $merchant_payment = [];
                        $merchant_payment_bank = [];


                        if (in_array("cash", $request->payment, TRUE) &&
                            in_array("online", $request->payment, TRUE)) {

                            $merchant_payment = PaymentSystemTool::where(
                                [
                                    ['is_cash', true],
                                    ['is_active', true]
                                ]
                            )->get()->pluck('seller_id')->toArray();

                        } elseif (in_array("cash", $request->payment, TRUE)) {

                            $merchant_payment = PaymentSystemTool::where(
                                [
                                    ['is_cash', true],
                                ]
                            )->get()->pluck('seller_id')->toArray();

                        } elseif (in_array("online", $request->payment, TRUE)) {

                            $merchant_payment = PaymentSystemTool::where(
                                [
                                    ['is_active', true]
                                ]
                            )->get()->pluck('seller_id')->toArray();
                        }
                        if (in_array("bank", $request->payment, TRUE)) {
                            $merchant_payment_bank = Requisite::get()->pluck('seller_id')->toArray();
                        }


                        if ($merchant_payment_bank != null && $merchant_payment != null) {

                            $new_array = array_unique(array_merge($merchant_payment, $merchant_payment_bank));

                            $merchant_ids = Address::whereIn('region_id', $request->region)->
                            where('role_id', 3)->get()->
                            pluck('user_id')->toArray();

                            $merchant_ids_d = Address::whereIn('region_id', $request->region)->
                            whereIn('district_id', $request->district)->
                            where('role_id', 3)->
                            get()->pluck('user_id')->toArray();


                            $products = Product::where([
                                ['is_active', true],
                            ])->
                            whereIn('catalog_id', $category_ids)->
                            whereIn('selling_type', $request->selling_type)->
                            whereIn('merchant_id', $merchant_ids)->
                            whereIn('merchant_id', $merchant_ids_d)->
                            whereIn('merchant_id', $new_array)->
                            whereBetween('price', [$request->min, $request->max])->
                            get();


                        } elseif ($merchant_payment_bank != null) {

                            $merchant_ids = Address::whereIn('region_id', $request->region)->where('role_id', 3)->get()->pluck('user_id')->toArray();
                            $merchant_ids_d = Address::whereIn('region_id', $request->region)->
                            whereIn('district_id', $request->region)->
                            where('role_id', 3)->
                            get()->pluck('user_id')->toArray();

                            $products = Product::where([
                                ['is_active', true],
                            ])->
                            whereIn('catalog_id', $category_ids)->
                            whereIn('selling_type', $request->selling_type)->
                            whereIn('merchant_id', $merchant_ids)->
                            whereIn('merchant_id', $merchant_ids_d)->
                            whereIn('merchant_id', $merchant_payment_bank)->
                            whereBetween('price', [$request->min, $request->max])->
                            get();
                        } else {
                            $merchant_ids = Address::whereIn('region_id', $request->region)->where('role_id', 3)->get()->pluck('user_id')->toArray();
                            $merchant_ids_d = Address::whereIn('region_id', $request->region)->
                            whereIn('district_id', $request->region)->
                            where('role_id', 3)->
                            get()->pluck('user_id')->toArray();

                            $products = Product::where([
                                ['is_active', true],
                            ])->
                            whereIn('catalog_id', $category_ids)->
                            whereIn('selling_type', $request->selling_type)->
                            whereIn('merchant_id', $merchant_ids)->
                            whereIn('merchant_id', $merchant_ids_d)->
                            whereIn('merchant_id', $merchant_payment)->
                            whereBetween('price', [$request->min, $request->max])->
                            get();

                        }

                    } else {
                        //payment not set

                        $merchant_ids = Address::whereIn('region_id', $request->region)->where('role_id', 3)->get()->pluck('user_id')->toArray();
                        $merchant_ids_d = Address::whereIn('region_id', $request->region)->
                        whereIn('district_id', $request->region)->
                        where('role_id', 3)->
                        get()->pluck('user_id')->toArray();

                        $products = Product::where([
                            ['is_active', true],
                        ])->
                        whereIn('catalog_id', $category_ids)->
                        whereIn('selling_type', $request->selling_type)->
                        whereIn('merchant_id', $merchant_ids)->
                        whereIn('merchant_id', $merchant_ids_d)->
                        whereBetween('price', [$request->min, $request->max])->
                        get();
                    }
                    //                        payment end


                } else {

                    //                        payment
                    if (isset($request->payment)) {

                        $merchant_payment = [];
                        $merchant_payment_bank = [];

                        if (in_array("cash", $request->payment, TRUE) &&
                            in_array("online", $request->payment, TRUE)) {

                            $merchant_payment = PaymentSystemTool::where(
                                [
                                    ['is_cash', true],
                                    ['is_active', true]
                                ]
                            )->get()->pluck('seller_id')->toArray();

                        } elseif (in_array("cash", $request->payment, TRUE)) {

                            $merchant_payment = PaymentSystemTool::where(
                                [
                                    ['is_cash', true],
                                ]
                            )->get()->pluck('seller_id')->toArray();

                        } elseif (in_array("online", $request->payment, TRUE)) {

                            $merchant_payment = PaymentSystemTool::where(
                                [
                                    ['is_active', true]
                                ]
                            )->get()->pluck('seller_id')->toArray();
                        }
                        if (in_array("bank", $request->payment, TRUE)) {
                            $merchant_payment_bank = Requisite::get()->pluck('seller_id')->toArray();
                        }


                        if ($merchant_payment_bank != null && $merchant_payment != null) {

                            $new_array = array_unique(array_merge($merchant_payment, $merchant_payment_bank));
                            $merchant_ids = Address::whereIn('region_id', $request->region)->where('role_id', 3)->get()->pluck('user_id')->toArray();


                            $products = Product::where([
                                ['is_active', true],
                            ])->
                            whereIn('catalog_id', $category_ids)->
                            whereIn('selling_type', $request->selling_type)->
                            whereIn('merchant_id', $merchant_ids)->
                            whereIn('merchant_id', $new_array)->
                            whereBetween('price', [$request->min, $request->max])->
                            get();


                        } elseif ($merchant_payment_bank != null) {
                            $merchant_ids = Address::whereIn('region_id', $request->region)->where('role_id', 3)->get()->pluck('user_id')->toArray();

                            $products = Product::where([
                                ['is_active', true],
                            ])->
                            whereIn('catalog_id', $category_ids)->
                            whereIn('selling_type', $request->selling_type)->
                            whereIn('merchant_id', $merchant_ids)->

                            whereIn('merchant_id', $merchant_payment_bank)->
                            whereBetween('price', [$request->min, $request->max])->
                            get();
                        } else {
                            $merchant_ids = Address::whereIn('region_id', $request->region)->where('role_id', 3)->get()->pluck('user_id')->toArray();

                            $products = Product::where([
                                ['is_active', true],
                            ])->
                            whereIn('catalog_id', $category_ids)->
                            whereIn('selling_type', $request->selling_type)->
                            whereIn('merchant_id', $merchant_ids)->
                            whereIn('merchant_id', $merchant_payment)->
                            whereBetween('price', [$request->min, $request->max])->
                            get();

                        }

                    } else {
                        //payment not set

                        $merchant_ids = Address::whereIn('region_id', $request->region)->where('role_id', 3)->get()->pluck('user_id')->toArray();

                        $products = Product::where([
                            ['is_active', true],
                        ])->
                        whereIn('catalog_id', $category_ids)->
                        whereIn('merchant_id', $merchant_ids)->
                        whereBetween('price', [$request->min, $request->max])->
                        get();
                    }
                    //                        payment end
                }

                //end district

            } else {

                //                        payment
                if (isset($request->payment)) {

                    $merchant_payment = [];
                    $merchant_payment_bank = [];

                    if (in_array("cash", $request->payment, TRUE) &&
                        in_array("online", $request->payment, TRUE)) {

                        $merchant_payment = PaymentSystemTool::where(
                            [
                                ['is_cash', true],
                                ['is_active', true]
                            ]
                        )->get()->pluck('seller_id')->toArray();

                    } elseif (in_array("cash", $request->payment, TRUE)) {

                        $merchant_payment = PaymentSystemTool::where(
                            [
                                ['is_cash', true],
                            ]
                        )->get()->pluck('seller_id')->toArray();

                    } elseif (in_array("online", $request->payment, TRUE)) {

                        $merchant_payment = PaymentSystemTool::where(
                            [
                                ['is_active', true]
                            ]
                        )->get()->pluck('seller_id')->toArray();
                    }
                    if (in_array("bank", $request->payment, TRUE)) {
                        $merchant_payment_bank = Requisite::get()->pluck('seller_id')->toArray();
                    }


                    if ($merchant_payment_bank != null && $merchant_payment != null) {

                        $new_array = array_unique(array_merge($merchant_payment, $merchant_payment_bank));

                        $products = Product::where([
                            ['is_active', true],
                        ])->
                        whereIn('catalog_id', $category_ids)->
                        whereIn('selling_type', $request->selling_type)->

                        whereIn('merchant_id', $new_array)->
                        whereBetween('price', [$request->min, $request->max])->
                        get();


                    } elseif ($merchant_payment_bank != null) {

                        $products = Product::where([
                            ['is_active', true],
                        ])->
                        whereIn('catalog_id', $category_ids)->
                        whereIn('selling_type', $request->selling_type)->

                        whereIn('merchant_id', $merchant_payment_bank)->
                        whereBetween('price', [$request->min, $request->max])->
                        get();
                    } else {
                        $products = Product::where([
                            ['is_active', true],
                        ])->
                        whereIn('catalog_id', $category_ids)->
                        whereIn('selling_type', $request->selling_type)->
                        whereIn('merchant_id', $merchant_payment)->
                        whereBetween('price', [$request->min, $request->max])->
                        get();
                    }

                } else {
                    //payment not set
                    $products = Product::where([
                        ['is_active', true],
                    ])->
                    whereIn('catalog_id', $category_ids)->
                    whereIn('selling_type', $request->selling_type)->
                    whereBetween('price', [$request->min, $request->max])->
                    get();
                }
                //                        payment end
            }
            // end Region

        } else {
//            region
            if (isset($request->region)) {

//                district
                if (isset($request->district)) {

                    if (isset($request->payment)) {
                        $merchant_payment = null;
                        $merchant_payment_bank = null;
                        if (in_array("cash", $request->payment, TRUE) && in_array("online", $request->payment, TRUE)) {
                            $merchant_payment = PaymentSystemTool::where(
                                [
                                    ['is_cash', true],
                                    ['is_active', true]
                                ]
                            )->get()->pluck('seller_id')->toArray();
                        } elseif (in_array("cash", $request->payment, TRUE)) {
                            $merchant_payment = PaymentSystemTool::where(
                                [
                                    ['is_cash', true],
                                    ['is_active', false]
                                ]
                            )->get()->pluck('seller_id')->toArray();
                        } elseif (in_array("online", $request->payment, TRUE)) {
                            $merchant_payment = PaymentSystemTool::where(
                                [
                                    ['is_cash', false],
                                    ['is_active', true]
                                ]
                            )->get()->pluck('seller_id')->toArray();
                        }
                        if (in_array("bank", $request->payment, TRUE)) {
                            $merchant_payment_bank = Requisite::get()->pluck('seller_id')->toArray();
                        }

                        if ($merchant_payment_bank) {
                            $new_array = array_unique(array_merge($merchant_payment, $merchant_payment_bank));
                            $merchant_ids = Address::whereIn('region_id', $request->region)->where('role_id', 3)->get()->pluck('user_id')->toArray();
                            $merchant_ids_d = Address::whereIn('region_id', $request->region)->
                            whereIn('district_id', $request->region)->
                            where('role_id', 3)->
                            get()->pluck('user_id')->toArray();

                            $products = Product::where([
                                ['is_active', true],
                            ])->
                            whereIn('catalog_id', $category_ids)->
                            whereIn('merchant_id', $merchant_ids)->
                            whereIn('merchant_id', $merchant_ids_d)->
                            whereIn('merchant_id', $new_array)->
                            whereBetween('price', [$request->min, $request->max])->
                            get();
                        } else {
                            $merchant_ids = Address::whereIn('region_id', $request->region)->where('role_id', 3)->get()->pluck('user_id')->toArray();
                            $merchant_ids_d = Address::whereIn('region_id', $request->region)->
                            whereIn('district_id', $request->region)->
                            where('role_id', 3)->
                            get()->pluck('user_id')->toArray();

                            $products = Product::where([
                                ['is_active', true],
                            ])->
                            whereIn('catalog_id', $category_ids)->
                            whereIn('merchant_id', $merchant_ids)->
                            whereIn('merchant_id', $merchant_ids_d)->
                            whereIn('merchant_id', $merchant_payment)->
                            whereBetween('price', [$request->min, $request->max])->
                            get();

                        }

                    } else {

                        $merchant_ids = Address::whereIn('region_id', $request->region)->where('role_id', 3)->get()->pluck('user_id')->toArray();
                        $merchant_ids_d = Address::whereIn('region_id', $request->region)->
                        whereIn('district_id', $request->region)->
                        where('role_id', 3)->
                        get()->pluck('user_id')->toArray();

                        $products = Product::where([
                            ['is_active', true],
                        ])->
                        whereIn('catalog_id', $category_ids)->
                        whereIn('merchant_id', $merchant_ids)->
                        whereIn('merchant_id', $merchant_ids_d)->
                        whereBetween('price', [$request->min, $request->max])->
                        get();
                    }

                } else {

                    if (isset($request->payment)) {
                        $merchant_payment = null;
                        $merchant_payment_bank = null;
                        if (in_array("cash", $request->payment, TRUE) && in_array("online", $request->payment, TRUE)) {
                            $merchant_payment = PaymentSystemTool::where(
                                [
                                    ['is_cash', true],
                                    ['is_active', true]
                                ]
                            )->get()->pluck('seller_id')->toArray();
                        } elseif (in_array("cash", $request->payment, TRUE)) {
                            $merchant_payment = PaymentSystemTool::where(
                                [
                                    ['is_cash', true],
                                    ['is_active', false]
                                ]
                            )->get()->pluck('seller_id')->toArray();
                        } elseif (in_array("online", $request->payment, TRUE)) {
                            $merchant_payment = PaymentSystemTool::where(
                                [
                                    ['is_cash', false],
                                    ['is_active', true]
                                ]
                            )->get()->pluck('seller_id')->toArray();
                        }
                        if (in_array("bank", $request->payment, TRUE)) {
                            $merchant_payment_bank = Requisite::get()->pluck('seller_id')->toArray();
                        }

                        if ($merchant_payment_bank) {
                            $new_array = array_unique(array_merge($merchant_payment, $merchant_payment_bank));

                            $merchant_ids = Address::whereIn('region_id', $request->region)->
                            where('role_id', 3)->get()->pluck('user_id')->toArray();

                            $products = Product::where([
                                ['is_active', true],
                            ])->
                            whereIn('catalog_id', $category_ids)->
                            whereIn('merchant_id', $merchant_ids)->
                            whereIn('merchant_id', $new_array)->
                            whereBetween('price', [$request->min, $request->max])->
                            get();
                        } else {

                            $merchant_ids = Address::whereIn('region_id', $request->region)->
                            where('role_id', 3)->get()->pluck('user_id')->toArray();

                            $products = Product::where([
                                ['is_active', true],
                            ])->
                            whereIn('catalog_id', $category_ids)->
                            whereIn('merchant_id', $merchant_ids)->
                            whereIn('merchant_id', $merchant_payment)->
                            whereBetween('price', [$request->min, $request->max])->
                            get();
                        }
                    } else {

                        $merchant_ids = Address::whereIn('region_id', $request->region)->where('role_id', 3)->get()->pluck('user_id')->toArray();


                        $products = Product::where([
                            ['is_active', true],
                        ])->
                        whereIn('catalog_id', $category_ids)->
                        whereIn('merchant_id', $merchant_ids)->
                        whereBetween('price', [$request->min, $request->max])->
                        get();
                    }
                }
//                end district

            } else {

                //                        payment
                if (isset($request->payment)) {

                    $merchant_payment = [];
                    $merchant_payment_bank = [];

                    if (in_array("cash", $request->payment, TRUE) &&
                        in_array("online", $request->payment, TRUE)) {

                        $merchant_payment = PaymentSystemTool::where(
                            [
                                ['is_cash', true],
                                ['is_active', true]
                            ]
                        )->get()->pluck('seller_id')->toArray();

                    } elseif (in_array("cash", $request->payment, TRUE)) {

                        $merchant_payment = PaymentSystemTool::where(
                            [
                                ['is_cash', true],
                            ]
                        )->get()->pluck('seller_id')->toArray();

                    } elseif (in_array("online", $request->payment, TRUE)) {

                        $merchant_payment = PaymentSystemTool::where(
                            [
                                ['is_active', true]
                            ]
                        )->get()->pluck('seller_id')->toArray();
                    }
                    if (in_array("bank", $request->payment, TRUE)) {
                        $merchant_payment_bank = Requisite::get()->pluck('seller_id')->toArray();
                    }


                    if ($merchant_payment_bank != null && $merchant_payment != null) {

                        $new_array = array_unique(array_merge($merchant_payment, $merchant_payment_bank));

                        $products = Product::where([
                            ['is_active', true],
                        ])->
                        whereIn('catalog_id', $category_ids)->
                        whereIn('merchant_id', $new_array)->
                        whereBetween('price', [$request->min, $request->max])->
                        get();


                    } elseif ($merchant_payment_bank != null) {

                        $products = Product::where([
                            ['is_active', true],
                        ])->
                        whereIn('catalog_id', $category_ids)->
                        whereIn('merchant_id', $merchant_payment_bank)->
                        whereBetween('price', [$request->min, $request->max])->
                        get();
                    } else {
                        $products = Product::where([
                            ['is_active', true],
                        ])->
                        whereIn('catalog_id', $category_ids)->
                        whereIn('merchant_id', $merchant_payment)->
                        whereBetween('price', [$request->min, $request->max])->
                        get();
                    }

                } else {
                    //payment not set
                    $products = Product::where([
                        ['is_active', true],
                    ])->
                    whereIn('catalog_id', $category_ids)->
                    whereBetween('price', [$request->min, $request->max])->
                    get();
                }
                //                        payment end

            }
//            end region

        }
        return response()->json([
            'products' => $products
        ]);

    }

    public function searchInCatalogs(Request $request)
    {

        $regions = Region::where('parent_id', 0)->get();

        $products = [];

        if (isset($request->selling_type) &&
            (in_array("retail", $request->selling_type, TRUE) ||
                in_array("wholesale", $request->selling_type, TRUE))) {

            //Region
            if (isset($request->region)) {
//district
                if (isset($request->district)) {
                    //                        payment
                    if (isset($request->payment)) {

                        $merchant_payment = [];
                        $merchant_payment_bank = [];


                        if (in_array("cash", $request->payment, TRUE) &&
                            in_array("online", $request->payment, TRUE)) {

                            $merchant_payment = PaymentSystemTool::where(
                                [
                                    ['is_cash', true],
                                    ['is_active', true]
                                ]
                            )->get()->pluck('seller_id')->toArray();

                        } elseif (in_array("cash", $request->payment, TRUE)) {

                            $merchant_payment = PaymentSystemTool::where(
                                [
                                    ['is_cash', true],
                                ]
                            )->get()->pluck('seller_id')->toArray();

                        } elseif (in_array("online", $request->payment, TRUE)) {

                            $merchant_payment = PaymentSystemTool::where(
                                [
                                    ['is_active', true]
                                ]
                            )->get()->pluck('seller_id')->toArray();
                        }
                        if (in_array("bank", $request->payment, TRUE)) {
                            $merchant_payment_bank = Requisite::get()->pluck('seller_id')->toArray();
                        }


                        if ($merchant_payment_bank != null && $merchant_payment != null) {

                            $new_array = array_unique(array_merge($merchant_payment, $merchant_payment_bank));

                            $merchant_ids = Address::whereIn('region_id', $request->region)->
                            where('role_id', 3)->get()->
                            pluck('user_id')->toArray();

                            $merchant_ids_d = Address::whereIn('region_id', $request->region)->
                            whereIn('district_id', $request->district)->
                            where('role_id', 3)->
                            get()->pluck('user_id')->toArray();


                            $products = Product::where([
                                ['is_active', true],
                            ])->

                            whereIn('selling_type', $request->selling_type)->
                            whereIn('merchant_id', $merchant_ids)->
                            whereIn('merchant_id', $merchant_ids_d)->
                            whereIn('merchant_id', $new_array)->
                            whereBetween('price', [$request->min, $request->max])->
                            get();


                        } elseif ($merchant_payment_bank != null) {

                            $merchant_ids = Address::whereIn('region_id', $request->region)->where('role_id', 3)->get()->pluck('user_id')->toArray();
                            $merchant_ids_d = Address::whereIn('region_id', $request->region)->
                            whereIn('district_id', $request->region)->
                            where('role_id', 3)->
                            get()->pluck('user_id')->toArray();

                            $products = Product::where([
                                ['is_active', true],
                            ])->

                            whereIn('selling_type', $request->selling_type)->
                            whereIn('merchant_id', $merchant_ids)->
                            whereIn('merchant_id', $merchant_ids_d)->
                            whereIn('merchant_id', $merchant_payment_bank)->
                            whereBetween('price', [$request->min, $request->max])->
                            get();
                        } else {
                            $merchant_ids = Address::whereIn('region_id', $request->region)->where('role_id', 3)->get()->pluck('user_id')->toArray();
                            $merchant_ids_d = Address::whereIn('region_id', $request->region)->
                            whereIn('district_id', $request->region)->
                            where('role_id', 3)->
                            get()->pluck('user_id')->toArray();

                            $products = Product::where([
                                ['is_active', true],
                            ])->

                            whereIn('selling_type', $request->selling_type)->
                            whereIn('merchant_id', $merchant_ids)->
                            whereIn('merchant_id', $merchant_ids_d)->
                            whereIn('merchant_id', $merchant_payment)->
                            whereBetween('price', [$request->min, $request->max])->
                            get();

                        }

                    } else {
                        //payment not set

                        $merchant_ids = Address::whereIn('region_id', $request->region)->where('role_id', 3)->get()->pluck('user_id')->toArray();
                        $merchant_ids_d = Address::whereIn('region_id', $request->region)->
                        whereIn('district_id', $request->region)->
                        where('role_id', 3)->
                        get()->pluck('user_id')->toArray();

                        $products = Product::where([
                            ['is_active', true],
                        ])->

                        whereIn('selling_type', $request->selling_type)->
                        whereIn('merchant_id', $merchant_ids)->
                        whereIn('merchant_id', $merchant_ids_d)->
                        whereBetween('price', [$request->min, $request->max])->
                        get();
                    }
                    //                        payment end


                } else {
                    //                        payment
                    if (isset($request->payment)) {

                        $merchant_payment = [];
                        $merchant_payment_bank = [];

                        if (in_array("cash", $request->payment, TRUE) &&
                            in_array("online", $request->payment, TRUE)) {

                            $merchant_payment = PaymentSystemTool::where(
                                [
                                    ['is_cash', true],
                                    ['is_active', true]
                                ]
                            )->get()->pluck('seller_id')->toArray();

                        } elseif (in_array("cash", $request->payment, TRUE)) {

                            $merchant_payment = PaymentSystemTool::where(
                                [
                                    ['is_cash', true],
                                ]
                            )->get()->pluck('seller_id')->toArray();

                        } elseif (in_array("online", $request->payment, TRUE)) {

                            $merchant_payment = PaymentSystemTool::where(
                                [
                                    ['is_active', true]
                                ]
                            )->get()->pluck('seller_id')->toArray();
                        }
                        if (in_array("bank", $request->payment, TRUE)) {
                            $merchant_payment_bank = Requisite::get()->pluck('seller_id')->toArray();
                        }


                        if ($merchant_payment_bank != null && $merchant_payment != null) {

                            $new_array = array_unique(array_merge($merchant_payment, $merchant_payment_bank));
                            $merchant_ids = Address::whereIn('region_id', $request->region)->where('role_id', 3)->get()->pluck('user_id')->toArray();


                            $products = Product::where([
                                ['is_active', true],
                            ])->

                            whereIn('selling_type', $request->selling_type)->
                            whereIn('merchant_id', $merchant_ids)->
                            whereIn('merchant_id', $new_array)->
                            whereBetween('price', [$request->min, $request->max])->
                            get();


                        } elseif ($merchant_payment_bank != null) {
                            $merchant_ids = Address::whereIn('region_id', $request->region)->where('role_id', 3)->get()->pluck('user_id')->toArray();

                            $products = Product::where([
                                ['is_active', true],
                            ])->

                            whereIn('selling_type', $request->selling_type)->
                            whereIn('merchant_id', $merchant_ids)->

                            whereIn('merchant_id', $merchant_payment_bank)->
                            whereBetween('price', [$request->min, $request->max])->
                            get();
                        } else {
                            $merchant_ids = Address::whereIn('region_id', $request->region)->where('role_id', 3)->get()->pluck('user_id')->toArray();

                            $products = Product::where([
                                ['is_active', true],
                            ])->
                            whereIn('selling_type', $request->selling_type)->
                            whereIn('merchant_id', $merchant_ids)->
                            whereIn('merchant_id', $merchant_payment)->
                            whereBetween('price', [$request->min, $request->max])->
                            get();

                        }

                    } else {
                        //payment not set

                        $merchant_ids = Address::whereIn('region_id', $request->region)->where('role_id', 3)->get()->pluck('user_id')->toArray();

                        $products = Product::where([
                            ['is_active', true],
                        ])->
                        whereIn('selling_type', $request->selling_type)->
                        whereIn('merchant_id', $merchant_ids)->
                        whereBetween('price', [$request->min, $request->max])->
                        get();
                    }
                    //                        payment end
                }

                //end district

            } else {

                //                        payment
                if (isset($request->payment)) {

                    $merchant_payment = [];
                    $merchant_payment_bank = [];

                    if (in_array("cash", $request->payment, TRUE) &&
                        in_array("online", $request->payment, TRUE)) {

                        $merchant_payment = PaymentSystemTool::where(
                            [
                                ['is_cash', true],
                                ['is_active', true]
                            ]
                        )->get()->pluck('seller_id')->toArray();

                    } elseif (in_array("cash", $request->payment, TRUE)) {

                        $merchant_payment = PaymentSystemTool::where(
                            [
                                ['is_cash', true],
                            ]
                        )->get()->pluck('seller_id')->toArray();

                    } elseif (in_array("online", $request->payment, TRUE)) {

                        $merchant_payment = PaymentSystemTool::where(
                            [
                                ['is_active', true]
                            ]
                        )->get()->pluck('seller_id')->toArray();
                    }
                    if (in_array("bank", $request->payment, TRUE)) {
                        $merchant_payment_bank = Requisite::get()->pluck('seller_id')->toArray();
                    }


                    if ($merchant_payment_bank != null && $merchant_payment != null) {

                        $new_array = array_unique(array_merge($merchant_payment, $merchant_payment_bank));

                        $products = Product::where([
                            ['is_active', true],
                        ])->
                        whereIn('selling_type', $request->selling_type)->

                        whereIn('merchant_id', $new_array)->
                        whereBetween('price', [$request->min, $request->max])->
                        get();


                    } elseif ($merchant_payment_bank != null) {

                        $products = Product::where([
                            ['is_active', true],
                        ])->
                        whereIn('selling_type', $request->selling_type)->

                        whereIn('merchant_id', $merchant_payment_bank)->
                        whereBetween('price', [$request->min, $request->max])->
                        get();
                    } else {
                        $products = Product::where([
                            ['is_active', true],
                        ])->

                        whereIn('selling_type', $request->selling_type)->
                        whereIn('merchant_id', $merchant_payment)->
                        whereBetween('price', [$request->min, $request->max])->
                        get();
                    }

                } else {
                    //payment not set
                    $products = Product::where([
                        ['is_active', true],
                    ])->whereIn('selling_type', $request->selling_type)->
                    whereBetween('price', [$request->min, $request->max])->
                    get();
                }
                //                        payment end
            }
            // end Region

        } else {
//            region
            if (isset($request->region)) {
//                district
                if (isset($request->district)) {

                    if (isset($request->payment)) {

                        $merchant_payment = null;
                        $merchant_payment_bank = null;
                        if (in_array("cash", $request->payment, TRUE) && in_array("online", $request->payment, TRUE)) {
                            $merchant_payment = PaymentSystemTool::where(
                                [
                                    ['is_cash', true],
                                    ['is_active', true]
                                ]
                            )->get()->pluck('seller_id')->toArray();
                        } elseif (in_array("cash", $request->payment, TRUE)) {
                            $merchant_payment = PaymentSystemTool::where(
                                [
                                    ['is_cash', true],
                                    ['is_active', false]
                                ]
                            )->get()->pluck('seller_id')->toArray();
                        } elseif (in_array("online", $request->payment, TRUE)) {
                            $merchant_payment = PaymentSystemTool::where(
                                [
                                    ['is_cash', false],
                                    ['is_active', true]
                                ]
                            )->get()->pluck('seller_id')->toArray();
                        }
                        if (in_array("bank", $request->payment, TRUE)) {
                            $merchant_payment_bank = Requisite::get()->pluck('seller_id')->toArray();
                        }

                        if ($merchant_payment_bank) {
                            $new_array = array_unique(array_merge($merchant_payment, $merchant_payment_bank));
                            $merchant_ids = Address::whereIn('region_id', $request->region)->where('role_id', 3)->get()->pluck('user_id')->toArray();
                            $merchant_ids_d = Address::whereIn('region_id', $request->region)->
                            whereIn('district_id', $request->region)->
                            where('role_id', 3)->
                            get()->pluck('user_id')->toArray();

                            $products = Product::where([
                                ['is_active', true],
                            ])->whereIn('merchant_id', $merchant_ids)->
                            whereIn('merchant_id', $merchant_ids_d)->
                            whereIn('merchant_id', $new_array)->
                            whereBetween('price', [$request->min, $request->max])->
                            get();
                        } else {
                            $merchant_ids = Address::whereIn('region_id', $request->region)->where('role_id', 3)->get()->pluck('user_id')->toArray();
                            $merchant_ids_d = Address::whereIn('region_id', $request->region)->
                            whereIn('district_id', $request->region)->
                            where('role_id', 3)->
                            get()->pluck('user_id')->toArray();

                            $products = Product::where([
                                ['is_active', true],
                            ])->whereIn('merchant_id', $merchant_ids)->
                            whereIn('merchant_id', $merchant_ids_d)->
                            whereIn('merchant_id', $merchant_payment)->
                            whereBetween('price', [$request->min, $request->max])->
                            get();

                        }

                    } else {

                        $merchant_ids = Address::whereIn('region_id', $request->region)->
                        where('role_id', 3)->get()->pluck('user_id')->toArray();

                        $merchant_ids_d = Address::whereIn('region_id', $request->region)->
                        whereIn('district_id', $request->district)->
                        where('role_id', 3)->
                        get()->pluck('user_id')->toArray();

                        $products = Product::where([
                            ['is_active', true],
                        ])->whereIn('merchant_id', $merchant_ids)->
                        whereIn('merchant_id', $merchant_ids_d)->
                        whereBetween('price', [$request->min, $request->max])->
                        get();

                    }

                } else {

                    if (isset($request->payment)) {
                        $merchant_payment = null;
                        $merchant_payment_bank = null;
                        if (in_array("cash", $request->payment, TRUE) && in_array("online", $request->payment, TRUE)) {
                            $merchant_payment = PaymentSystemTool::where(
                                [
                                    ['is_cash', true],
                                    ['is_active', true]
                                ]
                            )->get()->pluck('seller_id')->toArray();
                        } elseif (in_array("cash", $request->payment, TRUE)) {
                            $merchant_payment = PaymentSystemTool::where(
                                [
                                    ['is_cash', true],
                                    ['is_active', false]
                                ]
                            )->get()->pluck('seller_id')->toArray();
                        } elseif (in_array("online", $request->payment, TRUE)) {
                            $merchant_payment = PaymentSystemTool::where(
                                [
                                    ['is_cash', false],
                                    ['is_active', true]
                                ]
                            )->get()->pluck('seller_id')->toArray();
                        }
                        if (in_array("bank", $request->payment, TRUE)) {
                            $merchant_payment_bank = Requisite::get()->pluck('seller_id')->toArray();
                        }

                        if ($merchant_payment_bank) {
                            $new_array = array_unique(array_merge($merchant_payment, $merchant_payment_bank));

                            $merchant_ids = Address::whereIn('region_id', $request->region)->
                            where('role_id', 3)->get()->pluck('user_id')->toArray();

                            $products = Product::where([
                                ['is_active', true],
                            ])->whereIn('merchant_id', $merchant_ids)->
                            whereIn('merchant_id', $new_array)->
                            whereBetween('price', [$request->min, $request->max])->
                            get();
                        } else {

                            $merchant_ids = Address::whereIn('region_id', $request->region)->
                            where('role_id', 3)->get()->pluck('user_id')->toArray();

                            $products = Product::where([
                                ['is_active', true],
                            ])->whereIn('merchant_id', $merchant_ids)->
                            whereIn('merchant_id', $merchant_payment)->
                            whereBetween('price', [$request->min, $request->max])->
                            get();
                        }
                    } else {
                        $merchant_ids = Address::whereIn('region_id', $request->region)->
                        where('role_id', 3)->get()->pluck('user_id')->toArray();

                        $products = Product::where([
                            ['is_active', true],
                        ])->whereIn('merchant_id', $merchant_ids)->
                        whereBetween('price', [$request->min, $request->max])->
                        get();
                    }

                }
//                end district

            } else {

                //                        payment
                if (isset($request->payment)) {

                    $merchant_payment = [];
                    $merchant_payment_bank = [];

                    if (in_array("cash", $request->payment, TRUE) &&
                        in_array("online", $request->payment, TRUE)) {

                        $merchant_payment = PaymentSystemTool::where(
                            [
                                ['is_cash', true],
                                ['is_active', true]
                            ]
                        )->get()->pluck('seller_id')->toArray();

                    } elseif (in_array("cash", $request->payment, TRUE)) {

                        $merchant_payment = PaymentSystemTool::where(
                            [
                                ['is_cash', true],
                            ]
                        )->get()->pluck('seller_id')->toArray();

                    } elseif (in_array("online", $request->payment, TRUE)) {

                        $merchant_payment = PaymentSystemTool::where(
                            [
                                ['is_active', true]
                            ]
                        )->get()->pluck('seller_id')->toArray();
                    }
                    if (in_array("bank", $request->payment, TRUE)) {
                        $merchant_payment_bank = Requisite::get()->pluck('seller_id')->toArray();
                    }


                    if ($merchant_payment_bank != null && $merchant_payment != null) {

                        $new_array = array_unique(array_merge($merchant_payment, $merchant_payment_bank));

                        $products = Product::where([
                            ['is_active', true],
                        ])->whereIn('merchant_id', $new_array)->
                        whereBetween('price', [$request->min, $request->max])->
                        get();


                    } elseif ($merchant_payment_bank != null) {

                        $products = Product::where([
                            ['is_active', true],
                        ])->whereIn('merchant_id', $merchant_payment_bank)->
                        whereBetween('price', [$request->min, $request->max])->
                        get();
                    } else {
                        $products = Product::where([
                            ['is_active', true],
                        ])->whereIn('merchant_id', $merchant_payment)->
                        whereBetween('price', [$request->min, $request->max])->
                        get();
                    }

                } else {
                    //payment not set
                    $products = Product::where([
                        ['is_active', true],
                    ])->whereBetween('price', [$request->min, $request->max])->
                    get();
                }
                //                        payment end

            }
//            end region

        }
        return response()->json([
            'products' => $products
        ]);

    }
}