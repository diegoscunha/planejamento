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
Route::post('/api/planejamento/{periodo_letivo}/{unidade}/alocar', 'PlanejamentoController@alocar')->where('periodo_letivo', '[0-9]+')->middleware('auth');

Auth::routes();
// Registration Routes...
$this->get('/adm/usuario/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
$this->post('/adm/usuario/register', 'Auth\RegisterController@register');

// unidades
Route::get('/adm/unidade', 'UnidadeController@list')->name('listar-unidades')->middleware('auth');
Route::get('/adm/unidade/nova', 'UnidadeController@novo')->name('adicionar-unidade')->middleware('auth');
Route::get('/adm/unidade/editar/{id}', 'UnidadeController@editar')->where('id', '[0-9]+')->name('editar-unidade')->middleware('auth');
Route::post('/adm/unidade/save', 'UnidadeController@save')->name('salvar-unidade')->middleware('auth');
Route::get('/adm/unidade/delete/{id}', 'UnidadeController@delete')->where('id', '[0-9]+')->name('excluir-unidade')->middleware('auth');

// disciplinas
Route::get('/adm/disciplina', 'DisciplinaController@list')->name('listar-disciplinas')->middleware('auth');
Route::get('/adm/disciplina/nova', 'DisciplinaController@novo')->name('adicionar-disciplina')->middleware('auth');
Route::get('/adm/disciplina/editar/{id}', 'DisciplinaController@editar')->where('id', '[0-9]+')->name('editar-disciplina')->middleware('auth');
Route::post('/adm/disciplina/save', 'DisciplinaController@save')->name('salvar-disciplina')->middleware('auth');
Route::get('/adm/disciplina/delete/{id}', 'DisciplinaController@delete')->where('id', '[0-9]+')->name('excluir-disciplina')->middleware('auth');
