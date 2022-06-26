<?php

Route::get('/', 'Admin\DashboardController@index')->name('admin.dashboard.index');
Route::post('dashboard/image-upload', 'Admin\DashboardController@imageUpload')->name('imageUpload');

Route::get('login', function() {
    return view('admin/login');
});
Route::post('login', 'Auth\LoginController@login')->name('admin.login');
Route::post('logout', 'Auth\LoginController@logout')->name('admin.logout');

/* PROFILE */
Route::get('profile', 'Admin\ProfileController@edit')->name('profile.edit');
Route::post('profile', 'Admin\ProfileController@update')->name('profile.update');

/* PERMISSION */
Route::post('permission/get-data', 'Admin\PermissionController@getData')->name('permission.getData');
Route::resource('permission', 'Admin\PermissionController');

/* ROLE */
Route::get('role/{role}/permission', 'Admin\RoleController@permission')->name('role.permission');
Route::put('role/{role}/permission', 'Admin\RoleController@updatePermission')->name('role.permission');
Route::post('role/get-data', 'Admin\RoleController@getData')->name('role.getData');
Route::resource('role', 'Admin\RoleController');

/* USER */
Route::post('user/get-data', 'Admin\UserController@getData')->name('user.getData');
Route::resource('user', 'Admin\UserController');

/* DROPDOWN */
Route::post('dropdown/item-get-data', 'Admin\DropdownController@itemGetData')->name('dropdown.itemGetData');
Route::get('dropdown/{dropdown}/{item}/item-edit', 'Admin\DropdownController@itemEdit')->name('dropdown.itemEdit');
Route::put('dropdown/{dropdown}/{item}/item-update', 'Admin\DropdownController@itemUpdate')->name('dropdown.itemUpdate');
Route::get('dropdown/{dropdown}/item-create', 'Admin\DropdownController@itemCreate')->name('dropdown.itemCreate');
Route::post('dropdown/{dropdown}/item-create', 'Admin\DropdownController@itemStore')->name('dropdown.itemStore');
Route::get('dropdown/{dropdown}/item', 'Admin\DropdownController@item')->name('dropdown.item');
Route::post('dropdown/get-data', 'Admin\DropdownController@getData')->name('dropdown.getData');
Route::resource('dropdown', 'Admin\DropdownController');

/* COLOR GROUP */
Route::post('group_color/get-data', 'Admin\ColorGroupController@getData')->name('group_color.getData');
Route::resource('group_color', 'Admin\ColorGroupController');

/* COLOR */
Route::post('color/get-data', 'Admin\ColorController@getData')->name('color.getData');
Route::resource('color', 'Admin\ColorController');

/* brand */
Route::post('brand/get-data-array', 'Admin\BrandController@getDataArray')->name('brand.getDataArray');
Route::get('brand/{brand}/get-detail', 'Admin\BrandController@getDetail')->name('brand.getDetail');
Route::post('brand/get-data', 'Admin\BrandController@getData')->name('brand.getData');
Route::resource('brand', 'Admin\BrandController');

/* bank */
Route::post('bank/get-data', 'Admin\BankController@getData')->name('bank.getData');
Route::resource('bank', 'Admin\BankController');

/* size */
Route::post('sizes/get-data-array', 'Admin\SizeController@getDataArray')->name('sizes.getDataArray');
Route::get('sizes/{size}/get-detail', 'Admin\SizeController@getDetail')->name('sizes.getDetail');
Route::post('sizes/get-data', 'Admin\SizeController@getData')->name('sizes.getData');
Route::resource('sizes', 'Admin\SizeController');

/* slideshow */
Route::post('slideshow/get-data', 'Admin\SlideshowController@getData')->name('slideshow.getData');
Route::resource('slideshow', 'Admin\SlideshowController');

/* article */
Route::post('article/get-data', 'Admin\ArticleController@getData')->name('article.getData');
Route::resource('article', 'Admin\ArticleController');

/* page */
Route::post('page/get-data', 'Admin\PageController@getData')->name('page.getData');
Route::resource('page', 'Admin\PageController');

/* product */

Route::get('/product/{product}/images', 'Admin\ProductController@images')->name('product.images');
Route::post('/product/{product}/images', 'Admin\ProductController@imagesStore')->name('product.images.store');

Route::get('/product/{product}/so/{so}/show', 'Admin\ProductController@StockOpnameShow')->name('product.so.show');
Route::get('/product/{productSizeQty}/so/delete', 'Admin\ProductController@stockOpnameDelete')->name('product.so.delete');
Route::post('product/{product}/so/get-data', 'Admin\ProductController@stockOpnameGetData')->name('product.so.getData');
Route::post('/product/{product}/so/store', 'Admin\ProductController@stockOpnameStore')->name('product.so.store');
Route::get('/product/{product}/so/create', 'Admin\ProductController@stockOpnameCreate')->name('product.so.create');
Route::get('/product/{product}/so', 'Admin\ProductController@stockOpname')->name('product.so');

Route::get('/product/{product}/additional-stock/{additional_stock}/show', 'Admin\ProductController@additionalStockShow')->name('product.additionalStock.show');
Route::get('/product/{product}/additional-stock/create', 'Admin\ProductController@additionalStockCreate')->name('product.additionalStock.create');
Route::post('/product/{product}/additional-stock/store', 'Admin\ProductController@additionalStockStore')->name('product.additionalStock.store');
Route::get('/product/{product}/additional-stock', 'Admin\ProductController@additionalStock')->name('product.additionalStock');
Route::post('product/{product}/additional-stock/get-data', 'Admin\ProductController@additionalStockGetData')->name('product.additionalStock.getData');

Route::get('/product/{product}/view-stock', 'Admin\ProductController@viewStock')->name('product.viewStock');
Route::post('product/dropzone', 'Admin\ProductController@dropzone')->name('product.dropzone');
Route::post('product/uploadImage', 'Admin\ProductController@uploadImage')->name('product.uploadImage');

Route::post('product/get-data', 'Admin\ProductController@getData')->name('product.getData');
Route::resource('product', 'Admin\ProductController');

/* tag */
Route::get('tag/get-data-array', 'Admin\TagController@getDataArray')->name('tag.getDataArray');

/* banner */
Route::post('banner/get-data', 'Admin\BannerController@getData')->name('banner.getData');
Route::resource('banner', 'Admin\BannerController');

/* voucher */
Route::post('voucher/get-data', 'Admin\VoucherController@getData')->name('voucher.getData');
Route::resource('voucher', 'Admin\VoucherController');

/* member */
Route::post('member/get-data', 'Admin\MemberController@getData')->name('member.getData');
Route::resource('member', 'Admin\MemberController');

/* order */
Route::post('order/get-data', 'Admin\OrderController@getData')->name('order.getData');
Route::post('order/{order}/approve', 'Admin\OrderController@approve')->name('order.approve');
Route::post('order/{order}/updateResi', 'Admin\OrderController@updateResi')->name('order.updateResi');
Route::resource('order', 'Admin\OrderController');

/* confirm payment */
Route::post('confirm_payment/get-data', 'Admin\ConfirmPaymentController@getData')->name('confirm_payment.getData');
Route::resource('confirm_payment', 'Admin\ConfirmPaymentController');

Route::get('info', function(){
	phpinfo();
});
