<?php
/**
 * Created by PhpStorm.
 * Developer: Tariq Ayman ( tariq.ayman94@gmail.com )
 * Date: 2/1/20, 1:10 AM
 * Last Modified: 2/1/20, 1:07 AM
 * Project Name: Wafaq
 * File Name: api.php
 */

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
Route::post('products/active/get','CategoriesController@allActive');

Route::post('products/state/get','CategoriesController@viewWithState');
Route::post('products/category/condition/get','CategoriesController@productsCondition');
Route::post('categories/{id}','CategoriesController@update');
Route::resource('categories','CategoriesController');

Route::post('products/condition/get','ProductsController@productsCondition');

Route::post('products/{id}','ProductsController@update');
Route::resource('products','ProductsController');

Route::group(['prefix'=>'users'],function(){
    Route::post('login','UsersController@login');
    Route::post('profile','UsersController@profile');
    Route::post('register','UsersController@register');
    Route::post('logout','UsersController@logout');
    Route::post('contact','UsersController@contact');
    Route::get('control','CategoriesController@control')->middleware(['control']);

});

