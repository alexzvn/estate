<?php

use Illuminate\Support\Facades\Auth;
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

Route::get('/', 'PublicController@index');

Auth::routes();

Route::group(['middleware' => ['verified', 'auth'], 'namespace' => 'Customer'], function ()
{
    Route::get('/home', 'PostController@index')->name('home');
    Route::get('/online', 'PostController@online')->name('post.online');
    Route::get('/fee', 'PostController@fee')->name('post.fee');
    Route::get('/market', 'PostController@market')->name('post.market');
    Route::get('/post/{id}/view', 'PostController@view')->name('post.view');
});


