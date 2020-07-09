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