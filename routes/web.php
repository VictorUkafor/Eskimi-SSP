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


Route::group(["namespace"=>"App\Http\Controllers"], function() {
    
    Route::get('/', function () { return redirect()->route('login'); });   
    Auth::routes();

    Route::middleware(['auth'])->group(function () {
        Route::get('/home', 'HomeController@index')->name('home');

        Route::post('/campaigns', 'CampaignController@create');
        Route::get('/campaigns', 'CampaignController@get');
        Route::put('/campaigns/{uid}', 'CampaignController@update');
        Route::get('/get-media', 'CampaignController@getMedia');


    });

});





