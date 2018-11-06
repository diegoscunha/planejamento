@extends('samples')

@section('content')
<div class="container">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-sm-5">
          <h1 class="card-title mb-0">Listar Usuários</h1>
          <div class="small text-muted">Lista de usuários cadastradas</div>
        </div>
        <div class="col-sm-7 d-md-block">
          <a href="{{ route('register') }}" class="btn btn-sm btn-primary float-right" role="button" alt="Adicionar Usuário" title="Adicionar Usuário">
            <i class="fa fa-plus-circle"></i>
          </a>
        </div>
      </div>
      @if(session()->has('message'))
      <br>
      <div class="row">
        <div class="col-md-12">
          <div class="alert alert-success">
            {{ session('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        </div>
      </div>
      @endif
      <br>
      <table class="table table-responsive-sm table-bordered table-striped table-sm">
        <thead>
          <tr>
            <th scope="col">Nome</th>
            <th scope="col">E-mail</th>
            <th scope="col">Ativo</th>
            <th scope="col">Ações</th>
          </tr>
        </thead>
        <tbody>
          @forelse($usuarios as $usuario)
            <tr>
              <th scope="row">{{$usuario->name}}</th>
              <td>{{$usuario->email}}</td>
              <td>
              @if($usuario->ativo)
              <span class="badge badge-success">Ativo</span>
              @else
              <span class="badge badge-danger">Inativo</span>
              @endif
              </td>
              <td>
                <a href="{{ route('editar-usuario', ['id' => $usuario->id]) }}" class="btn btn-sm btn-brand btn-info" role="button" alt="Editar Usuário" title="Editar Usuário"><i class="fa fa-pencil"></i></a>
                @if($usuario->id!=Auth::id())
                  @if($usuario->ativo)
                  <a href="{{ route('desativar-usuario', ['id' => $usuario->id]) }}" class="btn btn-sm btn-brand btn-danger desativar-usuario" role="button" title="Desativar Usuário" title="Desativar Usuário">
                    <i class="fa fa-minus-circle"></i>
                  </a>
                  @else
                  <a href="{{ route('ativar-usuario', ['id' => $usuario->id]) }}" class="btn btn-sm btn-brand btn-success ativar-usuario" role="button" title="Ativar Usuário" title="Ativar Usuário">
                    <i class="fa fa-check"></i>
                  </a>
                  @endif
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="3" class="text-center"><strong>Sem registros!</strong></td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
