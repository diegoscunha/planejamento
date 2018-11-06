<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sala;

class SalaController extends Controller
{
    public function list()
    {
        $result = Sala::orderBy('numero_sala')->get();
        $breadcrumb = [
              'Home' => route('adm'),
              'Salas' => ''
        ];
        return view('adm.sala.list-salas', ['breadcrumb' => $breadcrumb, 'salas' => $result]);
    }

    public function novo()
    {
        $breadcrumb = [
              'Home' => route('adm'),
              'Salas' => route('listar-salas'),
              'Adicionar Sala' => ''
        ];
        return view('adm.sala.edit-sala', ['breadcrumb' => $breadcrumb, 'sala' => new Sala()]);
    }

    public function save(Request $request)
    {
        $request->validate([
          'codigo' => 'required|max:5',
          'nome' => 'required'
        ]);

        $input = $request->input();
        $sala = Sala::find($input['id']);
        if(empty($sala)) {
            $sala = new Unidade();
            $sala->id = $input['id'];
        }
        $sala->codigo = strtoupper($input['codigo']);
        $sala->nome = $input['nome'];
        try {
            $sala->save();
        } catch(\Illuminate\Database\QueryException $e) {
            return back()->withErrors('Número de sala já cadastrado no banco de dados!')->withInput($request->all());
        }

        return redirect()->route('listar-salas')->with('success', 'Sala salva com sucesso!');
    }

    public function editar($id)
    {
        $sala = Sala::findOrfail($id);
        $breadcrumb = [
              'Home' => route('adm'),
              'Salas' => route('listar-salas'),
              'Editar Sala' => ''
        ];
        return view('adm.sala.edit-sala', ['breadcrumb' => $breadcrumb, 'sala' => $sala]);
    }

    public function delete(Request $req, $id)
    {
        if(!empty($id)) {
            $sala = Sala::findOrfail($id);
            $sala->delete();
        }

        $req->session()->flash('message', 'Sala excluída com sucesso!');
        return redirect()->route('listar-salas');
    }
}
