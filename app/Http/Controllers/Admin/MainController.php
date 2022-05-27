<?php

namespace App\Http\Controllers\Admin;

use App\Catalog;
use App\Http\Controllers\Controller;
use App\Product;
use App\User;

class MainController extends Controller
{


    public function index()
    {

        $sellers = User::where([
            ['role_id', 3],
            ['is_active', true]
        ])->count();
        $users = User::where('role_id', 2)->count();
        $catalogs = Catalog::where('parent_id', 0)->count();
        $active_products = Product::where('is_active', true)->count();
        $not_cash_products = Product::where('is_cash', false)->count();


        return view('admin.pages.dashboard', compact('sellers', 'users', 'catalogs', 'active_products', 'not_cash_products'));

    }

    public function catalog()
    {
        return view('admin.pages.catalog');
    }

    public function product()
    {
        return view('admin.pages.product');
    }


}
