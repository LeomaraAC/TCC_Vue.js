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


Route::middleware('auth')->group(function(){
    Route::prefix('master')->group(function(){
        Route::resource('grupos', 'Admin\GruposController')->except('show');
        Route::resource('usuarios', 'Admin\UsuariosController')->except('show');
        Route::get('/grupos/filtro/{campo?}/{sort?}/{filter?}', 'Admin\GruposController@filtro')->name('grupos.filtro');
        Route::get('/find_ids', 'Admin\PermissoesController@getByIds');
        Route::get('/usuarios/filtro/{campo?}/{sort?}/{filter?}', 'Admin\UsuariosController@filtro')->name('usuarios.filtro');
        Route::get('/permissoes/{campo?}/{sort?}/{filter?}', 'Admin\PermissoesController@index')->name('permissoes');
    });

    Route::prefix('atendimento')->group(function() {
        Route::resource('tipo', 'Atendimentos\TipoAtendimentoController')->except('show');
        Route::get('/tipo/filtro/{campo?}/{sort?}/{filter?}', 'Atendimentos\TipoAtendimentoController@filtro')
                    ->name('tipo.filtro');
        Route::resource('agendamento', 'Atendimentos\AgendamentoController');
        Route::get('/agendamento/filtro/{campo?}/{sort?}/{responsavel?}/{filter?}', 'Atendimentos\AgendamentoController@filtro')
                    ->name('agendamento.filtro');
        
    });

    Route::get('/alunos', 'AlunoController@index')->name('alunos.index');
    Route::get('/alunos/{aluno}', 'AlunoController@show')->name('alunos.show');
    Route::get('/cursos', 'CursoController@getCursos');
    Route::get('/matricula/{cpf}', 'MatriculaController@getMatricula');
    Route::get('/matricula/find/{prontuario}', 'MatriculaController@getTurma');
    Route::get('/alunos/filtro/{campo?}/{sort?}/{filter?}', 'AlunoController@filtro')->name('alunos.filtro');
    Route::prefix('import')->group(function(){
        Route::post('/alunos', 'ImportController@importAlunos');
    });
});