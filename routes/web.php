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

Route::get('/', function () {
    
    app('session')->put('disable_notification',true);

    return redirect()->route('login');
});

Route::redirect('/dashboard', '/sales');

Route::get('/sales', function () {
    return view('coffee_sales');
})->middleware(['auth'])->name('coffee.sales');

Route::get('/arabic_sales', function () {
    return view('coffee_arabic_sales');
})->middleware(['auth'])->name('coffee_arabic.sales');

Route::get('/shipping-partners', function () {
    return view('shipping_partners');
})->middleware(['auth'])->name('shipping.partners');

// Route::post('/recordSale', 'SalesController@recordSale');

Route::post('/recordSale', 'App\Http\Controllers\SalesController@recordSale');
Route::get('/getSalesData', 'App\Http\Controllers\SalesController@getSalesData');
Route::get('/getProductsData', 'App\Http\Controllers\SalesController@getProductsData');




require __DIR__.'/auth.php';
