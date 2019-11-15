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
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');

    Route::group([
        'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});
//codes
Route::post("/sendcode","NoAuthController@sendCode");//body(phone)
Route::post("/confirmcode","ChangePersonalInfoController@confirmCode");//body(code)


//change username,first and last name
Route::post("/changedetails","ChangePersonalInfoController@changedetails");//body(username,firstname,lastname)
//change phone
Route::post("/generatecode","ChangePersonalInfoController@generateChangePhoneCode");
Route::post("/changephone","ChangePersonalInfoController@changephone");
//change password
Route::post("/changepassword","ChangePersonalInfoController@changePassword");

//sales
Route::post("/addsales","SalesController@insert");//body(quantity,costprice,purchase_id)
Route::post("/getmonthlysales","SalesController@getMonthlySales");//body(month,year)
Route::post("/getprofitloss","SalesController@getTotalSummary");//body(month,year)

//months
Route::post("/getmonths","PaymentsController@getMonths");//body(year)

//years
Route::get("/getyears","PaymentsController@getYear");

//expenses
Route::post("/addexpense","PaymentsController@insert");//body(amount,expensetype)
Route::post("/deleteexpense","PaymentsController@deleteExpense");//body(id)
Route::post("/getexpenses","PaymentsController@getExpenses");//body(month,year)
//suggested restock
Route::post("/suggestedrestock","AllTypesController@get_suggested_restock");

//stock
Route::post("/addstock","ItemController@insert");
Route::post("/gettypegroup","ItemGroupController@get_types");//body(category_name)
Route::post("/gettypeitem","AllTypesController@get_types_item");//body(nametype,namecategory)
Route::post("/deletestock","ItemController@deleteItem");//body(item_id)
Route::post("/editstock","ItemController@itemedit");//body(item_id)
Route::post("/restock","ItemController@newPurchase");//body(item_id,buyingp,quantity)

//sizes
Route::post("/addsize","SizeController@insert");//body(name)
Route::get("/getsizes","SizeController@getsizes");

//types
Route::post("/addtype","NoAuthController@insertTypes");//body(name,itemgroup)
Route::post("/getcategorytype","CategoryController@get_categories_type");//body(namegroup,namecategory)
Route::get("/getalltypes","AllTypesController@getAllTypes");


//categories
Route::post("/addcategory","NoAuthController@insert");//body(name)

//itemgroup
Route::post("/additemgroup","ItemGroupController@insert");//body(name)
Route::post("/getgroup","CategoryController@get_groups");//body(category_name)->specific group in category
Route::get("/getgroups","ItemGroupController@getGroups");//body(category_name)

//faqs
Route::post("/addquestion","QuestionsController@insert");//body(question,answer)
Route::get("/getqa","QuestionsController@getQA");

//message
Route::post("/sendmessage","MessagesController@insert");

//buyingprice
Route::post("/getbuyingprices","BuyingPriceController@getBuyingPrice");

//get
Route::get("/getcategories","NoAuthController@getCategories");
//Route::get("/gettypes","TypeController@getTypes");

Route::get('/secrets', 'SecretsController@show')->middleware('password.confirm');

