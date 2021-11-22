<?php

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

Route::get('/','CrudController@index');
Route::get('/get-employee','CrudController@getEmployee');
Route::get('/image/{name}','CrudController@image');
Route::get('/get-one','CrudController@getOne');
Route::get('/remove-employee','CrudController@removeEmployee');
Route::post('add-employee','CrudController@addEmployee');
Route::post('update-employee','CrudController@updateEmployee');




