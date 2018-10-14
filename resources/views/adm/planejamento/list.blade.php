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
          <div class="col-sm-7 d-none d-md-block">
            <a href="{{ route('importar-planejamento') }}" class="btn btn-sm btn-primary float-right" role="button" alt="Adicionar" title="Adicionar">
              <i class="fa fa-plus-circle"></i>
            </a>
          </div>
        </div>
        <br>
        <table class="table table-responsive-sm table-bordered table-striped table-sm">
          <thead>
            <tr>
              <th scope="col">Semestre</th>
              <th scope="col">N° unidades</th>
              <th scope="col">N° disciplinas</th>
              <th scope="col">N° disciplinas não alocadas</th>
              <th scope="col">Status</th>
              <th scope="col">Ações</th>
            </tr>
          </thead>
          <tbody>
            @forelse($planejamentos as $planejamento)
              <tr>
                <th scope="row">{{$planejamento->semestre}}</th>
                <td>{{$planejamento->unidades}}</td>
                <td>{{$planejamento->disciplinas}}</td>
                <td>{{$planejamento->disciplinas_nao_alocadas}}</td>
                @if($planejamento->status==1)
                <td><span class="badge badge-danger">Fechado</span></td>
                @elseif($planejamento->status==0)
                <td><span class="badge badge-success">Aberto</span></td>
                @endif
                <td>
                  <a class="btn btn-sm btn-brand btn-info" href="#" role="button" title="Detalhes"><i class="fa fa-eye"></i></a>
                  @if($planejamento->status==0)
                  <a class="btn btn-sm btn-brand btn-primary" href="{{ route('ajustar-planejamento', ['semestre' => $planejamento->periodo_letivo]) }}" role="button" title="Ajustes"><i class="fa fa-calendar"></i></a>
                  <a href="#" class="btn btn-sm btn-brand btn-danger" role="button" title="Excluir">
                    <i class="fa fa-trash"></i>
                  </a>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center"><strong>Sem registros!</strong></td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
</div>
@endsection
