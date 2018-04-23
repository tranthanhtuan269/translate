<?php

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
    return view('welcome');
});

Route::post('/loginAjax', 'SiteController@loginAjax')->name('loginAjax');
Route::get('/logout', 'SiteController@logout')->name('logout');

Route::get('/contribute', 'SiteController@contribute')->name('contribute');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('category/getDataAjax', 'CategoryController@getDataAjax');
Route::delete('category/delMulti', 'CategoryController@delMulti');
Route::resource('category', 'CategoryController');
