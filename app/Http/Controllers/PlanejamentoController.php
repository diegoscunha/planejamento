<?php

namespace App\Http\Controllers;

use DB;
use App\Calendar;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Controller as BaseController;

class PlanejamentoController extends BaseController
{

    public function index()
    {
        $semestres = DB::table('semestre')
                        ->select('ano', 'semestre')
                        ->where('finalizado', 1)
                        ->orderBy('ano', 'semestre')
                        ->get();
        return view('index', ['semestres' => $semestres]);
    }

    public function obter_unidades(Request $request)
    {
        $periodo_letivo = $request->query('semestre');
        $unidades = DB::table('calendars')
                        ->select('unidade')
                        ->where('periodo_letivo', $periodo_letivo)
                        ->where('numero_sala', '<>', '0')
                        ->groupBy('unidade')
                        ->orderBy('unidade')
                        ->get();

        return response()->json($unidades, Response::HTTP_OK);
    }

    public function obter_salas(Request $request)
    {
        $periodo_letivo = $request->query('semestre');
        $unidade = $request->query('unidade');
        $salas = DB::table('calendars')
                          ->select('numero_sala')
                          ->where('periodo_letivo', $periodo_letivo)
                          ->where('unidade', $unidade)
                          ->where('numero_sala', '<>', '0')
                          ->groupBy('numero_sala')
                          ->get();

        return response()->json($salas, Response::HTTP_OK);
    }

    public function obter_disciplinas(Request $request)
    {
        $periodo_letivo = $request->query('semestre');
        $unidade = $request->query('unidade');
        $numero_sala = $request->query('sala');
        $result = Calendar::where('periodo_letivo', $periodo_letivo)
                   ->where('unidade', $unidade)
                   ->where('numero_sala', $numero_sala)
                   ->get();
        $response = $this->parse_diciplinas($result);

        return response()->json($response, Response::HTTP_OK);
    }

    public function parse_diciplinas($array)
    {
        $data_atual = date('Y-m-d');
        $diasemana_numero = date('w', time()) + 1;
        $result = [];

        foreach ($array as $value) {
            $value->periodo_letivo = substr_replace($value->periodo_letivo, '.', 4, 0);
            // verificar como fazer isso direto na consulta
            if (strlen($value->hora_inicial)==3)
                $value->hora_inicial = '0' . $value->hora_inicial;
            if (strlen($value->hora_final)==3)
                $value->hora_final = '0' . $value->hora_final;

            $value->hora_inicial = substr_replace($value->hora_inicial, ':', 2, 0);
            $value->hora_final = substr_replace($value->hora_final, ':', 2, 0);

            $diff = abs($diasemana_numero - $value->dia_semana);
            if(intval($value->dia_semana) <= intval($diasemana_numero)) {
                $date_disciplina = date('Y-m-d', strtotime($data_atual . '- ' . $diff . ' days'));
            } else {
                $date_disciplina = date('Y-m-d', strtotime($data_atual . '+ ' . $diff . ' days'));
            }

            $value->text = $value->codigo_disciplina;
            $value->start_date = $date_disciplina . ' ' . $value->hora_inicial;
            $value->end_date = $date_disciplina . ' ' . $value->hora_final;
            $result[] = $value;
        }

        return $result;
    }

    public function import()
    {
        return view('adm.planejamento.import');
    }

    public function list()
    {
        return view('adm.planejamento.list');
    }

}
