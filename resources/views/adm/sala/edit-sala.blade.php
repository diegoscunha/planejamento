@extends('samples')

@section('content')
<div class="container">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-sm-5">
          <h1 class="card-title mb-0">@if(empty($sala->id)) Nova @else Editar @endisset Sala</h1>
          <div class="small text-muted">@if(empty($sala->id)) Novo @else Editar @endisset registro de sala</div>
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
        <div class="col">
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
      <form class="" action="{{ route('salvar-sala') }}" method="post">
        @csrf
        <input type="hidden" id="id" name="id" value="{{ old('id', $sala->id) }}">
        <div class="row">
          <div class="col-2">
            <div class="form-group">
              <label for="disponivel">Sala disponível?</label>
              <br>
              <label class="switch switch-text switch-success">
                <input type="checkbox" class="switch-input" id="disponivel" name="disponivel" {{ old('disponivel', $sala->disponivel) ? 'checked' : '' }}>
                <span class="switch-label" data-on="Sim" data-off="Não"></span>
                <span class="switch-handle"></span>
              </label>
            </div>
          </div>
          <div class="col">
            <div class="form-group">
              <label for="quadro_grande">Quadro grande?</label>
              <br>
              <label class="switch switch-text switch-primary">
                <input type="checkbox" class="switch-input" id="quadro_grande" name="quadro_grande" {{ old('quadro_grande', $sala->quadro_grande) ? 'checked' : '' }}>
                <span class="switch-label" data-on="Sim" data-off="Não"></span>
                <span class="switch-handle"></span>
              </label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col">
              <div class="form-group">
                <label for="unidade_id">Unidade</label>
                <select class="form-control" name="unidade_id" id="unidade_id">
                  <option value="">:: Selecione ::</option>
                  @foreach($unidades as $unidade)
                  <option value="{{ $unidade->id }}" {{ (old('unidade_id', $sala->unidade_id)==$unidade->id ? 'selected' : '') }}>{{ $unidade->codigo . ' - ' . $unidade->nome }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="numero_sala">Sala</label>
                <input type="text" class="form-control" id="numero_sala" name="numero_sala" value="{{ old('numero_sala', $sala->numero_sala) }}"/>
              </div>
              <div class="form-group">
                <label for="num_cadeiras">Quantidade cadeiras</label>
                <input type="text" class="form-control" id="num_cadeiras" name="num_cadeiras" value="{{ old('num_cadeiras', $sala->num_cadeiras) }}"/>
              </div>
              <div class="form-group">
                <label for="capacidade_max">Capacidade máxima sala</label>
                <input type="text" class="form-control" id="capacidade_max" name="capacidade_max" value="{{ old('capacidade_max', $sala->capacidade_max) }}"/>
              </div>
              <div class="form-group">
                <label for="acessibilidade">Acessibilidade</label>
                <select class="form-control" name="acessibilidade" id="acessibilidade">
                  <option value="">:: Selecione ::</option>
                  @foreach($acessibilidade as $key => $value)
                  <option value="{{ $value }}" {{ (old('acessibilidade', $sala->acessibilidade)==$value ? 'selected' : '') }}>{{ $key }}</option>
                  @endforeach
                </select>
              </div>
          </div>
          <div class="col">
            <div class="form-group">
              <label for="ar_condicionado">Ar condicionado</label>
              <select class="form-control" name="ar_condicionado" id="ar_condicionado">
                <option value="">:: Selecione ::</option>
                @foreach($ar as $key => $value)
                <option value="{{ $value }}" {{ (old('ar_condicionado', $sala->ar_condicionado)==$value ? 'selected' : '') }}>{{ $key }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="projetor_tv">Projetor/TV</label>
              <select class="form-control" name="projetor_tv" id="projetor_tv">
                <option value="">:: Selecione ::</option>
                @foreach($projetorTv as $key => $value)
                <option value="{{ $value }}" {{ (old('projetor_tv', $sala->projetor_tv)==$value ? 'selected' : '') }}>{{ $key }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="equipamento_som">Equipamento som</label>
              <select class="form-control" name="equipamento_som" id="equipamento_som">
                <option value="">:: Selecione ::</option>
                @foreach($equipamentoSom as $key => $value)
                <option value="{{ $value }}" {{ (old('equipamento_som', $sala->equipamento_som)==$value ? 'selected' : '') }}>{{ $key }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="prioridade_uso">Prioridade de uso</label>
              <select class="form-control" name="prioridade_uso" id="prioridade_uso">
                <option value="">:: Selecione ::</option>
                @foreach($prioridade as $key => $value)
                <option value="{{ $value }}" {{ (old('prioridade_uso', $sala->prioridade_uso)==$value ? 'selected' : '') }}>{{ $key }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('listar-salas') }}" class="btn btn-secondary">Cancelar</a>
      </form>
    </div>
  </div>
</div>
@endsection
