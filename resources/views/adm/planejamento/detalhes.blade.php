@extends('samples')

@section('content')
<div class="container">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-sm-12">
            <h1 class="card-title mb-0">Detalhes Planejamento {{ $planejamento->semestre }}</h1>
            <div class="small text-muted">Informações do Planejamento acadêmico</div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-sm-12">
            <table class="table table-responsive-sm table-bordered table-striped table-sm">
              <thead>
                <tr>
                  <th scope="col" style="width: 80px;">Unidade</th>
                  <th scope="col" style="width: 80px;">Número de salas</th>
                  <th scope="col" style="width: 80px;">Número de disciplinas</th>
                  <th scope="col" style="width: 80px;">Número de turmas</th>
                </tr>
              </thead>
              <tbody>
                @forelse($planejamento->unidades as $unidades)
                  <tr>
                    <th scope="row">{{$unidades->unidade}}</th>
                    <td>{{$unidades->qtd_salas}}</td>
                    <td>{{$unidades->qtd_disciplinas}}</td>
                    <td>{{$unidades->qtd_turmas}}</td>
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
    </div>
</div>
@endsection
