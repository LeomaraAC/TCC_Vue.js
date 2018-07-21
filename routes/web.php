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

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::middleware('auth')->prefix('master')->group(function(){
    Route::resource('grupos', 'Admin\GruposController');
    Route::get('/filtro/{campo?}/{sort?}/{filter?}', 'Admin\GruposController@filtro')->name('filtro');
    Route::get('/permissoes/{campo?}/{sort?}/{filter?}', 'Admin\PermissoesController@index')->name('permissoes');
});
