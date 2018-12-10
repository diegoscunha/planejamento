<?php

namespace App\Http\Controllers;

use Log;
use App\Enum\OperationLog;
use App\Acme\Importing\CsvFileImporter;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Redirect;
use DB;

class CsvImportController extends BaseController
{
    /**
     * [POST] Form which will submit the file
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ano' => 'required',
            'semestre' => 'required',
            'arquivo' => 'required|file|mimes:csv,txt',
        ]);
        $ano = $request->input('ano');
        $semestre = $request->input('semestre');
        if($this->existe($ano, $semestre)) {
            // Check if form submitted a file
            if (Input::hasFile('arquivo')) {
                $csv_file = Input::file('arquivo');

                // You wish to do file validation at this point
                if ($csv_file->isValid()) {

                    // We can also create a CsvStructureValidator class
                    // So that we can validate the structure of our CSV file
                    $headers = [
                      "per_letivo",
                      "epoca",
                      "disciplina",
                      "inicio",
                      "turma",
                      "dia semana 1",
                      "dia semana 2",
                      "hora inicial",
                      "hora final",
                      "unidade",
                      "numero.sala",
                      "tipo.sala ",
                      "capacidade.sala",
                      "vagas.ofe",
                      "vagas.pre",
                      "docente"
                    ];
                    // Lets construct our importer
                    $csv_importer = new CsvFileImporter($headers, false);
                    try {
                        // Import our csv file
                        if ($csv_importer->import($csv_file)) {
                            // Provide success message to the user
                            $sem = new \App\Semestre();
                            $sem->ano = $ano;
                            $sem->semestre = $semestre;
                            $sem->user_id = \Auth::user()->id;
                            $sem->save();

                            Log::channel('database')->info(OperationLog::CREATED, ['user' => \Auth::user(), 'funcionalidade' => 'Planejamento', 'new' => 'Planejamento importado ' . $ano . $semestre]);

                            $message = 'Arquivo importado com sucesso!';
                            $session = 'success';
                        } else {
                            $message = 'Falha ao importar o arquivo!';
                            $session = 'error';
                        }
                    } catch (\Exception $e) {
                        $message = $e->getMessage();
                        $session = 'error';
                    }
                } else {
                    // Provide a meaningful error message to the user
                    // Perform any logging if necessary
                    $message = 'Você deve informar um arquivo CSV.';
                    $session = 'error';
                }
            } else {
                $message = 'Falha ao importar o arquivo!';
                $session = 'error';
            }
        } else {
            $message = 'O Planejamento informado já está cadastrado! Caso necessario exclua-o e o importe novamente.';
            $session = 'error';
        }
        return Redirect::back()->with($session, $message);
    }

    public function existe($ano, $semestre)
    {
          $result = DB::table('semestres')
                    ->where('ano', $ano)
                    ->where('semestre', $semestre)
                    ->get();
        return !empty($result);
    }
}
