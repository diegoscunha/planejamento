<?php

namespace App\Http\Controllers;

use App\Acme\Importing\CsvFileImporter;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Redirect;

class CsvImportController extends BaseController
{
    public function show() {
        return view('importar');
    }
    /**
     * [POST] Form which will submit the file
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
          'semestre' => 'required',
          'arquivo' => 'required|file|mimes:csv,txt',
        ]);
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
                $csv_importer = new CsvFileImporter($headers, true);

                // Import our csv file
                if ($csv_importer->import($csv_file)) {
                    // Provide success message to the user
                    $message = 'Your file has been successfully imported!';
                } else {
                    $message = 'Your file did not import';
                }

            } else {
                // Provide a meaningful error message to the user
                // Perform any logging if necessary
                $message = 'You must provide a CSV file for import.';
            }

            return Redirect::back()->with('message', $message);
        }
    }
}
