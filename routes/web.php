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
Route::get('/planejamento/consultar-disciplinas-grid/json', 'PlanejamentoController@obter_disciplinas_grid');

Route::get('/api/planejamento/isexist/{ano}/{semestre}', 'PlanejamentoController@isExist')->where('ano', '[0-9]+')->where('semestre', '[1-2]')->middleware('auth');
Route::post('/api/planejamento', 'PlanejamentoController@update')->middleware('auth');
Route::get('/api/planejamento/{periodo_letivo}/nao-alocadas/{unidade}', 'PlanejamentoController@getNaoAlocadas')->where('periodo_letivo', '[0-9]+')->middleware('auth');
Route::post('/api/planejamento/{periodo_letivo}/{unidade}/alocar', 'PlanejamentoController@alocar')->where('periodo_letivo', '[0-9]+')->middleware('auth');
Route::get('/api/disciplinas', 'DisciplinaController@obter_disciplinas');
Route::get('/api/planejamento/{semestre}/liberar', 'PlanejamentoController@liberar')->where('semestre', '[0-9]+')->middleware('auth');
Route::get('/api/planejamento/choque-horario/{id}', 'PlanejamentoController@choque_horario')->where('id', '[0-9]+')->middleware('auth');
Route::get('/api/planejamento/desalocar/{id}', 'PlanejamentoController@desalocar')->where('id', '[0-9]+')->middleware('auth');

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

// salas
Route::get('/adm/sala', 'SalaController@list')->name('listar-salas')->middleware('auth');
Route::get('/adm/sala/nova', 'SalaController@novo')->name('adicionar-sala')->middleware('auth');
Route::get('/adm/sala/editar/{id}', 'SalaController@editar')->where('id', '[0-9]+')->name('editar-sala')->middleware('auth');
Route::post('/adm/sala/save', 'SalaController@save')->name('salvar-sala')->middleware('auth');
Route::get('/adm/sala/delete/{id}', 'SalaController@delete')->where('id', '[0-9]+')->name('excluir-sala')->middleware('auth');

// usuarios
Route::get('/adm/usuario', 'UserController@list')->name('listar-usuarios')->middleware('auth');
Route::get('/adm/usuario/ativar/{id}', 'UserController@ativar_desativar')->where('id', '[0-9]+')->name('ativar-usuario')->middleware('auth');
Route::get('/adm/usuario/desativar/{id}', 'UserController@ativar_desativar')->where('id', '[0-9]+')->name('desativar-usuario')->middleware('auth');
Route::get('/adm/usuario/editar/{id}', 'UserController@editar')->where('id', '[0-9]+')->name('editar-usuario')->middleware('auth');
Route::post('/adm/usuario/save', 'UserController@save')->name('salvar-usuario')->middleware('auth');

Route::get('/adm/usuario/alterar-senha', 'UserController@show_alterar_senha')->name('alterar-senha')->middleware('auth');
Route::post('/adm/usuario/alterar-senha', 'UserController@update_password')->name('alterar-senha')->middleware('auth');

Auth::routes();
// Registration Routes...
$this->get('/adm/usuario/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
$this->post('/adm/usuario/register', 'Auth\RegisterController@register');
