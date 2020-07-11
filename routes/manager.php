<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin manager Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Manager\ManagerController@index')->name('manager');


Route::group(['prefix' => 'role', 'namespace' => 'Manager\Role'], function () {
    Route::get('/', 'RoleController@index')->name('manager.role');
    Route::get('/create', 'RoleController@index')->name('manager.role.create');
    Route::get('/{id}/view', 'RoleController@index')->name('manager.role.view');
    Route::post('/{id}/update', 'RoleController@index')->name('manager.role.update');
    Route::get('/{id}/delete', 'RoleController@index')->name('manager.role.delete');
});

Route::group(['prefix' => 'post', 'namespace' => 'Manager\Post'], function () {
    Route::get('/', 'PostController@index')->name('manager.post');
    Route::get('/create', 'PostController@create')->name('manager.post.create');
    Route::get('/{id}/view', 'PostController@index')->name('manager.post.view');
    Route::post('/{id}/update', 'PostController@index')->name('manager.post.update');
    Route::get('/{id}/delete', 'PostController@index')->name('manager.post.delete');
});

Route::group(['prefix' => 'user', 'namespace' => 'Manager\User'], function () {
    Route::get('/', 'UserController@index')->name('manager.user');
    Route::get('/create', 'UserController@index')->name('manager.user.create');
    Route::get('/{id}/view', 'UserController@index')->name('manager.user.view');
    Route::post('/{id}/update', 'UserController@index')->name('manager.user.update');
    Route::get('/{id}/delete', 'UserController@index')->name('manager.user.delete');
});