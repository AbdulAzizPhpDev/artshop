<?php

use Illuminate\Support\Facades\Route;


////handle requests from payment system
//Route::any('/handle/{paysys}', function ($paysys) {
//    (new Goodoneuz\PayUz\PayUz)->driver($paysys)->handle();
//});
//
////redirect to payment system or payment form
//Route::any('/pay/{paysys}/{key}/{amount}', function ($paysys, $key, $amount) {
//    $model = Goodoneuz\PayUz\Services\PaymentService::convertKeyToModel($key);
//    $url = request('redirect_url', '/'); // redirect url after payment completed
//    $pay_uz = new Goodoneuz\PayUz\PayUz;
//    $pay_uz
//        ->driver($paysys)
//        ->redirect($model, $amount, 860, $url);
//});
//Route::get('/pdf', 'MainController@pdf')->name('pdf');

Route::redirect('/', 'uz');
Route::group([
    "prefix" => "/ajax",
], function () {
    Route::post('/districts', 'MainController@districts')->name('districts');
    Route::post('/district/search', 'MainController@districtSearch')->name('search.districts');
    Route::post('/change-phone', 'MainController@changePhone')->name('change_phone');
    Route::post('/categories', 'MainController@categories')->name('categories');
    Route::post('/set-delivery-price', 'Seller\DeliveryController@setDeliveryPrice')->name('set_delivery');
    Route::post('/change-delivery-status', 'Seller\DeliveryController@changeDeliveryStatus')->name('change_delivery');
    Route::post('/change-status', 'Seller\DeliveryController@changeSingleStatus')->name('change_single_status');
    Route::get('/changelanguage/{lang}', 'MainController@changeLanguage')->name('changeLanguage');
    Route::post('/catalog/update/status', 'Admin\CatalogController@updateStatus')->name('update_status');
    Route::post('/member/user-seller/inactive', 'Admin\MemberController@userAndSellerInactive')->name('user.inactive');
    Route::post('/member/user-seller/active', 'Admin\ArchiveController@userAndSellerActive')->name('user.active');
    Route::post('/member/admins/delete', 'Admin\MemberController@destroyAdmin')->name('user.admin.delete');
    Route::post('/section/destroy', 'Admin\VideoController@destroySection')->name('section.destroy');
    Route::post('/section/update/status', 'Admin\VideoController@updateSectionStatus')->name('section.update.status');
    Route::post('/video/destroy', 'Admin\VideoController@destroy')->name('video.destroy');
    Route::post('/video/update/status', 'Admin\VideoController@updateVideoStatus')->name('video.update.status');


    Route::post('/product/popular', 'Admin\ProductController@popular')->name('product.popular');
    Route::post('/product/update/status', 'Admin\ProductController@updateStatus')->name('product.update.status');
    Route::post('/product/delete', 'Admin\ProductController@destroy')->name('product.destroy');


    Route::post('/get-catalogs/item', 'User\MainController@getCatalogPrePage')->name('user.get_catalog');
    Route::post('/get-products/item', 'User\MainController@getProductPrePage')->name('user.get_product');

    Route::post('/cart-add', 'User\CartController@cartAdd')->name('cart_add');
    Route::post('/cart-change-quantity', 'User\CartController@cartChangeQuantity')->name('cart_change_quantity');
    Route::post('/cart-delete', 'User\CartController@cartDeleteProduct')->name('cart_delete');
    Route::post('/add/quantity', 'User\CartController@addQuantity')->name('add_quantity');

    Route::post('/count', 'User\InfoCenterController@countVideo')->name('count');
    Route::post('/like', 'User\InfoCenterController@likeVideo')->name('like');

    Route::post('/wish-list-add', 'User\MainController@addWishList')->name('add_wish_list');
    Route::post('/wish-list-remove', 'User\MainController@removeWishList')->name('remove_wish_list');

    Route::post('/add-review', 'User\ProductController@addReview')->name('add_review');
    Route::post('/get-contact-info', 'User\ProductController@getContactInfo')->name('contact');

    Route::post('/put-in-archive', 'Seller\OrderController@deleteArchive')->name('order.archive');

    Route::post('/search-in-catalog', 'User\CatalogController@searchInCatalog')->name('search.product.catalog');
    Route::post('/search-in-catalogs', 'User\CatalogController@searchInCatalogs')->name('search_in_catalogs');

    Route::post('/search-products', 'User\ProductController@searchProducts')->name('search_in_products');

    Route::post('/delete/catalog', 'Admin\RegionController@destroy')->name('delete_catalog');

    Route::post('/get/address/street','Seller\OrderController@updateStreet');
    Route::post('/get/address/districts','Seller\OrderController@updateAddressDistrict');


});

Route::get('/payment-info', [User\PaymentController::class, 'index'])->name('payment.index');
Route::post('/create-click-invoice', [User\PaymentController::class, 'createClickInvoice'])->name('createClickInvoice');
Route::get('/check-click-invoice', [User\PaymentController::class, 'checkClickPayment'])->name('checkClickPayment');

Route::group([
    "prefix" => "{lang}"
], function () {

    Route::get('/document/{order_id}', 'MainController@printDocument')->name('print');

    Route::group([
        "as" => "admin.",
        "namespace" => "Admin",
        "prefix" => "/admin",
        "middleware" => "admin"
    ], function () {
        Route::get('/dashboard', 'MainController@index')->name('dashboard');
        Route::group([
            "as" => "catalog.",
            "prefix" => "/catalog"
        ], function () {
            Route::get('/', 'CatalogController@index')->name('index');
            Route::get('/{id}/edit', 'CatalogController@edit')->name('edit');
            Route::get('/{id}/sub-catalog', 'CatalogController@subCatalog')->name('sub_catalog');
            Route::get('/create', 'CatalogController@create')->name('create');
            Route::post('/store', 'CatalogController@store')->name('store');
            Route::post('/update', 'CatalogController@store')->name('update');
            Route::delete('/delete', 'CatalogController@destroy')->name('destroy');
            Route::post('/update/status', 'CatalogController@updateStatus')->name('update_status');
            Route::get('/search/{search}', 'CatalogController@search')->name('search');
            Route::post('/search', 'CatalogController@postSearch')->name('post.search');
        });
        Route::group([
            "as" => "region.",
            "prefix" => "/region"
        ], function () {
            Route::get('/', 'RegionController@index')->name('index');
            Route::get('/{region_id}/district', 'RegionController@district')->name('district');
            Route::get('/{region_id}/district/create', 'RegionController@Createdistrict')->name('district.create');
            Route::get('/update/{id}', 'RegionController@update')->name('update');
            Route::get('/create', 'RegionController@create')->name('creat');
            Route::post('/store', 'RegionController@addAndUpdateRegion')->name('store');
            Route::get('/delete/{id}', 'RegionController@destroy')->name('destroy');

        });

        Route::group([
            "as" => "product.",
            "prefix" => "/product"
        ], function () {

            Route::get('/', 'ProductController@index')->name('index');
            Route::get('/view/{id}', 'ProductController@show')->name('show');
            Route::post('/search', 'ProductController@postSearch')->name('post.search');
            Route::get('/search/{search}/catalog/{catalog_id}/seller/{seller_id}/pagination/{number}', 'ProductController@search')->name('search');


        });

        Route::group([
            "as" => "member.",
            "prefix" => "/member"
        ], function () {
            Route::get('/users', 'MemberController@usersIndex')->name('user.index');
            Route::get('/admins', 'MemberController@adminsIndex')->name('admin.index');
            Route::get('/sellers', 'MemberController@sellersIndex')->name('seller.index');


            Route::get('/user/edit/{user_id}', 'MemberController@editUser')->name('edit.user');
            Route::post('/user/update', 'MemberController@updateUser')->name('post.user');

            Route::get('/add-admin', 'MemberController@addAdmin')->name('add.admin');
            Route::get('/add-seller', 'MemberController@addSeller')->name('add.seller');
            Route::post('/add-admin', 'MemberController@store')->name('post.admin');
            Route::post('/add-seller', 'MemberController@store')->name('post.seller');
            Route::get('/admin/edit/{id}', 'MemberController@updateAdmin')->name('update.admin');
            Route::get('/seller/edit/{id}', 'MemberController@updateSeller')->name('update.seller');
//                                     Search
            Route::get('/users/search/{search}', 'MemberController@searchUser')->name('user.search');
            Route::post('/users/search', 'MemberController@postSearchUser')->name('user.post.search');

            Route::get('/admins/search/{search}', 'MemberController@searchAdmin')->name('admin.search');
            Route::get('/admins/role/{role_id}/search/{search}', 'MemberController@searchAdminWithRole')->name('admin.search.role');
            Route::get('/admins/role/{role_id}', 'MemberController@searchRole')->name('admin.role');
            Route::post('/admins/search', 'MemberController@postSearchAdmin')->name('admin.post.search');

            Route::get('/sellers/search/{search}', 'MemberController@searchSeller')->name('seller.search');
            Route::post('/sellers/search', 'MemberController@postSearchSeller')->name('seller.post.search');
            Route::post('/user-seller/inactive', 'MemberController@userAndSellerInactive')->name('user.inactive');
            Route::post('/user-seller/active', 'ArchiveController@userAndSellerActive')->name('user.active');
//                Delete
//            Route::post('/admins/delete', '')->name('admin.delete');


        });

        Route::group([
            "as" => "video.",
            "prefix" => "/video"
        ], function () {
            Route::get('/', 'VideoController@index')->name('index');
            Route::get('/add', 'VideoController@addVideo')->name('add');
            Route::post('/add', 'VideoController@storeVideo')->name('store');
            Route::get('/edit/{id}', 'VideoController@editVideo')->name('edit');
            Route::get('/search/{search}', 'VideoController@searchVideo')->name('search');
            Route::post('/search', 'VideoController@postSearchVideo')->name('post.search');


        });

        Route::group([
            "as" => "section.",
            "prefix" => "/section"
        ], function () {
            Route::get('/', 'VideoController@section')->name('index');
            Route::get('/add', 'VideoController@addSection')->name('add');
            Route::post('/add', 'VideoController@storeSection')->name('store');
            Route::get('/edit/{id}', 'VideoController@editSection')->name('edit');
            Route::get('/search/{search}', 'VideoController@searchSection')->name('search');
            Route::post('/search', 'VideoController@postSearchSection')->name('post.search');

        });

        Route::group([
            "as" => "archive.",
            "prefix" => "/archive"
        ], function () {
            Route::get('/sellers', 'ArchiveController@sellerIndex')->name('seller');
            Route::get('/purchasers', 'ArchiveController@purchaserIndex')->name('purchaser');
            //Search
            Route::get('/sellers/search/{search}', 'ArchiveController@searchSeller')->name('seller.search');
            Route::post('/sellers/search', 'ArchiveController@postSearchSeller')->name('seller.post.search');

            Route::get('/purchasers/search/{search}', 'ArchiveController@searchUser')->name('user.search');
            Route::post('/purchasers/search', 'ArchiveController@postSearchUser')->name('user.post.search');
        });

        Route::group([
            "as" => "message.",
            "prefix" => "/message"
        ], function () {
            Route::get('/feedback', 'FeedbackController@index')->name('feedback.index');
        });


    });

//       user user user user user useruseruser user user user
    Route::group([
        "as" => "user.",
        "namespace" => "User",
        "prefix" => "/",

    ], function () {

        Route::group([
            "as" => "profile.",
            "prefix" => "/user/profile",
            "middleware" => "user"
        ], function () {
            Route::get('/', 'ProfileController@index')->name('index');
            Route::get('/information', 'ProfileController@information')->name('information');
            Route::post('/information', 'ProfileController@storeInformation')->name('information.store');
            Route::get('/security', 'ProfileController@security')->name('security');
            Route::post('/security/password', 'ProfileController@postSecurity')->name('post.security');
            Route::post('/security/login', 'ProfileController@postLoginSecurity')->name('post.login.security');
            Route::get('/orders', 'ProfileController@orders')->name('orders');
            Route::get('/order/{id}/invoice', 'ProfileController@invoice')->name('invoice');
            Route::post('/orders/search', 'ProfileController@postSearch')->name('post.search');
            Route::get('/orders/{search}/state/{state}', 'ProfileController@search')->name('search');
            Route::post('/invoice', 'ProfileController@activeInvoice')->name('post.invoice');
            Route::get('/wishlist', 'ProfileController@wishlist')->name('wishlist');
            Route::get('/reviews', 'ProfileController@reviews')->name('reviews');
            Route::post('/reviews/search', 'ProfileController@reviewsPostSearch')->name('reviews.post.search');
            Route::get('/reviews/search/{search}', 'ProfileController@reviewsSearch')->name('reviews.search');
            Route::get('/messages', 'ProfileController@messages')->name('messages');


        });


        Route::get('/', 'MainController@index')->name('index');
        Route::get('/about', 'MainController@about')->name('about');
        Route::get('/help', 'MainController@help')->name('help');
        Route::get('/wish-list', 'MainController@wishList')->name('wish_list')->middleware('auth');

        Route::get('/product/{id}', 'ProductController@index')->name('product_view');
        Route::get('/cart', 'CartController@cart')->name('cart');


        Route::get('/checkout/{merchant_id}', 'MainController@checkout')->name('checkout');
        Route::get('/buy-checkout/{product_id}', 'MainController@buyCheckout')->name('buy_checkout');

        Route::get('/invoice', 'MainController@invoice')->name('invoice');
        Route::post('/order', 'OrderController@create')->name('order.create');
        Route::post('/buy-order', 'OrderController@bayProduct')->name('order.buyProduct');


        Route::get('/catalog', 'CatalogController@index')->name('catalog');
        Route::get('/catalog/{catalog_id}', 'CatalogController@productsByCatalog')->name('products_by_catalog');


        Route::group([
            "as" => "info_center.",
            "prefix" => "/information-center"
        ], function () {
            Route::get('/', 'InfoCenterController@index')->name('index');

            Route::get('/section/{name}/video/{id}', 'InfoCenterController@showVideo')->name('show.video');
            Route::get('/section/{id}', 'InfoCenterController@showSectionVideo')->name('show.section.video');
            Route::get('/section/{id}/search/{search}', 'InfoCenterController@searchSection')->name('search.section.video');
            Route::post('/search', 'InfoCenterController@postSearch')->name('post.search');
            Route::get('/search/{search}', 'InfoCenterController@search')->name('search');
            Route::get('/most-viewed-in-{range}', 'InfoCenterController@mostViewed')->name('viewed');
        });
        Route::group([
            "namespace" => "Auth",
        ], function () {
            Route::get('/login', 'AuthController@login')->name('login');
            Route::get('/register', 'AuthController@register')->name('register');
            Route::post('/login-post', 'AuthController@loginPost')->name('login_post');
            Route::post('/register-post', 'AuthController@registerPost')->name('register_post');
            Route::get('/confirm', 'AuthController@confirm')->name('confirm');
            Route::post('/confirm-post', 'AuthController@confirmPost')->name('confirm_post');
            Route::get('/resent-sms', 'AuthController@resendSms')->name('resend_sms');
            Route::get('/logout', 'AuthController@logout')->name('logout');

            Route::get('/register/seller', 'AuthController@registerSeller')->name('register_seller');

            Route::get('/restore-password', 'AuthController@restorePassword')->name('restore_password');
            Route::post('/restore-password', 'AuthController@postRestorePassword')->name('post_restore_password');

            Route::get('/confirm-password', 'AuthController@confirmPassword')->name('confirm_password');
            Route::post('/confirm-password', 'AuthController@postConfirmPassword')->name('post_confirm_password');
            Route::post('/change-password', 'AuthController@changePassword')->name('change_password');
        });

    });

// Seller  Seller  Seller  Seller  Seller  Seller  Seller  Seller

    Route::group([
        "as" => "seller.",
        "namespace" => "Seller",
        "prefix" => "/seller",
        "middleware" => "seller"

    ], function () {
        Route::get('/dashboard', 'CompanyController@dashboard')->name('dashboard')->middleware('seller.check');
        Route::get('/reviews', 'CompanyController@review')->name('review')->middleware('seller.check');

        Route::group([
            "as" => "company.",
            "prefix" => "/company",
        ], function () {
            Route::get('/about', 'CompanyController@about')->name('about');
            Route::post('/store/info', 'CompanyController@storeInformations')->name('store.info');
            Route::get('/requisite', 'CompanyController@requisite')->name('requisite');
            Route::post('/store/requisites', 'CompanyController@storeRequisites')->name('store.requisites');
        });

        Route::group([
            "as" => "order.",
            "prefix" => "/order",
            "middleware" => "seller.check"
        ], function () {
            Route::get('/', 'OrderController@index')->name('index');
            Route::get('/{id}/invoice', 'OrderController@invoice')->name('invoice');
            Route::get('/archive/{id}/invoice', 'OrderController@invoiceArchive')->name('invoice.archive');

            Route::post('/search', 'OrderController@postSearch')->name('post.search');
            Route::get('/{search}/state/{state}', 'OrderController@search')->name('search');

            Route::get('/archive', 'OrderController@archive')->name('archive');
            Route::get('/archive', 'OrderController@archive')->name('archive');
            Route::post('/invoice', 'OrderController@activeInvoice')->name('post.invoice');
            Route::post('/cancel-invoice', 'OrderController@deActiveInvoice')->name('cancel.invoice');
        });

        Route::group([
            "as" => "product.",
            "prefix" => "/product",
            "middleware" => "seller.check"

        ], function () {
            Route::get('/index', 'ProductController@index')->name('index');
            Route::get('/add', 'ProductController@add')->name('add');
            Route::post('/store', 'ProductController@store')->name('store');
            Route::get('/edit/{id}', 'ProductController@edit')->name('edit');
            Route::post('/update', 'ProductController@update')->name('update');
            Route::post('/search', 'ProductController@Postsearch')->name('search');
            Route::get('/search/{search}/selling-type/{sell_type}', 'ProductController@search')->name('post.search');

            Route::get('/archive', 'ProductController@archive')->name('archive');
            Route::post('/archive/search', 'ProductController@postSearchArchive')->name('archive.search');
            Route::get('/archive/search/{search}/selling-type/{sell_type}', 'ProductController@searchArchive')->name('post.archive.search');

            Route::post('/update/status', 'ProductController@updateStatus')->name('update.status');
            Route::post('/delete', 'ProductController@destroy')->name('destroy');


        });

        Route::group([
            "as" => "delivery.",
            "prefix" => "/delivery",
            "middleware" => "seller.check"
        ], function () {
            Route::get('/', 'DeliveryController@delivery')->name('index');
            Route::get('/payment-setting', 'DeliveryController@payment')->name('payment');
            Route::post('/payment-setting', 'DeliveryController@postPayment')->name('post.payment');

        });
    });

});



