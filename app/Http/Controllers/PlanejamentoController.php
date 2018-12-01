<?php

namespace App\Http\Controllers;

use DB;
use App\Calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Controller as BaseController;

class PlanejamentoController extends Controller
{
    /**
     * Exibe tela inicial do sistema
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $semestres = DB::table('semestres')
                        ->select('ano', 'semestre')
                        ->where('status', 1)
                        ->orderBy('ano', 'semestre')
                        ->get();
        return view('index', ['semestres' => $semestres]);
    }
    /**
     * Retorna unidades do semestre
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    public function obter_unidades(Request $request)
    {
        $periodo_letivo = $request->query('semestre');
        $unidades = DB::table('calendars')
                        ->select('unidade', 'unidades.nome')
                        ->join('unidades', 'unidades.codigo', '=', 'calendars.unidade')
                        ->where('periodo_letivo', $periodo_letivo)
                        ->where('numero_sala', '<>', '0')
                        ->groupBy('unidade', 'unidades.nome')
                        ->orderBy('unidade')
                        ->get();

        return response()->json($unidades, Response::HTTP_OK);
    }
    /**
     * Retorna salas do semestre e da unidade
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
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
    /**
     * Retorna disciplinas
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
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
    /**
     * Formata alguns dados das disciplinas
     *
     * @param Array $array
     * @return Array
     */
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
    /**
     * Exibe tela de importação de arquivo
     *
     * @return \Illuminate\Http\Response
     */
    public function import()
    {
        $anos = [];
        $ano_atual = (int)date('Y');
        $anos[] = $ano_atual - 2;
        $anos[] = $ano_atual - 1;
        $anos[] = $ano_atual;
        $anos[] = $ano_atual + 1;
        $anos[] = $ano_atual + 2;
        $breadcrumb = [
              'Home' => route('adm'),
              'Planejamentos' => route('listar-planejamento'),
              'Adicionar Planejamento' => ''
        ];
        return view('adm.planejamento.import', ['breadcrumb' => $breadcrumb, 'anos' => $anos]);
    }
    /**
     * Exibe tela de listagem de planejamentos
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $result = DB::select("select concat(s.ano,'.',s.semestre) as semestre,
                              t.periodo_letivo,
                              sum(case when t.descricao='unidades' then total else 0 end) as unidades,
                              sum(case when t.descricao='disciplinas' then total else 0 end) as disciplinas,
                              sum(case when t.descricao='aulas_nao_alocadas' then total else 0 end) as aulas_nao_alocadas,
                              s.status
                              from semestres s
                              left join (select unid.periodo_letivo, 'unidades' as descricao, count(*) as total
                      		    from (select distinct(unidade), periodo_letivo from calendars
                              where unidade in(select codigo from unidades)) unid group by unid.periodo_letivo
                      		    union
                      		    select dis.periodo_letivo, 'disciplinas' as descricao, count(*) as total
                      		    from (select distinct(codigo_disciplina), periodo_letivo from calendars
                              where unidade in(select codigo from unidades)) dis group by dis.periodo_letivo
                      		    union
                      		    select nao.periodo_letivo, 'aulas_nao_alocadas' as nao_alocadas, count(*) as total
                      		    from (select distinct(codigo_disciplina), periodo_letivo from calendars where numero_sala='0'
                              and unidade in(select codigo from unidades)) nao group by nao.periodo_letivo) t
                              on concat(s.ano,s.semestre)=t.periodo_letivo
                              group by s.ano, s.semestre, t.periodo_letivo, s.status
                              order by s.ano desc, s.semestre desc");
        foreach($result as $value) {
            $x = $value->aulas_nao_alocadas * 100;
            $value->percent = (int)(100 - ($x / $value->disciplinas));
            if($value->percent <= 40)
                $value->percent_class = 'bg-danger';
            elseif ($value->percent >= 40 && $value->percent <= 80)
                $value->percent_class = 'bg-warning';
            else
                $value->percent_class = 'bg-success';
        }

        $breadcrumb = [
              'Home' => route('adm'),
              'Planejamentos' => ''
        ];
        return view('adm.planejamento.list', ['planejamentos' => $result, 'breadcrumb' => $breadcrumb]);
    }
    /**
     * Exibe tela de ajuste de planejamento
     *
     * @param int $periodo_letivo
     * @return \Illuminate\Http\Response
     */
    public function ajustar($periodo_letivo)
    {
        if (!$this->isExist($periodo_letivo)) {
            abort(404);
        }
        $ano = substr($periodo_letivo, 0, 4);
        $sem = substr($periodo_letivo, 4);
        $semestre = format_periodo_letivo($periodo_letivo);
        $liberado = DB::table('semestres')
                                  ->select('status')
                                  ->where('ano', $ano)
                                  ->where('semestre', $sem)
                                  ->first();
        $unidades = DB::table('calendars')
                        ->select('unidade', 'unidades.nome')
                        ->join('unidades', 'unidades.codigo', '=', 'calendars.unidade')
                        ->where('periodo_letivo', $periodo_letivo)
                        ->where('numero_sala', '<>', '0')
                        ->groupBy('unidade', 'unidades.nome')
                        ->orderBy('unidade')
                        ->get();
        $dias = \App\DiaSemana::$dias;
        $horas = \App\Hora::$horas;
        $breadcrumb = [
              'Home' => route('adm'),
              'Planejamentos' => route('listar-planejamento'),
              'Ajuste de Planejamento' => ''
        ];
        return view('adm.planejamento.ajustar', ['breadcrumb' => $breadcrumb, 'semestre' => $semestre, 'unidades' => $unidades, 'dias' => $dias, 'horas' => $horas, 'liberado' => $liberado->status]);
    }
    /**
     * Atualiza os dados de uma aula
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
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
    /**
     * Retorna as aulas não alocadas
     *
     * @param int $periodo_letivo
     * @param string $unidade
     * @return string
     */
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
            $value->acao = "<a class='badge badge-primary alocar' href='#' role='button'>Alocar</a>";

            $obj->data =  array_flatten((array)$value);
            $nao_alocadas[] = $obj;
        }
        $response['rows'] = $nao_alocadas;
        return response()->json($response, Response::HTTP_OK);
    }
    /**
     * Aloca uma disciplina em uma sala
     *
     * @param \Illuminate\Http\Request $request
     * @param int $periodo_letivo
     * @param string $unidade
     * @return string
     */
    public function alocar(Request $request, $periodo_letivo, $unidade)
    {
        $response = [];
        $calendar = Calendar::find($request->input('modal_id'));
        $calendar->unidade = $request->input('modal_unidade');
        $calendar->numero_sala = $request->input('modal_sala');
        $calendar->dia_semana = $request->input('modal_dia_semana');
        $calendar->dia_semana_ext = \App\DiaSemana::$dias[$request->input('modal_dia_semana')];
        $calendar->hora_inicial = $request->input('modal_hora_inicial');
        $calendar->hora_final = $request->input('modal_hora_final');

        $calendar->save();
        $response['action'] = 'update';
        $response['message'] = 'Registro salvo com sucesso!';

        return response()->json($response, Response::HTTP_OK);
    }
    /**
     * Exclui um planejamento
     *
     * @param \Illuminate\Http\Request $request
     * @param int $periodo_letivo
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $periodo_letivo)
    {
        if(!empty($periodo_letivo)) {
            DB::transaction(function () use ($periodo_letivo) {
                $ano = substr($periodo_letivo, 0, 4);
                $semestre = substr($periodo_letivo, 4);
                DB::table('semestres')->where('ano', $ano)->where('semestre', $semestre)->delete();
                DB::table('calendars')->where('periodo_letivo', $ano.$semestre)->delete();
            });
        }

        $request->session()->flash('message', 'Planejamento excluído com sucesso!');
        return redirect()->route('listar-planejamento');
    }
    /**
     * Exibe a tela de detalhes de um planejamento
     *
     * @param int $periodo_letivo
     * @return \Illuminate\Http\Response
     */
    public function detalhes($periodo_letivo)
    {
          if (!$this->isExist($periodo_letivo)) {
              abort(404);
          }
          $unidades = DB::select("select t.unidade,
                                  	   sum(case when t.descricao='qtd_salas' then total else 0 end) as qtd_salas,
                                  	   sum(case when t.descricao='qtd_turmas' then total else 0 end) as qtd_turmas,
                                       sum(case when t.descricao='qtd_disciplinas' then total else 0 end) as qtd_disciplinas
                                  from (select unidade, 'qtd_salas' as descricao, count(numero_sala) as total
                                        from (select unidade, numero_sala from calendars where periodo_letivo = ? and unidade in(select codigo from unidades) group by numero_sala, unidade) salas
                                        group by unidade
                                        union
                                  	  select unidade, 'qtd_turmas' as descricao, count(turma) as total
                                        from (select unidade, turma from calendars where periodo_letivo = ? and unidade in(select codigo from unidades) group by turma, unidade) turmas
                                        group by unidade
                                  	  union
                                        select unidade, 'qtd_disciplinas' as descricao, count(codigo_disciplina) as total
                                        from (select unidade, codigo_disciplina from calendars where periodo_letivo = ? and unidade in(select codigo from unidades) group by codigo_disciplina, unidade) disciplinas
                                        group by unidade) t
                                  group by unidade
                                  order by t.unidade", [$periodo_letivo,$periodo_letivo,$periodo_letivo]);
          $planejamento = new \stdClass();
          $planejamento->semestre = format_periodo_letivo($periodo_letivo);
          $planejamento->unidades = $unidades;
          $breadcrumb = [
                'Home' => route('adm'),
                'Planejamentos' => route('listar-planejamento'),
                'Detalhes Planejamento' => ''
          ];
          return view('adm.planejamento.detalhes', ['breadcrumb' => $breadcrumb, 'planejamento' => $planejamento]);
    }
    /**
     * Verifica se existe o planejamento para o periodo letivo informado
     *
     * @param int $periodo_letivo
     * @return boolean
     */
    private function isExist($periodo_letivo)
    {
        $ano = substr($periodo_letivo, 0, 4);
        $semestre = substr($periodo_letivo, 4);
        $result = DB::table('semestres')
                    ->where('ano', $ano)
                    ->where('semestre', $semestre)
                    ->get();
        return count($result)>0 ? true : false;
    }
    /**
     * Altera o status do planejamento
     *
     * @param \Illuminate\Http\Request $request
     * @param int $periodo_letivo
     * @return void
     */
    public function liberar(Request $request, $periodo_letivo)
    {
        $ano = substr($periodo_letivo, 0, 4);
        $sem = substr($periodo_letivo, 4);
        $semestre = \App\Semestre::where('ano', $ano)
                                  ->where('semestre', $sem)
                                  ->first();
        $semestre->status = !$semestre->status;
        $semestre->save();
    }
    /**
     * Retornar disciplinas
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    public function obter_disciplinas_grid(Request $request)
    {
        $semestre = $request->query('semestre');
        $disciplinas = explode(',', $request->query('disciplinas'));

        $result = DB::table('calendars as c')
                    ->select('c.periodo_letivo', 'c.codigo_disciplina', 'd.nome as disciplina', 'u.nome as unidade', 'numero_sala', 'turma', 'dia_semana_ext as dia', 'hora_inicial', 'hora_final', DB::raw('trim(docente) as docente'))
                    ->join('unidades as u', 'u.codigo', '=', 'c.unidade')
                    ->leftJoin('disciplinas as d', 'd.codigo', '=', 'c.codigo_disciplina')
                    ->where('c.periodo_letivo', $semestre)
                    ->whereIn('c.codigo_disciplina', $disciplinas)
                    ->orderBy('c.codigo_disciplina')
                    ->orderBy('c.turma')
                    ->orderBy('c.dia_semana')
                    ->orderBy('c.hora_inicial')
                    ->get();
       foreach ($result as $value) {
          $value->periodo_letivo = format_periodo_letivo($value->periodo_letivo);
          $value->horario = format_hora($value->hora_inicial) . ' - ' . format_hora($value->hora_final);
       }

       return response()->json($result, Response::HTTP_OK);
    }
    /**
     * Verifica se existe choque de horarios
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return string
     */
    public function choque_horario(Request $request, $id)
    {
        $periodo_letivo = $request->query('periodo_letivo');
        $unidade = $request->query('unidade');
        $numero_sala = $request->query('sala');
        $dia_semana = $request->query('dia_semana');
        $hora_inicial = $request->query('hora_inicial');
        $result = DB::table('calendars')
                    ->where('id', '<>', $id)
                    ->where('periodo_letivo', $periodo_letivo)
                    ->where('unidade', $unidade)
                    ->where('numero_sala', $numero_sala)
                    ->where('dia_semana', $dia_semana)
                    ->when(($hora_inicial[0]=='0'), function($query) use ($hora_inicial) {
                        return $query->where(function($query) use ($hora_inicial) {
                                                $query->where('hora_inicial', $hora_inicial)
                                                      ->orWhere('hora_inicial', substr($hora_inicial, 1));
                                            });
                    }, function($query) use ($hora_inicial) {
                        return $query->where('hora_inicial', $hora_inicial);
                    })
                    ->get();
       $choque_horario = !$result->isEmpty();
       return response()->json(['choque_horario' => $choque_horario], Response::HTTP_OK);
    }
    /**
     * Desalocar a disciplina da sala de aula
     *
     * @param int $id
     * @return void
     */
    public function desalocar($id)
    {
        $disciplina = Calendar::find($id);
        $disciplina->numero_sala = "0";
        $disciplina->save();
    }
    /**
     * Buscar horarios ociosos de salas
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    public function horarios_ociosos(Request $request)
    {
        $periodo_letivo = $request->input('periodo_letivo');
        $unidade = $request->input('unidade');
        $sala = $request->input('sala');

        $result = DB::table('calendars as c')
                    ->select('c.numero_sala', 'c.dia_semana', 'c.hora_inicial', 'c.hora_final')
                    ->join('unidades as u', 'u.codigo', '=', 'c.unidade')
                    ->leftJoin('salas as s', 's.unidade_id', '=', 'u.id')
                    ->where('c.periodo_letivo', $periodo_letivo)
                    ->where('c.unidade', $unidade)
                    ->where('c.numero_sala', $sala)
                    ->groupBy('c.numero_sala', 'c.dia_semana', 'c.hora_inicial', 'c.hora_final')
                    ->orderBy('c.dia_semana')
                    ->orderBy('c.numero_sala')
                    ->get();
        $result = collect($result)->map(function($x){ return (array) $x; });
        $salasgroup = $result->groupBy('dia_semana');

        $ociosos = [];
        for ($i=2;$i<=7;$i++) {
            $lista = $salasgroup->get($i);
            $ociosos[$i] = $lista ? $this->listar_horarios_ociosos($lista) : \App\Hora::$horarios;
        }
        return response()->json(['horarios_ociosos' => $ociosos], Response::HTTP_OK);
    }
    /**
     * Listar horarios ociosos para as salas
     *
     * @param Array $lista
     * @return Array
     */
    private function listar_horarios_ociosos($lista)
    {
        $horarios = \App\Hora::$horarios;
        foreach ($lista as $value) {
            $inicial = format_hora($value['hora_inicial']);
            $final = format_hora($value['hora_final']);
            $remover = horarios_remover($inicial, $final);
            foreach ($remover as $remove) {
                unset($horarios[$remove]);
            }
        }
        return $horarios;
    }
    /**
     * Gerar relatório de alocação
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    public function realtorio_alocacao(Request $request)
    {
        /* Cria um cahce de 60 min pora essa consulta */
        $minutes = now()->addMinutes(60);
        $result = Cache::remember('relatorio', $minutes, function () {
            return DB::table('calendars as c')
                    ->select('c.periodo_letivo', 'c.unidade', 'u.nome', 'c.codigo_disciplina', DB::raw('ifnull(d.nome, " ") as nome_disciplina'), 'c.turma',
                             'c.numero_sala', 'c.dia_semana', DB::raw('trim(c.dia_semana_ext) as dia_semana_ext'), 'c.hora_inicial',
                             'c.hora_final', DB::raw('trim(c.docente) as docente'))
                    ->join('unidades as u', 'u.codigo', '=', 'c.unidade')
                    ->leftJoin('disciplinas as d', 'd.codigo', '=', 'c.codigo_disciplina')
                    ->where('c.periodo_letivo', '20182')
                    ->where('c.unidade', 'PAF')
                    ->groupBy('c.periodo_letivo', 'c.unidade', 'u.nome', 'c.codigo_disciplina', 'd.nome', 'c.numero_sala', 'c.turma', 'c.dia_semana', 'c.dia_semana_ext', 'c.hora_inicial', 'c.hora_final', 'c.docente')
                    ->orderBy('c.unidade')
                    ->orderBy('c.codigo_disciplina')
                    ->orderBy('c.turma')
                    ->orderBy('c.numero_sala')
                    ->orderBy('c.dia_semana')
                    ->get();
        });
        $result = collect($result)->map(function($x){ return (array) $x; });
        $unidadesgroup = $result->groupBy('unidade');

        $html = view('adm.relatorios.rel_alocacao', ['semestre' => '2018.2', 'dados' => $unidadesgroup])->render();
        //dd($html);
        $pdf = \PDF::loadHTML($html);
        return $pdf->download('invoice.pdf');
        //return view('adm.relatorios.rel_alocacao', ['semestre' => '2018.2', 'dados' => $unidadesgroup]);
    }
}
