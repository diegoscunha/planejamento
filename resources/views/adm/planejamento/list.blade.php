@extends('layouts.adm')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <a class="btn btn-primary" href="{{ route('importar-planejamento') }}" role="button">Adicionar Planejamento</a>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-12">
                        <table class="table table-striped">
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
                                  <a class="badge badge-info" href="#" role="button">Detalhes</a>
                                  <a class="badge badge-warning" href="#" role="button">Visualizar registros</a>
                                  <a class="badge badge-primary" href="{{ route('ajustar-planejamento', ['semestre' => $planejamento->periodo_letivo]) }}" role="button">Ajustes</a>
                                  @if($planejamento->status==0)
                                  <a class="badge badge-danger" href="#" role="button">Excluir</a>
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
            </div>
        </div>
    </div>
</div>
@endsection
