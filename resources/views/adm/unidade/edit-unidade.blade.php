@extends('samples')

@section('content')
<div class="container">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-sm-5">
          <h1 class="card-title mb-0">@if(empty($unidade->id)) Nova @else Editar @endisset Unidade</h1>
          <div class="small text-muted">@if(empty($unidade->id)) Novo @else Editar @endisset registro de unidade</div>
        </div>
      </div>
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
            <form class="" action="{{ route('salvar-unidade') }}" method="post">
              @csrf
              <input type="hidden" id="id" name="id" value="{{ old('id', $unidade->id) }}">
              <div class="form-group">
                <label for="codigo">Código</label>
                <input type="text" class="form-control" id="codigo" name="codigo" value="{{ old('codigo', $unidade->codigo) }}" />
              </div>
              <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', $unidade->nome) }}"/>
              </div>
              <button type="submit" class="btn btn-primary">Salvar</button>
              <a href="{{ route('listar-unidades') }}" class="btn btn-secondary">Cancelar</a>
            </form>
          </div>
      </div>
    </div>
  </div>
</div>
@endsection
