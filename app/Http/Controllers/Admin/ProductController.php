<?php

namespace App\Http\Controllers\Admin;

use App\Catalog;
use App\Http\Controllers\Controller;
use App\Product;
use App\ProductImages;
use App\User;
use App\MostViewedProduct;
use App\OrderList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected $paginate = 5;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $merchant_ids = Product::get()->pluck('merchant_id')->unique();
        $merchants = User::whereIn('id', $merchant_ids)->get();
        $catalogs = Catalog::where('parent_id', 0)->get();
        $products = Product::with(['merchant'])->paginate($this->paginate);
        return view('admin.pages.product.index', compact('products', 'catalogs', 'merchants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($lang,$id)
    {

        $product = Product::where('id', $id)->with(['category'])->first();
        return view('admin.pages.product.edit', compact('product'));
    }


    public function popular(Request $request)
    {
        $product = Product::where('id', $request->id)->first();
        if ($product->is_popular) {
            $product->is_popular = false;
        } else {
            $product->is_popular = true;
        }
        $product->save();
        return response()->json([
            'popular' => $product->is_popular,
            'id' => $product->id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
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

    public function search($lang,$search, $catalog_id, $seller_id, $pagination)
    {
        if ($search == "null" && $catalog_id == 'all' && $seller_id == 'all') {
            $products = Product::paginate($pagination);
            $merchant_ids = Product::get()->pluck('merchant_id')->unique();
            $merchants = User::whereIn('id', $merchant_ids)->get();
            $catalogs = Catalog::where('parent_id', 0)->get();
            return view('admin.pages.product.index', compact('search', 'catalog_id', 'seller_id', 'pagination', 'products', 'catalogs', 'merchants'));
        }

        $products = null;
        if ($search != "null") {
            if ($catalog_id == "all") {
                if ($seller_id == "all") {
                    if (preg_match('/[А-Яа-яЁё]/u', $search)) {
                        $products = Product::Where('name_ru', 'LIKE', "%$search%")->
                        paginate($pagination);
                    } else {
                        $products = Product::where(
                            [
                                ['name_uz', 'LIKE', "%$search%"]
                            ])->
                        paginate($pagination);
                    }
                } else {
                    if (preg_match('/[А-Яа-яЁё]/u', $search)) {
                        $products = Product::Where([
                            ['name_ru', 'LIKE', "%$search%"],
                            ['merchant_id', $seller_id]
                        ])->
                        paginate($pagination);
                    } else {
                        $products = Product::where([
                            ['name_uz', 'LIKE', "%$search%"],
                            ['merchant_id', $seller_id]
                        ])->
                        paginate($pagination);
                    }
                }
            } else {
                if ($seller_id == "all") {
                    $category = Catalog::where(
                        [
                            ['id', $catalog_id],
                            ['parent_id', 0]
                        ])->with(['children'])->first();
                    $catalog_ids = $category->children->pluck('id')->toArray();

                    if (preg_match('/[А-Яа-яЁё]/u', $search)) {
                        $products = Product::Where(
                            [
                                ['name_ru', 'LIKE', "%$search%"],
                                ['catalog_id', $catalog_id]
                            ])->
                        paginate($pagination);
                    } else {
                        $products = Product::where(
                            [
                                ['name_uz', 'LIKE', "%$search%"],
                            ])->
                        WhereIn('catalog_id', $catalog_ids)->
                        paginate($pagination);
                    }
                } else {
                    $category = Catalog::where(
                        [
                            ['id', $catalog_id],
                            ['parent_id', 0]
                        ])->with(['children'])->first();
                    $catalog_ids = $category->children->pluck('id')->toArray();

                    if (preg_match('/[А-Яа-яЁё]/u', $search)) {
                        $products = Product::Where(
                            [
                                ['name_ru', 'LIKE', "%$search%"],
                                ['merchant_id', $seller_id],
                            ])->
                        WhereIn('catalog_id', $catalog_ids)->
                        paginate($pagination);
                    } else {
                        $products = Product::where(
                            [
                                ['name_uz', 'LIKE', "%$search%"],
                                ['merchant_id', $seller_id],
                            ])->
                        WhereIn('catalog_id', $catalog_ids)->
                        paginate($pagination);
                    }
                }
            }

        } else {

            if ($catalog_id != "all" && $seller_id != "all") {
                $category = Catalog::where(
                    [
                        ['id', $catalog_id],
                        ['parent_id', 0]
                    ])->with(['children'])->first();
                $catalog_ids = $category->children->pluck('id')->toArray();
                $products = Product::where([
                    ['merchant_id', $seller_id],
                ])->
                whereIn('catalog_id', $catalog_ids)->
                paginate($pagination);
            } else {
                if ($catalog_id == "all" && $seller_id != "all") {
                    $products = Product::where([
                        ['merchant_id', $seller_id]
                    ])->
                    paginate($pagination);
                } else {
                    $category = Catalog::where(
                        [
                            ['id', $catalog_id],
                            ['parent_id', 0]
                        ])->with(['children'])->first();
                    $catalog_ids = $category->children->pluck('id')->toArray();
                    $products = Product::whereIn('catalog_id', $catalog_ids)->paginate($pagination);

                }
            }
        }

        $merchant_ids = Product::get()->pluck('merchant_id')->unique();
        $merchants = User::whereIn('id', $merchant_ids)->get();
        $catalogs = Catalog::where('parent_id', 0)->get();
        return view('admin.pages.product.index', compact('search', 'catalog_id', 'seller_id', 'pagination', 'products', 'catalogs', 'merchants'));

    }

    public function postSearch(Request $request)
    {

        if ($request->search == null && $request->catalog == 'all' && $request->seller == 'all' && $request->pagination == 5) {
            return redirect()->route('admin.product.index',app()->getLocale());
        }


        return redirect()->route('admin.product.search', ["lang"=>app()->getLocale(),"search" => $request->search ?? "null", "catalog_id" => $request->catalog, "seller_id" => $request->seller, "number" => $request->pagination]);
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
}
