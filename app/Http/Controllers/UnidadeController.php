<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unidade;

class UnidadeController extends Controller
{
    /**
     * Show unidade list.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $result = Unidade::orderBy('codigo')->get();
        $breadcrumb = [
              'Home' => route('adm'),
              'Unidades' => ''
        ];
        return view('adm.unidade.list-unidades', ['breadcrumb' => $breadcrumb, 'unidades' => $result]);
    }
    /**
     * Register new unidade.
     *
     * @return \Illuminate\Http\Response
     */
    public function novo()
    {
        $breadcrumb = [
              'Home' => route('adm'),
              'Unidades' => route('listar-unidades'),
              'Adicionar Unidade' => ''
        ];
        return view('adm.unidade.edit-unidade', ['breadcrumb' => $breadcrumb, 'unidade' => new Unidade()]);
    }
    /**
     * Save unidade record.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $request->validate([
          'codigo' => 'required|max:5',
          'nome' => 'required'
        ]);

        $input = $request->input();
        $unidade = Unidade::find($input['id']);
        if(empty($unidade)) {
            $unidade = new Unidade();
            $unidade->id = $input['id'];
        }
        $unidade->codigo = strtoupper($input['codigo']);
        $unidade->nome = $input['nome'];
        try {
            $unidade->save();
        } catch(\Illuminate\Database\QueryException $e) {
            return back()->withErrors('Código de disciplina já cadastrado no banco de dados!')->withInput($request->all());
        }

        return redirect()->route('listar-unidades')->with('message', 'Unidade salva com sucesso!');
    }
    /**
     * Edit unidade.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function editar($id)
    {
        $unidade = Unidade::findOrfail($id);
        $breadcrumb = [
              'Home' => route('adm'),
              'Unidades' => route('listar-unidades'),
              'Editar Unidade' => ''
        ];
        return view('adm.unidade.edit-unidade', ['breadcrumb' => $breadcrumb, 'unidade' => $unidade]);
    }
    /**
     * Delete unidade record.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $req, $id)
    {
        if(!empty($id)) {
            $unidade = Unidade::findOrfail($id);
            $unidade->delete();
        }

        $req->session()->flash('message', 'Unidade excluída com sucesso!');
        return redirect()->route('listar-unidades');
    }
}
