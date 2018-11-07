@extends('samples')

@section('content')
<div class="container">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-sm-5">
          <h1 class="card-title mb-0">Listar Salas</h1>
          <div class="small text-muted">Lista de salas cadastradas</div>
        </div>
        <div class="col-sm-7 d-md-block">
          <a href="{{ route('adicionar-sala') }}" class="btn btn-sm btn-primary float-right" role="button" alt="Adicionar Sala" title="Adicionar Sala">
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
            <th scope="col">Unidade</th>
            <th scope="col">Disponível?</th>
            <th scope="col">Quadro grande?</th>
            <th scope="col">Sala</th>
            <th scope="col">Cadeiras</th>
            <th scope="col">Capacidade máxima</th>
            <th scope="col">Ações</th>
          </tr>
        </thead>
        <tbody>
          @forelse($salas as $sala)
            <tr data-toggle="tooltip" data-placement="top" data-html="true" title="<div class='text-left'>Unidade: {{$sala->unidade->nome}}<br>Sala: {{$sala->numero_sala}}<br>Número cadeiras: {{$sala->num_cadeiras}}<br>Capacidade máxima: {{$sala->capacidade_max}}<br>Disponível: {{$sala->disponivel ? \App\Enum\SimNao::SIM : \App\Enum\SimNao::NAO }}<br>Acessibilidade: {{\App\Enum\Acessibilidade::getConstante($sala->acessibilidade)}}<br>Ar condicionado: {{\App\Enum\ArCondicionado::getConstante($sala->ar_condicionado)}}<br>Projetor/TV: {{\App\Enum\ProjetorTV::getConstante($sala->projetor_tv)}}<br>Quadro grande: {{$sala->quadro_grande ? \App\Enum\SimNao::SIM : \App\Enum\SimNao::NAO }}<br>Equipamento de som: {{\App\Enum\EquipamentoSom::getConstante($sala->equipamento_som)}}<br>Prioridade de uso: {{\App\Enum\Prioridade::getConstante($sala->prioridade_uso)}}</div>">
              <th scope="row">{{ $sala->unidade->nome }}</th>
              <td>@if ($sala->disponivel) <span class="badge badge-success">Sim</span> @else <span class="badge badge-danger">Não</span> @endif</td>
              <td>@if ($sala->quadro_grande) <span class="badge badge-success">Sim</span> @else <span class="badge badge-danger">Não</span> @endif</td>
              <td>{{ $sala->numero_sala }}</td>
              <td>{{ $sala->num_cadeiras }}</td>
              <td>{{ $sala->capacidade_max }}</td>
              <td>
                <a href="{{ route('editar-sala', ['id' => $sala->id]) }}" class="btn btn-sm btn-brand btn-info" role="button" alt="Editar Sala" title="Editar Sala"><i class="fa fa-pencil"></i></a>
                <a href="{{ route('excluir-sala', ['id' => $sala->id]) }}" class="btn btn-sm btn-brand btn-danger excluir-sala" role="button" title="Excluir Sala" title="Excluir Sala">
                  <i class="fa fa-trash"></i>
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="12" class="text-center"><strong>Sem registros!</strong></td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
