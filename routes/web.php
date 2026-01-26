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
Route::get('/v/{token}', 'HomeController@verify_email')->name('verify.email');
Route::get('/', [App\Http\Controllers\HomeController::class, 'redirectHome']);

Route::group([
        'prefix' => '{locale}',
        'where' => ['locale' => '[a-zA-Z]{2}'],
        'middleware' => 'setLocale',
    ]
    , function () {
        //Views
        Route::view("terms", "frontend.pages.terms")->name("terms");

        //Routes
        Auth::routes(['verify' => true]);

        /**
         * SearchController
         * */
        Route::get("results", [App\Http\Controllers\DirectoryController::class, 'index'])->name("search.results");

        //For user logged only
        Route::get("properties/results", "PropertyController@index")->name("search.property.index");
        Route::post("properties/results", "PropertyController@buildQuery")->name("search.property.results");

        //*****************************************************************************************
        Route::get('/home', 'DirectoryController@index')->name('homepage');
        Route::get('/', [App\Http\Controllers\DirectoryController::class, 'index'])->name('home');
        Route::get('/result', 'DirectoryController@index')->name('result');
        Route::post('/search', 'DirectoryController@index')->name('search');

        //*****************************************************************************************
        Route::get('/profile', 'UserController@profile')->name('profile');
        Route::post('/profile', 'UserController@profile_update')->name('profile.update');

        //*****************************************************************************************
        Route::resource('credits', 'CreditController');
        Route::resource('properties', 'PropertyController')->except(['index']);
        Route::resource('reports', 'ReportController');

        Route::get('reports/remove/{id}', [\App\Http\Controllers\ReportController::class, 'remover'])->name('reports.remover');

        //*****************************************************************************************
        Route::get('mis/anuncios', [App\Http\Controllers\PropertyController::class, 'index'])->name('properties.index');
        Route::get('/s', [App\Http\Controllers\PropertyController::class,'index'])->name('search.property');

        //*****************************************************************************************
        //Formulario de Publicar y Accion de Publicar
        Route::get('/publish/{property}', 'PropertyController@publish')->name('publish.form');
        Route::post('/publish/{property}', 'PropertyController@publishing')->name('publish.store');

        Route::get('/extended/{property}', 'PropertyController@extended')->name('extend.form');
        Route::post('/extended/{property}', 'PropertyController@extending')->name('extend.store');

        Route::get('/private/{property}', 'PropertyController@extended')->name('extend.form');
        Route::post('/private/{property}', 'PropertyController@extending')->name('extend.store');

        Route::get('/private/{property}', 'PropertyController@privating')->name('privating.store');
        Route::get('admin/private/{property}', 'PropertyController@adminPrivate')->name('admin.privating.store');

        Route::get('/{slug}',
            [\App\Http\Controllers\DirectoryController::class, 'get_property_by_slug'])->name('get.property.by.slug');
        Route::get('/directorio/detalle/{id}', 'DirectoryController@show')->name('get.property.by.id');


        //********************************************************************************************************

        Route::get('paypal/pay/{credit}', 'PaymentController@payform')->name('paypal.form');
        Route::post('paypal/pay', 'PaymentController@payWithpaypal')->name('paypal.pay');
        Route::get('paypal/status', 'PaymentController@getPaymentStatus')->name('paypal.status');


        Route::get('send/credits',
            [App\Http\Controllers\UserController::class, 'sendCreditForm'])->name('send.credits');
        Route::post('send/credits', [App\Http\Controllers\UserController::class, 'creditsSent'])->name('sent.credits');

//        Route::post("photos/{id}", "PhotoController@update")->name("photos.update");
        Route::post("photos/{id}", [\App\Http\Controllers\PhotoController::class, 'update'])->name("photos.update");

        Route::post("photos/delete/{property}/{key}",
            [App\Http\Controllers\PhotoController::class, 'delete'])->name("photos.delete");

        Route::post('/property/gallery/{property}/upload',
            [App\Http\Controllers\PropertyController::class, 'upload'])
            ->name('property.gallery.upload');

        Route::post('/property/gallery/{id}',
            [App\Http\Controllers\PropertyController::class, 'delete'])
            ->name('property.gallery.delete');

        Route::get("photo/{property}/{key}", "PhotoController@display");

        Route::post("password/reset", "ActionController@password_reset")->name("users.password.reset");

        # Send Message to Owner product
        Route::post('send/product/message/{property}', "PropertyController@message")->name("send.product.message");
    });

//Route::redirect('/{slug}', app()->getLocale().'/{slug}');
Route::get('/{slug}', [App\Http\Controllers\HomeController::class, 'redirectSlug']);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('category/{category}/', 'CategoryController@child')->name('category.child');
    Route::resource('categories', 'CategoryController');

    Route::resource('countries', 'CountryController');
    Route::post('countries/create',
        [App\Http\Controllers\Admin\CountryController::class, 'store'])->name('countries.store');
    Route::post("countries", "CountryController@index")->name("country.search");

    Route::post("countries/{country}", "CountryController@show")->name("city.search");

//    Route::resource('cities', 'CityController', ['except' => ['create', 'show']]);
//    Route::get('/cities/create/{country}', 'CityController@create')->name('cities.create');

    Route::resource('users', 'UserController');
    Route::resource('credits', 'CreditController');
});
Route::group(['prefix' => 'api', 'as' => 'api.', 'middleware' => ['auth']], function () {
    Route::get('/property/images/{property}', [App\Http\Controllers\Api\ApiController::class, 'getImageFromPropertyByOwner'])->name('property.images');
});



