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

Route::apiResource('/v1/user', 'UserController');

Route::post('/v1/transaction', 'TransactionController@create');
Route::get('/v1/transaction/{id}', 'TransactionController@show');
Route::get('/v1/transaction/user/{id}', 'TransactionController@showByUser');

Route::post('/v1/interest', 'InterestController@create');
Route::get('/v1/interest/{id}', 'InterestController@show');
Route::get('/v1/interest/{id}/details', 'InterestController@showInterest');

Route::post('/v1/task', 'TodoController@createTask');
Route::get('/v1/todo/{id}', 'TodoController@showTodoDetails');
Route::post('/v1/todo', 'TodoController@finishTodo');
Route::get('/v1/todo/user/{id}', 'TodoController@showTodo');


