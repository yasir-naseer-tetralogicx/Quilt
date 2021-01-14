<?php

use App\Http\Controllers\HelperController;
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

Route::get('/', 'ShopifyProductController@index');

Route::get('/excel', 'CustomCollectionsController@uploadCsvToDatabase');
Route::get('/import/excel/two', 'CustomCollectionsController@uploadCsvTwoToDatabase');


Route::get('/push/collections', 'CustomCollectionsController@push')->name('collections.push');

Route::get('/count', function() {
    $api = HelperController::config();
    $result = $api->rest('GET', '/admin/smart_collections/count.json', null,[],true);
    dd($result);
});

Route::get('/store/products', 'ShopifyProductController@storeProducts');

Route::get('/duplicate', 'ShopifyProductController@getDuplicates');



