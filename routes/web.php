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

Route::get('/import', function () {
    $csv = \League\Csv\Reader::createFromPath(storage_path('app/uploads/demandas20181NOVO.csv', 'r'));
    $csv->setDelimiter(';');
    $csv->setHeaderOffset(0);
    $records = $csv->getRecords();
    return view('tabela', ['headers' => $csv->getHeader(), 'registros' => $records]);
});
