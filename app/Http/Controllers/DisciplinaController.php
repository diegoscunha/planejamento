<?php

namespace App\Http\Controllers;

use DB;
use Log;
use App\Enum\OperationLog;
use App\Models\Disciplina;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DisciplinaController extends Controller
{
    /**
     * Show disciplina list.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $result = Disciplina::orderBy('codigo')->get();
        $breadcrumb = [
              'Home' => route('adm'),
              'Disciplinas' => ''
        ];
        return view('adm.disciplina.list-disciplinas', ['breadcrumb' => $breadcrumb, 'disciplinas' => $result]);
    }
    /**
     * Register new disciplina.
     *
     * @return \Illuminate\Http\Response
     */
    public function novo()
    {
        $breadcrumb = [
              'Home' => route('adm'),
              'Disciplinas' => route('listar-disciplinas'),
              'Adicionar Disciplina' => ''
        ];
        return view('adm.disciplina.edit-disciplina', ['breadcrumb' => $breadcrumb, 'disciplina' => new Disciplina()]);
    }
    /**
     * Save disciplina record.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $request->validate([
          'codigo' => 'required|max:10',
          'nome' => 'required'
        ]);

        $input = $request->input();
        $disciplina = Disciplina::find($input['id']);
        if(empty($disciplina)) {
            $disciplina = new Disciplina();
            $disciplina->id = $input['id'];
        }

        $old_disciplina = clone($disciplina);

        $disciplina->codigo = strtoupper($input['codigo']);
        $disciplina->nome = $input['nome'];
        try {
            $disciplina->save();

            if($disciplina->wasRecentlyCreated) {
                Log::channel('database')->info(OperationLog::CREATED, ['user' => \Auth::user(), 'funcionalidade' => 'Disciplina', 'new' => $disciplina]);
            } else {
                Log::channel('database')->info(OperationLog::UPDATED, ['user' => \Auth::user(), 'funcionalidade' => 'Disciplina', 'new' => $disciplina, 'old' => $old_disciplina]);
            }
        } catch(\Illuminate\Database\QueryException $e) {
            return back()->withErrors('Código de disciplina já cadastrado no banco de dados!')->withInput($request->all());
        }

        return redirect()->route('listar-disciplinas')->with('message', 'Disciplina salva com sucesso!');
    }
    /**
     * Edit disciplina.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function editar($id)
    {
        $disciplina = Disciplina::findOrfail($id);
        $breadcrumb = [
              'Home' => route('adm'),
              'Unidades' => route('listar-disciplinas'),
              'Editar Disciplina' => ''
        ];
        return view('adm.disciplina.edit-disciplina', ['breadcrumb' => $breadcrumb, 'disciplina' => $disciplina]);
    }
    /**
     * Delete disciplina record.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $req, $id)
    {
        if(!empty($id)) {
            $disciplina = Disciplina::findOrfail($id);
            $old_disciplina = clone($disciplina);
            $disciplina->delete();
            Log::channel('database')->info(OperationLog::DELETED, ['user' => \Auth::user(), 'funcionalidade' => 'Disciplina',  'old' => $old_disciplina]);
        }

        $req->session()->flash('message', 'Disciplina excluída com sucesso!');
        return redirect()->route('listar-disciplinas');
    }
    /**
     * Get disciplinas.
     *
     * @param int $semestre
     * @return \Illuminate\Http\Response
     */
    public function obter_disciplinas()
    {
        /* Cria um cahce de 60 min pora essa consulta */
        $minutes = now()->addMinutes(60);
        $result = Cache::remember('disciplinas', $minutes, function () {
            return DB::table('calendars as c')
                      ->distinct('c.codigo_disciplina')
                      ->select('c.codigo_disciplina as codigo', DB::raw('case when d.nome is null then " " when d.nome = "" then " " else d.nome end as descricao'))
                      ->join('unidades as u', 'u.codigo', '=', 'c.unidade')
                      ->leftJoin('disciplinas as d', 'd.codigo', '=', 'c.codigo_disciplina')
                      ->orderBy('codigo_disciplina')
                      ->get();
        });

        return response()->json($result, Response::HTTP_OK);
    }
}
