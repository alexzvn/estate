<?php

use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => 'extension'], function ()
{
    Route::post('blacklist/add', 'Api\BlacklistController@import');
    Route::post('import-crawl-post', 'Api\Post\Imports\TccController@store');

    Route::post('import/crawl/tcc', 'Api\Post\Imports\TccController@store')->name('api.crawl.import.tcc');
    Route::post('import/crawl/chotot', 'Api\Post\Imports\ChoTotController@store')->name('api.crawl.import.chotot');
    Route::post('import/crawl/loctinbds', 'Api\Post\Imports\LocTinBdsController@store')->name('api.crawl.import.loctinbds');
});
