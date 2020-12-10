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

Route::post('import-crawl-post', 'Api\Post\Imports\TccController@store')
    ->middleware('extension')->name('api.crawl.import.tcc');

Route::post('import/crawl/tcc', 'Api\Post\Imports\TccController@store')
    ->middleware('extension');

Route::post('import/crawl/loctinbds', 'Api\Post\Imports\TccController@store')
    ->middleware('extension')->name('api.crawl.import.loctinbds');

Route::post('blacklist/add', 'Api\BlacklistController@import')
    ->middleware('extension');