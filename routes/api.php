<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CakeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(CakeController::class)->prefix('cakes')->group(function () {
    // index
    Route::get('/', 'index')->name('cakes.index');
    // index
    Route::get('/{cake}', 'show')->name('cakes.show');
    // store
    Route::post('/', 'store')->name('cakes.store');
    // update
    Route::put('/{cake}', 'update')->name('cakes.update');
    // destroy
    Route::delete('/{cake}', 'destroy')->name('cakes.destroy');
})->name('cakes');
//
Route::controller(CustomerController::class)->prefix('customers')->group(function () {
    // index
    Route::get('/', 'index')->name('customers.index');
    // index
    Route::get('/{customer}', 'show')->name('customers.show');
    // store
    Route::post('/', 'store')->name('customers.store');
    // update
    Route::put('/{customer}', 'update')->name('customers.update');
    // destroy
    Route::delete('/{customer}', 'destroy')->name('customers.destroy');
})->name('customers');
//
Route::controller(OrderController::class)->prefix('orders')->group(function () {
    // index
    Route::get('/', 'index')->name('orders.index');
    // index
    Route::get('/{order}', 'show')->name('orders.show');
    // store
    Route::post('/', 'store')->name('orders.store');
    // update
    Route::put('/{order}', 'update')->name('orders.update');
    // destroy
    Route::delete('/{order}', 'destroy')->name('orders.destroy');
})->name('orders');
