<?php

namespace App\Http\Controllers;

use DB;
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
        $disciplina->codigo = strtoupper($input['codigo']);
        $disciplina->nome = $input['nome'];
        try {
            $disciplina->save();
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
            $disciplina->delete();
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
    public function obter_disciplinas($semestre)
    {
        /* Cria um cahce de 10 min pora essa consulta */
        $minutes = now()->addMinutes(60);
        Cache::forget('disciplinas'.$semestre);
        $result = Cache::remember('disciplinas'.$semestre, $minutes, function () use ($semestre) {
            return DB::table('calendars as c')
                      ->distinct('c.codigo_disciplina')
                      //->select('c.codigo_disciplina as codigo', DB::raw('ifnull(d.nome, " ") as descricao'))
                      ->select('c.codigo_disciplina as codigo', DB::raw('case when d.nome is null then " " when d.nome = "" then " " else d.nome end as descricao'))
                      ->join('unidades as u', 'u.codigo', '=', 'c.unidade')
                      ->leftJoin('disciplinas as d', 'd.codigo', '=', 'c.codigo_disciplina')
                      ->where('periodo_letivo', $semestre)
                      ->orderBy('codigo_disciplina')
                      ->get();
        });

        return response()->json($result, Response::HTTP_OK);
    }
}
