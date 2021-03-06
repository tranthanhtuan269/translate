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
Route::get('/404', function(){
	return view('error.404');
});

Route::post('/loginAjax', 'SiteController@loginAjax')->name('loginAjax');
Route::post('/uploadAjaxFile', 'SiteController@uploadAjaxFile')->name('uploadAjaxFile');
Route::get('/logout', 'SiteController@logout')->name('logout');

Route::get('/contributor', 'ContributorController@index')->name('contributor');
Route::get('/contributor/getData', 'ContributorController@getData')->name('contributor.getData');
Route::put('/contributor', 'ContributorController@update')->name('contributor.update');

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
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
	Route::get('translates/getDataAjaxReview', 'TranslateController@getDataAjaxReview');
	Route::post('translate', 'TranslateController@translate');
	Route::post('translate/auto', 'TranslateController@autoTranslate');
	Route::delete('translates/delete', 'TranslateController@delete');
	Route::delete('translates/delMulti', 'TranslateController@delMulti');
	Route::put('translates/adminUpdate', 'TranslateController@adminUpdate');
	Route::get('translates/createFileExport', 'TranslateController@createFileExport2');
	Route::get('translates/create-form-file', 'TranslateController@createFromFile');
	Route::post('translates/uploadSourceFile', 'TranslateController@uploadSourceFile');
	Route::post('translates/uploadTranslateFolder', 'TranslateController@uploadTranslateFolder');
	Route::get('translates/review', 'TranslateController@reviewContribute');
	Route::put('translates/confirm', 'TranslateController@confirm');
	Route::resource('translates', 'TranslateController');

	Route::get('groups/getDataAjax', 'TranslateGroupController@getDataAjax');
	Route::get('groups/{group}/getLanguages', 'TranslateGroupController@getLanguages');
	Route::post('groups/{group}/addLanguages', 'TranslateGroupController@addLanguages');
	Route::delete('groups/delMulti', 'TranslateGroupController@delMulti');
	Route::resource('groups', 'TranslateGroupController');

	Route::post('images/uploadImage', 'HomeController@uploadImage');
});