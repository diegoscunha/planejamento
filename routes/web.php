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
    return view('welcome');
});

Route::get('/importar/planejamento', 'Importacao@show')->middleware('auth')->name('importar');
Route::post('/importar/planejamento', 'CsvImportController@store')->middleware('auth')->name('importar');

Route::get('/visualizar', 'PlanejamentoController@show')->name('vizualizar');
Route::get('/obter-unidades', 'PlanejamentoController@obter_unidades');
Route::get('/obter-salas', 'PlanejamentoController@obter_salas');
Route::get('/consultar-disciplinas', 'PlanejamentoController@obter_disciplinas')->name('consultar');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
