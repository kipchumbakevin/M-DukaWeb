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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//post
Route::post("/addstock","ItemController@insert");
Route::post("/addexpense","PaymentsController@insert");
Route::post("/addsales","SalesController@insert");
Route::post("/addcategory","CategoryController@insert");
Route::post("/addsize","SizeController@insert");
Route::post("/addtype","AllTypesController@insert");
Route::post("/gettypeitem","AllTypesController@get_types_item");
Route::post("/getstock","CategoryController@get_categories_item");
Route::post("/getcategoryitem","CategoryController@get_categories_item");

//get
Route::get("/getexpense","PaymentsController@getexpense");
Route::get("/getcategories","CategoryController@getCategories");
Route::get("/gettypes","TypeController@getTypes");
Route::get("/getalltypes","AllTypesController@getAllTypes");
Route::get("/getsales","SalesController@getTotalSales");
Route::get("/getsizes","SizeController@getsizes");
