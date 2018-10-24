<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disciplina;

class DisciplinaController extends Controller
{
    public function list()
    {
        $result = Disciplina::orderBy('codigo')->get();
        $breadcrumb = [
              'Home' => route('adm'),
              'Disciplinas' => ''
        ];
        return view('adm.disciplina.list-disciplinas', ['breadcrumb' => $breadcrumb, 'disciplinas' => $result]);
    }

    public function novo()
    {
        $breadcrumb = [
              'Home' => route('adm'),
              'Disciplinas' => route('listar-disciplinas'),
              'Adicionar Disciplina' => ''
        ];
        return view('adm.disciplina.edit-disciplina', ['breadcrumb' => $breadcrumb, 'disciplina' => new Disciplina()]);
    }

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

        return redirect()->route('listar-disciplinas')->with('success', 'Disciplina salva com sucesso!');
    }

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

    public function delete(Request $req, $id)
    {
        if(!empty($id)) {
            $disciplina = Disciplina::findOrfail($id);
            $disciplina->delete();
        }

        $req->session()->flash('message', 'Disciplina excluída com sucesso!');
        return redirect()->route('listar-disciplinas');
    }
}
