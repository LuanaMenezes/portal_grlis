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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

//Route::get('/admin/login', 'AdminLoginController@index')->name('admin.dashboard');
Route::get('/admin', 'AdminController@index')->name('admin');
Route::get('/admin/indexFiltering', 'AdminController@indexFiltering')->name('admin/indexFiltering');

Route::get('/admin/login', 'Auth\AdminLoginController@index')->name('admin.login');
Route::post('/admin/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');

Route::get('clientes/import', 'ClienteController@import')->name('clientes/import');
Route::post('clientes/importXML', 'ClienteController@importXML')->name('clientes/importXML');

Route::resource('clientes', 'ClienteController', ['except' => 'destroy']);
Route::get('cliente/delete/{id}', 'ClienteController@destroy');
Route::get('cliente/updateStatus/{id}', 'ClienteController@updateStatus');

Route::resource('borderos', 'BorderoController', ['except' => 'destroy']);
Route::post('bordero/getSacadoData', 'BorderoController@getSacadoData');

Route::resource('terceiros', 'TerceiroController', ['except' => 'destroy']);
Route::get('terceiro/delete/{id}', 'TerceiroController@destroy');

Route::post('/script', 'PythonController@run');

Route::post('/consulta', 'PythonController@consulta');

Route::get('/404', 'ErrorController@pageNotExist')->name('404');

Route::resource('sacados', 'SacadoController', ['except' => 'destroy']);
Route::get('sacado/delete/{id}', 'SacadoController@destroy');
Route::get('sacado/updateStatus/{id}', 'SacadoController@updateStatus');

