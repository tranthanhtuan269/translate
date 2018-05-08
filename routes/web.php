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

Route::get('/test', 'HomeController@test');

Route::post('/loginAjax', 'SiteController@loginAjax')->name('loginAjax');
Route::post('/uploadAjaxFile', 'SiteController@uploadAjaxFile')->name('uploadAjaxFile');
Route::post('/uploadAjaxFileAndProcess', 'SiteController@uploadAjaxFileAndProcess')->name('uploadAjaxFileAndProcess');
Route::get('/logout', 'SiteController@logout')->name('logout');

Route::get('/contributor', 'ContributorController@index')->name('contributor');
Route::get('/contributor/getData', 'ContributorController@getData')->name('contributor.getData');
Route::put('/contributor', 'ContributorController@update')->name('contributor.update');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/profile', 'HomeController@profile')->name('profile');

Route::get('user/getDataAjax', 'UserController@getDataAjax');
Route::get('user/getInfoByID/{id}', 'UserController@getInfoByID');
Route::put('user/updateSefl', 'UserController@updateSefl')->name('user.updateSefl');
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

Route::get('translates/getDataAjax', 'TranslateController@getDataAjax');
Route::post('translate', 'TranslateController@translate');
Route::delete('translates/delete', 'TranslateController@delete');
Route::delete('translates/delMulti', 'TranslateController@delMulti');
Route::put('translates/adminUpdate', 'TranslateController@adminUpdate');
Route::post('translates/createFileExport', 'TranslateController@createFileExport');
Route::resource('translates', 'TranslateController');

Route::get('groups/getDataAjax', 'TranslateGroupController@getDataAjax');
Route::get('groups/{group}/getLanguages', 'TranslateGroupController@getLanguages');
Route::post('groups/{group}/addLanguages', 'TranslateGroupController@addLanguages');
Route::delete('groups/delMulti', 'TranslateGroupController@delMulti');
Route::resource('groups', 'TranslateGroupController');

Route::post('images/uploadImage', 'HomeController@uploadImage');