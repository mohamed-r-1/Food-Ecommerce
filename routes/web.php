<?php

use App\Http\Middleware\CheckForPrice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/about', [App\Http\Controllers\HomeController::class, 'about'])->name('about');

Route::get('/contact', [App\Http\Controllers\HomeController::class, 'contact'])->name('contact');

Route::group(['prefix' => 'products'], function () {

    // Products
    Route::get('/category/{id}', [App\Http\Controllers\Products\ProductsController::class, 'singleCategory'])->name('single.category');

    Route::get('/single-product/{id}', [App\Http\Controllers\Products\ProductsController::class, 'singleProduct'])->name('single.product');

    Route::get('/shop', [App\Http\Controllers\Products\ProductsController::class, 'shop'])->name('products.shop');

    // Cart
    Route::post('/add-cart', [App\Http\Controllers\Products\ProductsController::class, 'addToCart'])->name('products.add.cart');

    Route::get('/cart', [App\Http\Controllers\Products\ProductsController::class, 'cart'])->name('products.cart')->middleware('auth:web');

    Route::get('/delete-cart/{id}', [App\Http\Controllers\Products\ProductsController::class, 'deleteFromCart'])->name('products.cart.delete');

    // Checkout And Paying
    Route::post('/prepare-checkout', [App\Http\Controllers\Products\ProductsController::class, 'prepareCheckout'])->name('products.prepare.checkout');

    Route::get('/checkout', [App\Http\Controllers\Products\ProductsController::class, 'checkout'])->name('products.checkout')->middleware('check.for.price');

    Route::post('/checkout', [App\Http\Controllers\Products\ProductsController::class, 'proccessCheckout'])->name('products.proccess.checkout')->middleware('check.for.price');

    Route::get('/pay', [App\Http\Controllers\Products\ProductsController::class, 'payWithPaypal'])->name('products.pay')->middleware('check.for.price');

    Route::get('/success', [App\Http\Controllers\Products\ProductsController::class, 'success'])->name('products.success')->middleware('check.for.price');

});

Route::group(['prefix' => 'users'], function () {

    // Users Pages
    Route::get('/my-orders', [App\Http\Controllers\Users\UsersController::class, 'myOrders'])->name('users.orders')->middleware('auth:web');

    Route::get('/settings', [App\Http\Controllers\Users\UsersController::class, 'settings'])->name('users.settings')->middleware('auth:web');

    Route::post('/settings/{id}', [App\Http\Controllers\Users\UsersController::class, 'updateUserSettings'])->name('users.settings.update')->middleware('auth:web');

});

// Admin Panel
Route::get('admin/login', [App\Http\Controllers\Admins\AdminsController::class, 'viewLogin'])->name('view.login')->middleware('check.for.auth');

Route::post('admin/login', [App\Http\Controllers\Admins\AdminsController::class, 'checkLogin'])->name('check.login');

Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function () {

    Route::get('/index', [App\Http\Controllers\Admins\AdminsController::class, 'index'])->name('admins.dashboard');

    // Admins
    Route::get('/all-admins', [App\Http\Controllers\Admins\AdminsController::class, 'displayAdmins'])->name('admins.all');

    Route::get('/create-admins', [App\Http\Controllers\Admins\AdminsController::class, 'createAdmins'])->name('admins.create');

    Route::post('/create-admins', [App\Http\Controllers\Admins\AdminsController::class, 'storeAdmins'])->name('admins.store');

    // Categories
    Route::get('/all-categories', [App\Http\Controllers\Admins\AdminsController::class, 'displayCategories'])->name('categories.all');

    Route::get('/create-categories', [App\Http\Controllers\Admins\AdminsController::class, 'createCategories'])->name('categories.create');

    Route::post('/create-categories', [App\Http\Controllers\Admins\AdminsController::class, 'storeCategories'])->name('categories.store');

    Route::get('/edit-categories/{id}', [App\Http\Controllers\Admins\AdminsController::class, 'editCategories'])->name('categories.edit');

    Route::post('/update-categories/{id}', [App\Http\Controllers\Admins\AdminsController::class, 'updateCategories'])->name('categories.update');

    Route::get('/delete-categories/{id}', [App\Http\Controllers\Admins\AdminsController::class, 'deleteCategories'])->name('categories.delete');

    // Products
    Route::get('/all-products', [App\Http\Controllers\Admins\AdminsController::class, 'displayProducts'])->name('products.all');

    Route::get('/create-products', [App\Http\Controllers\Admins\AdminsController::class, 'createProducts'])->name('products.create');

    Route::post('/create-products', [App\Http\Controllers\Admins\AdminsController::class, 'storeProducts'])->name('products.store');

    Route::get('/delete-products/{id}', [App\Http\Controllers\Admins\AdminsController::class, 'deleteProducts'])->name('products.delete');

    // Orders
    Route::get('/all-orders', [App\Http\Controllers\Admins\AdminsController::class, 'allOrders'])->name('orders.all');

    Route::get('/edit-orders/{id}', [App\Http\Controllers\Admins\AdminsController::class, 'editOrders'])->name('orders.edit');

    Route::post('/update-orders/{id}', [App\Http\Controllers\Admins\AdminsController::class, 'updateOrders'])->name('orders.update');

});
