@extends('samples')

@section('content')
<div class="container">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-sm-5">
          <h1 class="card-title mb-0">Listar Unidades</h1>
          <div class="small text-muted">Lista de unidades cadastradas</div>
        </div>
        <div class="col-sm-7 d-md-block">
          <a href="{{ route('adicionar-unidade') }}" class="btn btn-sm btn-primary float-right" role="button" alt="Adicionar Unidade" title="Adicionar Unidade">
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
            <th scope="col">Código</th>
            <th scope="col">Nome</th>
            <th scope="col">Ações</th>
          </tr>
        </thead>
        <tbody>
          @forelse($unidades as $unidade)
            <tr>
              <th scope="row">{{$unidade->codigo}}</th>
              <td>{{$unidade->nome}}</td>
              <td>
                <a href="{{ route('editar-unidade', ['id' => $unidade->id]) }}" class="btn btn-sm btn-brand btn-info" role="button" alt="Editar Unidade" title="Editar Unidade"><i class="fa fa-pencil"></i></a>
                <a href="{{ route('excluir-unidade', ['id' => $unidade->id]) }}" class="btn btn-sm btn-brand btn-danger excluir-unidade" role="button" title="Excluir Unidade" title="Excluir Unidade">
                  <i class="fa fa-trash"></i>
                </a>
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
