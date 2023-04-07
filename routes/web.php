<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiKeyController;
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
    return view('app');
});

Route::prefix('api')->group(function (){
//API KEY routes
    Route::post('api-key/validate', [ApiKeyController::class, 'validateAndSave'])->name('api-key.validate');
    Route::get('api-key', [ApiKeyController::class, 'load'])->name('api-key.load');

//Subscribers manager routes
    Route::resource('subscribers', 'App\Http\Controllers\SubscriberController');

});
