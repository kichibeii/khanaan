<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ], function()
{

	/** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
    Route::get('/', 'HomeController@index')->name('home');

    Route::get('webhome', 'HomeController@webhome')->name('webhome');

    Route::get('blog/{slug}', 'ArticleController@show')->name('blog.show');
    Route::get('blog', 'ArticleController@index')->name('blog');

    Route::post('page/contact-us', 'PageController@contactUs')->name('page.store-contact');
    Route::get('page/{slug}', 'PageController@show')->name('page.show');

    Route::get('shop/{slug?}.html', 'ProductController@show')->name('shop.show');
    Route::post('shop/{slug?}.html', 'NoAuthController@addToCart')->name('shop.addToCart');
    Route::get('shop/{action}', 'ProductController@index')->name('shop.action');
    Route::get('collections', 'ProductController@collections')->name('shop.collections');
    Route::get('shop', 'ProductController@index')->name('shop');

    Route::get('products/get-size', 'ProductController@getSize');
    Route::get('products/get-qty', 'ProductController@getQty');

    Route::get('cart/success-test', 'CartController@successTest')->name('cart.success');
    Route::get('cart/success', 'NoAuthController@successPage')->name('cart.success');
    Route::get('cart', 'NoAuthController@index')->name('cart');
    Route::get('cart/delete/{id}', 'NoAuthController@destroy')->name('cart.destroy');
    Route::post('cart/update', 'NoAuthController@update')->name('cart.update');
    Route::post('cart/voucher-update', 'NoAuthController@voucherUpdate')->name('cart.voucher-update');
    Route::get('checkout', 'NoAuthController@checkout')->name('checkout');
    Route::post('checkout', 'NoAuthController@storeCheckout')->name('checkout');
    Route::post('billing', 'CartController@storeBilling')->name('billing.store');

    Route::get('test-courier', 'RajaOngkirController@testCourier')->name('testCourier');

    Route::get('confirm-payment', 'CartController@confirmPayment')->name('confirm-payment');
    Route::post('store-confirm-payment', 'CartController@storeConfirmPayment')->name('store-confirm-payment');



    Auth::routes(['verify' => true]);

    // profile
    Route::get('profile', 'ProfileController@index')->name('profile');
    Route::post('profile', 'ProfileController@update')->name('profile-update');

    // Purchase / History order
    Route::get('purchase', 'PurchaseController@index')->name('purchase');
    Route::get('purchase-detail/{invoice}', 'PurchaseController@detail')->name('purchase.detail');

    // Track Order
    Route::get('track-order', 'TrackOrderController@index')->name('track-order');
    Route::get('track-order-detail/{invoice}', 'TrackOrderController@detail')->name('track-order.detail');

});

Route::post('xendit-payment', 'XenditController@index');
Route::get('country', 'RajaOngkirController@country')->name('rajaongkir.country');
Route::get('province', 'RajaOngkirController@index')->name('rajaongkir.index');
Route::get('city/{province}', 'RajaOngkirController@city')->name('rajaongkir.city');
Route::get('subdistrict/{city}', 'RajaOngkirController@subdistrict')->name('rajaongkir.subdistrict');
Route::get('subdistrict/{city}/{subdistrict_id}', 'RajaOngkirController@subdistrictDetail')->name('rajaongkir.subdistrictDetail');
Route::post('courier', 'RajaOngkirController@getCourier')->name('rajaongkir.getCourier');
Route::post('courier-guest', 'RajaOngkirController@getCourierGuest')->name('rajaongkir.getCourierGuest');
//Route::get('/home', 'HomeController@index')->name('home');

Route::get('/migrate', function () {$exitCode = Artisan::call('migrate');});
Route::get('/config-cache', function () {$exitCode = Artisan::call('config:cache');});
Route::get('/view-clear', function () {$exitCode = Artisan::call('view:clear');});
Route::get('/storage-link', function () {$exitCode = Artisan::call('storage:link');});

Route::get('/dirty-storage-link', function () {
    symlink('/home/k4795169/laravel/storage/app/public', '/home/k4795169/public_html/dev.khanaan.com/storage');
});

Route::get('/nginfo', function () {
    phpinfo();
});

Route::post('/set-currency', 'HomeController@setCurrency');

Route::get('/check-expired', function () {$exitCode = Artisan::call('CheckInvoiceExpire:check');});
Route::get('/check-currency', function () {$exitCode = Artisan::call('CheckCurrency:check');});
Route::get('/xendit', 'HomeController@xendit');

Route::get('sendemail', function () {
    echo config('mail.host') . '<br>';
    echo 'test email<br>';
    echo date('d-m-Y H:i:s');
    echo '<br>';
    $data = array(
        'name' => "Learning Laravel",
    );

    try{
        Mail::send('emails.welcome', $data, function ($message) {
            //$message->from(config('app.email_account'), config('app.email_account_name'));
            $message->to(['heri1845@gmail.com'])->subject('Test send email'); });

        echo 'Email berhasil dikirim';
    }
    catch(Exception $e){
        echo 'Email GAGAL dikirim';
        print_r($e);
    }

    echo '<br>';
    echo date('d-m-Y H:i:s');
    exit;
});
