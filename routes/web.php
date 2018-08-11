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

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::middleware('auth')->prefix('master')->group(function(){
    Route::resource('grupos', 'Admin\GruposController');
    Route::resource('usuarios', 'Admin\UsuariosController');
    Route::get('/grupos/filtro/{campo?}/{sort?}/{filter?}', 'Admin\GruposController@filtro')->name('grupos.filtro');
    Route::get('/find_ids', 'Admin\PermissoesController@getByIds');
    Route::get('/usuarios/filtro/{campo?}/{sort?}/{filter?}', 'Admin\UsuariosController@filtro')->name('usuarios.filtro');
    Route::get('/permissoes/{campo?}/{sort?}/{filter?}', 'Admin\PermissoesController@index')->name('permissoes');
});

// ['except' => 'logout']