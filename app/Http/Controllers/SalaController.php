<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sala;
use App\Models\Unidade;

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
        $unidades = Unidade::orderBy('codigo')->get();
        $acessibilidade = \App\Enum\Acessibilidade::getConstants();
        $ar = \App\Enum\ArCondicionado::getConstants();
        $projetorTv = \App\Enum\ProjetorTV::getConstants();
        $equipamentoSom = \App\Enum\EquipamentoSom::getConstants();
        $prioridade = \App\Enum\Prioridade::getConstants();

        $breadcrumb = [
              'Home' => route('adm'),
              'Salas' => route('listar-salas'),
              'Adicionar Sala' => ''
        ];
        return view('adm.sala.edit-sala', ['breadcrumb' => $breadcrumb,
                                           'sala' => new Sala(),
                                           'unidades' => $unidades,
                                           'acessibilidade' => $acessibilidade,
                                           'ar' => $ar,
                                           'projetorTv' => $projetorTv,
                                           'equipamentoSom' => $equipamentoSom,
                                           'prioridade' => $prioridade]);
    }

    public function save(Request $request)
    {
        $request->validate([
          'unidade_id' => 'required',
          'numero_sala' => 'required',
          'num_cadeiras' => 'required|numeric',
          'capacidade_max' => 'required|numeric',
          'acessibilidade' => 'required',
          'ar_condicionado' => 'required',
          'projetor_tv' => 'required',
          'equipamento_som' => 'required',
          'prioridade_uso' => 'required',
        ]);

        $input = $request->input();
        $sala = Sala::find($input['id']);
        if(empty($sala)) {
            $sala = new Sala();
        }

        $sala->disponivel = isset($input['disponivel']) ? true : false;
        $sala->quadro_grande = isset($input['quadro_grande']) ? true : false;
        $sala->unidade_id = $input['unidade_id'];
        $sala->numero_sala = $input['numero_sala'];
        $sala->num_cadeiras = $input['num_cadeiras'];
        $sala->capacidade_max = $input['capacidade_max'];
        $sala->acessibilidade = $input['acessibilidade'];
        $sala->ar_condicionado = $input['ar_condicionado'];
        $sala->projetor_tv = $input['projetor_tv'];
        $sala->equipamento_som = $input['equipamento_som'];
        $sala->prioridade_uso = $input['prioridade_uso'];

        $sala->save();

        return redirect()->route('listar-salas')->with('message', 'Sala salva com sucesso!');
    }

    public function editar($id)
    {
        $sala = Sala::findOrfail($id);
        $unidades = Unidade::orderBy('codigo')->get();
        $acessibilidade = \App\Enum\Acessibilidade::getConstants();
        $ar = \App\Enum\ArCondicionado::getConstants();
        $projetorTv = \App\Enum\ProjetorTV::getConstants();
        $equipamentoSom = \App\Enum\EquipamentoSom::getConstants();
        $prioridade = \App\Enum\Prioridade::getConstants();
        $breadcrumb = [
              'Home' => route('adm'),
              'Salas' => route('listar-salas'),
              'Editar Sala' => ''
        ];
        return view('adm.sala.edit-sala', ['breadcrumb' => $breadcrumb,
                                           'sala' => $sala,
                                           'unidades' => $unidades,
                                           'acessibilidade' => $acessibilidade,
                                           'ar' => $ar,
                                           'projetorTv' => $projetorTv,
                                           'equipamentoSom' => $equipamentoSom,
                                           'prioridade' => $prioridade]);
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
