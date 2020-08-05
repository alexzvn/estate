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

<<<<<<< HEAD
Route::get('/test1', 'Manager\Controller@InfoPost');

Route::group(['middleware' => ['verified', 'auth', 'notbanned'], 'namespace' => 'Customer'], function ()
=======
Route::group(['middleware' => ['auth', 'customer'], 'namespace' => 'Customer'], function ()
>>>>>>> master
{
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/online', 'Post\PostController@online')->name('post.online');
    Route::get('/fee', 'Post\PostController@fee')->name('post.fee');
    Route::get('/market', 'Post\PostController@market')->name('post.market');
    Route::get('/post/{id}/view', 'Post\PostController@view')->name('post.view');
    Route::post('/post/store', 'Post\PostController@store')->name('post.store');

    Route::get('/post/{id}/action/blacklist', 'Post\ActionController@blacklist')->name('post.action.blacklist');
    Route::get('/post/{id}/action/save', 'Post\ActionController@save')->name('post.action.save');
    Route::get('/post/{id}/action/report', 'Post\ActionController@report')->name('post.action.report');

    Route::group(['prefix' => 'me'], function () {
        Route::get('post/saved', 'Post\CustomerPost@saved')->name('customer.post.saved');
        Route::get('post/posted', 'Post\CustomerPost@posted')->name('customer.post.posted');
        Route::get('post/blacklist', 'Post\CustomerPost@blacklist')->name('customer.post.blacklist');
    });
});

