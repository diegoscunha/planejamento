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
                        ->where('status', 1)
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
        $result = [];

        foreach ($array as $value) {
            $value->periodo_letivo = format_periodo_letivo($value->periodo_letivo);

            $value->hora_inicial = format_hora($value->hora_inicial);
            $value->hora_final = format_hora($value->hora_final);

            $date_disciplina = format_date_scheduler($value->dia_semana);

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
        $result = DB::select("select concat(s.ano,'.',s.semestre) as semestre,
                              t.periodo_letivo,
                              sum(case when t.descricao='unidades' then total else 0 end) as unidades,
                              sum(case when t.descricao='disciplinas' then total else 0 end) as disciplinas,
                              sum(case when t.descricao='disciplinas_nao_alocadas' then total else 0 end) as disciplinas_nao_alocadas,
                              s.status
                              from semestre s
                              left join (select unid.periodo_letivo, 'unidades' as descricao, count(*) as total
                      		    from (select distinct(unidade), periodo_letivo from calendars) unid group by unid.periodo_letivo
                      		    union
                      		    select dis.periodo_letivo, 'disciplinas' as descricao, count(*) as total
                      		    from (select distinct(codigo_disciplina), periodo_letivo from calendars) dis group by dis.periodo_letivo
                      		    union
                      		    select nao.periodo_letivo, 'disciplinas_nao_alocadas' as nao_alocadas, count(*) as total
                      		    from (select distinct(codigo_disciplina), periodo_letivo from calendars where numero_sala='0') nao group by nao.periodo_letivo) t
                              on concat(s.ano,s.semestre)=t.periodo_letivo
                              group by s.ano, s.semestre, t.periodo_letivo
                              order by s.ano desc, s.semestre desc");
        return view('adm.planejamento.list', ['planejamentos' => $result]);
    }

    public function ajustar($periodo_letivo)
    {
        $semestre = substr_replace($periodo_letivo, '.', 4, 0);
        $unidades = DB::table('calendars')
                        ->select('unidade')
                        ->where('periodo_letivo', $periodo_letivo)
                        ->where('numero_sala', '<>', '0')
                        ->groupBy('unidade')
                        ->orderBy('unidade')
                        ->get();
        $dias = \App\DiaSemana::$dias;
        return view('adm.planejamento.ajustar', ['semestre' => $semestre, 'unidades' => $unidades, 'dias' => $dias]);
    }

    public function update(Request $req)
    {
        $request = json_decode($req->getContent());

        $response = [];
        if(!$req->query('editing') || !$request || !$request->id) {
            $response['error'] = 'Não foi possivel atualizar';
        } else {
            $calendar = Calendar::find($request->id);
            $calendar->unidade = $request->data->unidade;
            $calendar->numero_sala = $request->data->numero_sala;
            $calendar->tipo_sala = $request->data->tipo_sala;

            $start_date = explode(' ', $request->data->start_date);
            $end_date = explode(' ', $request->data->end_date);
            $calendar->hora_inicial = str_replace(':', '', $start_date[1]);
            $calendar->hora_final = str_replace(':', '', $end_date[1]);

            $calendar->dia_semana = $request->data->dia_semana;
            $calendar->dia_semana_ext = \App\DiaSemana::$dias[$request->data->dia_semana];

            $calendar->save();
            $response['action'] = 'update';
            $response['message'] = 'Registro salvo com sucesso!';
        }
        return response()->json($response, Response::HTTP_OK);
    }

    public function getNaoAlocadas($periodo_letivo, $unidade)
    {
        $reusult = DB::table('calendars')
                            ->select('id', 'codigo_disciplina', 'dia_semana_ext', 'dia_semana', 'hora_inicial', 'hora_final',
                            'periodo_letivo', 'unidade', 'docente')
                            ->where('periodo_letivo', $periodo_letivo)
                            ->where('unidade', $unidade)
                            ->where('numero_sala', '0')
                            ->get();
        $nao_alocadas = [];
        foreach ($reusult as $value) {
            $obj = new \stdClass();
            $obj->id = $value->id;

            $value->horario = format_hora($value->hora_inicial) . ' - ' . format_hora($value->hora_final);
            $value->periodo_letivo = format_periodo_letivo($value->periodo_letivo);
            $value->acao = "<a class='badge badge-primary alocar' href='#' role='button'>link</a>";

            $obj->data =  array_flatten((array)$value);
            $nao_alocadas[] = $obj;
        }
        $response['rows'] = $nao_alocadas;
        return response()->json($response, Response::HTTP_OK);
    }

    public function alocar(Request $req, $periodo_letivo, $unidade)
    {
        $response = [];
        $calendar = Calendar::find($req->input('modal_id'));
        $calendar->unidade = $req->input('modal_unidade');
        $calendar->numero_sala = $req->input('modal_sala');
        $calendar->dia_semana = $req->input('modal_dia_semana');
        $calendar->dia_semana_ext = \App\DiaSemana::$dias[$req->input('modal_dia_semana')];
        $calendar->hora_inicial = $req->input('modal_hora_inicial');
        $calendar->hora_final = $req->input('modal_hora_final');

        $calendar->save();
        $response['action'] = 'update';
        $response['message'] = 'Registro salvo com sucesso!';

        return response()->json($response, Response::HTTP_OK);
    }
}
