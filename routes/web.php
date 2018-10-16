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

Route::get('/adm', 'HomeController@index')->name('adm');

Route::get('/adm/planejamento', 'PlanejamentoController@list')->middleware('auth')->name('listar-planejamento');
Route::get('/adm/planejamento/adicionar', 'PlanejamentoController@import')->middleware('auth')->name('importar-planejamento');
Route::post('/adm/planejamento/importar', 'CsvImportController@store')->middleware('auth')->name('importar');
Route::get('/adm/planejamento/ajustar-planejamento/{periodo_letivo}', 'PlanejamentoController@ajustar')->where('periodo_letivo', '[0-9]+')->middleware('auth')->name('ajustar-planejamento');

Route::get('/adm/planejamento/excluir/{periodo_letivo}', 'PlanejamentoController@delete')->where('periodo_letivo', '[0-9]+')->middleware('auth')->name('excluir-planejamento');
Route::get('/adm/planejamento/detalhes/{periodo_letivo}', 'PlanejamentoController@detalhes')->where('periodo_letivo', '[0-9]+')->middleware('auth')->name('detalhes-planejamento');

Route::get('/planejamento/obter-unidades/json', 'PlanejamentoController@obter_unidades');
Route::get('/planejamento/obter-salas/json', 'PlanejamentoController@obter_salas');
Route::get('/planejamento/consultar-disciplinas/json', 'PlanejamentoController@obter_disciplinas');

Route::get('/api/planejamento/isexist/{ano}/{semestre}', 'PlanejamentoController@isExist')->where('ano', '[0-9]+')->where('semestre', '[1-2]')->middleware('auth');
Route::post('/api/planejamento', 'PlanejamentoController@update');
Route::get('/api/planejamento/{periodo_letivo}/nao-alocadas/{unidade}', 'PlanejamentoController@getNaoAlocadas')->where('periodo_letivo', '[0-9]+')->middleware('auth');
Route::post('/api/planejamento/{periodo_letivo}/nao-alocadas/{unidade}/alocar', 'PlanejamentoController@alocar')->where('periodo_letivo', '[0-9]+')->middleware('auth');



Auth::routes();
