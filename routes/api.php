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
Route::post("/addstock","ItemController@insert");
Route::post("/addexpense","PaymentsController@insert");
Route::post("/addcategory","CategoryController@insert");
Route::get("/getstock","ItemController@getitems");
Route::get("/getexpense","PaymentsController@getexpense");
