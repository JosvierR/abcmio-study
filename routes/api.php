<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

//Route::get('cities/{country}','ApiController@get_cities_by_params')->name('cities');
//Route::get('cities/{id}','ApiController@get_city_by_country_id')->name('city.by.country');


Route::get('cities/{id}','ApiController@get_city_by_country_id')->name('city.by.country');
Route::get('category/children/{id}','ApiController@get_sub_categories_by_parent_id')->name('sub.by.parent');

Route::get('properties','ApiController@get_properies')->name('directory.properties');

//Route::resource('photos','PhotoController');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Api'], function () {
    Route::resource('countries', 'CountryController')->only(['index']);
    Route::resource('cities', 'CityController')->only(['index']);

});

