<?php

namespace App\Http\Controllers;

use App\Calendar;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Importacao extends BaseController
{

    private function load_file_csv($file_name) {
        $csv = \League\Csv\Reader::createFromPath(storage_path('app/uploads/' . $file_name . '.csv'), 'r');
        $csv->setDelimiter(',');
        $csv->setHeaderOffset(0);
        return $csv;
    }

    public function show() {
        return view('importar');
    }

    public function importar_planejamento(Request $request) {
        $validatedData = $request->validate([
          'semestre' => 'required',
          'arquivo' => 'required|file|mimes:csv,txt',
        ]);

        if($request->hasFile('arquivo')) {
            $semestre = $request->input('semestre');
            $path = $request->arquivo->storeAs('uploads', $semestre . '.csv');
            $csv = $this->load_file_csv($semestre);
            $records = $csv->getRecords(['periodo_letivo', 'epoca', 'codigo_disciplina', 'inicio', 'turma', 'dia_semana',
            'dia_semana_ext', 'hora_inicial', 'hora_final', 'unidade', 'numero_sala', 'tipo_sala', 'capacidade_sala',
            'vagas_ofertadas', 'vagas_preenchidas', 'docente']);
            $insert = [];
            foreach ($records as $offset => $record) {
                $insert[] = $record;
            }
            //dd($insert);
            //set_time_limit(0);
            //dd($insert);
            //DB::transaction(function () {
            //    DB::connection()->disableQueryLog();
            //    DB::table('calendar')->insert(
            //        $insert
            //    );
            //});
            $query = sprintf("LOAD DATA LOCAL INFILE '%s' INTO TABLE calendar
                FIELDS TERMINATED BY ','
                IGNORE 1 LINES;", addslashes('C:\\demandas20181NOVO.csv'));

            DB::connection()->getpdo()->exec($query);
            echo 'teste';
            //return view('tabela', ['headers' => $csv->getHeader(), 'registros' => $records]);
        }
    }
}
