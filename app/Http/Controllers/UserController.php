<?php

namespace App\Http\Controllers;

use Log;
use App\Enum\OperationLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    /**
     * Show user list.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $result = User::orderBy('name')->get();
        $breadcrumb = [
              'Home' => route('adm'),
              'Usuários' => ''
        ];
        return view('adm.user.list-usuarios', ['breadcrumb' => $breadcrumb, 'usuarios' => $result]);
    }
    /**
     * Edit user.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function editar($id)
    {
        $usuario = User::findOrfail($id);
        $breadcrumb = [
              'Home' => route('adm'),
              'Usuários' => route('listar-usuarios'),
              'Editar Usuário' => ''
        ];
        return view('adm.user.edit-usuario', ['breadcrumb' => $breadcrumb, 'usuario' => $usuario]);
    }
    /**
     * Save user record.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255'
        ]);

        $input = $request->input();
        $usuario = User::find($input['id']);

        $old_usuario = clone($usuario);

        $usuario->name = $input['name'];
        $usuario->email = $input['email'];

        try {
            $usuario->save();

            Log::channel('database')->info(OperationLog::UPDATED, ['user' => \Auth::user(), 'funcionalidade' => 'Usuário', 'new' => $usuario, 'old' => $old_usuario]);
        } catch(\Illuminate\Database\QueryException $e) {
            return back()->withErrors('E-mail já cadastrado no banco de dados!')->withInput($request->all());
        }

        return redirect()->route('listar-usuarios')->with('message', 'Usuário salvo com sucesso!');
    }
    /**
     * Activate or disable user record.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function ativar_desativar(Request $request, $id)
    {
        $msg = "";
        if(!empty($id)) {
            $usuario = User::findOrfail($id);
            $old_usuario = clone($usuario);
            $usuario->ativo = !$usuario->ativo;
            $usuario->save();
            Log::channel('database')->info(OperationLog::UPDATED, ['user' => \Auth::user(), 'funcionalidade' => 'Usuário', 'new' => $usuario, 'old' => $old_usuario]);
            $msg = $usuario->ativo ? 'Usuário ativado com sucesso!' : 'Usuário desativado com sucesso!';
        }

        $request->session()->flash('message', $msg);
        return redirect()->route('listar-usuarios');
    }
    /**
     * Show change password.
     *
     * @return \Illuminate\Http\Response
     */
    public function show_alterar_senha()
    {
        $breadcrumb = [
              'Home' => route('adm'),
              'Usuários' => route('listar-usuarios'),
              'Alterar Senha' => ''
        ];
        return view('adm.user.change-password', ['breadcrumb' => $breadcrumb]);
    }
    /**
     * Update password.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update_password(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed'
        ]);

        $usuario = \Auth::user();
        $usuario->password = Hash::make($request->input('password'));
        $usuario->save();

        $request->session()->flash('message', 'Senha atualizada com sucesso!');
        return redirect()->route('adm');
    }
}
