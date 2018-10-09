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

Route::get('/', 'PlanejamentoController@index')->name('index');

Route::get('/planejamento/obter-unidades/json', 'PlanejamentoController@obter_unidades');
Route::get('/planejamento/obter-salas/json', 'PlanejamentoController@obter_salas');
Route::get('/planejamento/consultar-disciplinas/json', 'PlanejamentoController@obter_disciplinas');

Route::get('/planejamento', 'PlanejamentoController@list')->middleware('auth')->name('listar-planejamento');
Route::get('/planejamento/importar', 'PlanejamentoController@import')->middleware('auth')->name('importar-planejamento');
Route::post('/planejamento/importar', 'CsvImportController@store')->middleware('auth')->name('importar');
Route::get('/planejamento/ajustar-planejamento/{periodo_letivo}', 'PlanejamentoController@ajustar')->where('periodo_letivo', '[0-9]+')->middleware('auth')->name('ajustar-planejamento');

Route::post('/api/planejamento', 'PlanejamentoController@update');
Route::get('/api/planejamento/{periodo_letivo}/nao-alocadas/{unidade}', 'PlanejamentoController@getNaoAlocadas')->where('periodo_letivo', '[0-9]+')->middleware('auth');
Route::post('/api/planejamento/{periodo_letivo}/nao-alocadas/{unidade}/alocar', 'PlanejamentoController@alocar')->where('periodo_letivo', '[0-9]+')->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
