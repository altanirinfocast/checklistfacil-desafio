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
