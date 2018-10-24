@extends('samples')

@section('content')
<div class="container">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-sm-5">
          <h1 class="card-title mb-0">@if(empty($disciplina->id)) Nova @else Editar @endisset Disciplina</h1>
          <div class="small text-muted">@if(empty($disciplina->id)) Novo @else Editar @endisset registro de disciplina</div>
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
      @if ($errors->any())
      <br>
      <div class="row">
        <div class="col-md-6">
          <div class="alert alert-danger">
            <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
            </ul>
          </div>
        </div>
      </div>
      @endif
      <br>
      <div class="row">
          <div class="col-md-6">
            <form class="" action="{{ route('salvar-disciplina') }}" method="post">
              @csrf
              <input type="hidden" id="id" name="id" value="{{ old('id', $disciplina->id) }}">
              <div class="form-group">
                <label for="codigo">Código</label>
                <input type="text" class="form-control" id="codigo" name="codigo" value="{{ old('codigo', $disciplina->codigo) }}" />
              </div>
              <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', $disciplina->nome) }}"/>
              </div>
              <button type="submit" class="btn btn-primary">Salvar</button>
              <a href="{{ route('listar-disciplinas') }}" class="btn btn-secondary">Cancelar</a>
            </form>
          </div>
      </div>
    </div>
  </div>
</div>
@endsection
