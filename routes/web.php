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

Route::get('/', function () {
    return view('contents/content');
});

Route::get('/category', 'CategoryController@index');
Route::post('/category/registrar', 'CategoryController@store');
Route::put('/category/actualizar', 'CategoryController@update');
Route::put('/category/desactivar', 'CategoryController@deactivate');
Route::put('/category/activar', 'CategoryController@activate');
Route::get('/category/chart', 'CategoryController@chartData');

//Rutas para articulos
Route::get('/article', 'ArticleController@index');
Route::post('/article/registrar', 'ArticleController@store');
Route::put('/article/actualizar', 'ArticleController@update');
Route::put('/article/desactivar', 'ArticleController@deactivate');
Route::put('/article/activar', 'ArticleController@activate');
