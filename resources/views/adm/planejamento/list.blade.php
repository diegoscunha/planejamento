@extends('samples')

@section('content')
<div class="container">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-sm-5">
            <h1 class="card-title mb-0">Planejamento</h1>
            <div class="small text-muted">Planejamentos acadêmicos</div>
          </div>
          <div class="col-sm-7 d-md-block">
            <a href="{{ route('importar-planejamento') }}" class="btn btn-sm btn-primary float-right" role="button" alt="Adicionar" title="Adicionar">
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
              <th scope="col" style="width: 80px;">Semestre</th>
              <th scope="col" style="width: 80px;">Unidades</th>
              <th scope="col" style="width: 80px;">Disciplinas</th>
              <th scope="col" style="width: 120px;">Aulas não alocadas</th>
              <th scope="col" style="width: 200px;">Percercentual de alocação (%)</th>
              <th scope="col" style="width: 80px;">Status</th>
              <th scope="col" style="width: 80px;">Ações</th>
            </tr>
          </thead>
          <tbody>
            @forelse($planejamentos as $planejamento)
              <tr>
                <th scope="row">{{$planejamento->semestre}}</th>
                <td>{{$planejamento->unidades}}</td>
                <td>{{$planejamento->disciplinas}}</td>
                <td>{{$planejamento->aulas_nao_alocadas}}</td>
                <td>
                  <div class="clearfix">
                    <div class="float-left">
                      <strong>{{ $planejamento->percent }}%</strong>
                    </div>
                  </div>
                  <div class="progress progress-xs">
                    <div class="progress-bar {{ $planejamento->percent_class }}" role="progressbar" style="width: {{ $planejamento->percent }}%" aria-valuenow="{{ $planejamento->percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </td>
                @if($planejamento->status==1)
                <td><span class="badge badge-success">Liberado</span></td>
                @elseif($planejamento->status==0)
                <td><span class="badge badge-danger">Bloqueado</span></td>
                @endif
                <td>
                  <a class="btn btn-sm btn-brand btn-info" href="{{ route('detalhes-planejamento', ['semestre' => $planejamento->periodo_letivo]) }}" role="button" title="Detalhes"><i class="fa fa-eye"></i></a>
                  <a class="btn btn-sm btn-brand btn-primary" href="{{ route('ajustar-planejamento', ['semestre' => $planejamento->periodo_letivo]) }}" role="button" title="Ajustes"><i class="fa fa-calendar"></i></a>
                  <a href="{{ route('excluir-planejamento', ['periodo_letivo' => $planejamento->periodo_letivo]) }}" class="btn btn-sm btn-brand btn-danger excluir-planejamento" role="button" title="Excluir">
                    <i class="fa fa-trash"></i>
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center"><strong>Sem registros!</strong></td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
</div>
@endsection
