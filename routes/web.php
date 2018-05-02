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

Route::get('/', 'SiteController@welcome');

Route::post('/loginAjax', 'SiteController@loginAjax')->name('loginAjax');
Route::post('/uploadAjaxFile', 'SiteController@uploadAjaxFile')->name('uploadAjaxFile');
Route::post('/uploadAjaxFileAndProcess', 'SiteController@uploadAjaxFileAndProcess')->name('uploadAjaxFileAndProcess');
Route::get('/logout', 'SiteController@logout')->name('logout');

Route::get('/contributor', 'ContributorController@index')->name('contributor');
Route::get('/contributor/getData', 'ContributorController@getData')->name('contributor.getData');
Route::put('/contributor', 'ContributorController@update')->name('contributor.update');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('user/getDataAjax', 'UserController@getDataAjax');
Route::get('user/getInfoByID/{id}', 'UserController@getInfoByID');
Route::resource('user', 'UserController');

Route::get('role/getDataAjax', 'RoleController@getDataAjax');
Route::get('role/getInfoByID/{id}', 'RoleController@getInfoByID');
Route::delete('role/delMulti', 'RoleController@delMulti');
Route::resource('role', 'RoleController');

Route::get('permission/getDataAjax', 'PermissionController@getDataAjax');
Route::delete('permission/delMulti', 'PermissionController@delMulti');
Route::resource('permission', 'PermissionController');

Route::get('category/getDataAjax', 'CategoryController@getDataAjax');
Route::delete('category/delMulti', 'CategoryController@delMulti');
Route::resource('category', 'CategoryController');

Route::get('language/getDataAjax', 'LanguageController@getDataAjax');
Route::delete('language/delMulti', 'LanguageController@delMulti');
Route::resource('language', 'LanguageController');

Route::get('group/getDataAjax', 'TranslateGroupController@getDataAjax');
Route::delete('group/delMulti', 'TranslateGroupController@delMulti');
Route::resource('group', 'TranslateGroupController');
