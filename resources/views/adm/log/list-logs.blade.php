@extends('samples')

@section('content')
<div class="container">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-sm-5">
          <h1 class="card-title mb-0">Listar Logs</h1>
          <div class="small text-muted">Lista de logs registrados</div>
        </div>
      </div>
      <br>
      <table class="table table-responsive-sm table-bordered table-striped table-sm">
        <thead>
          <tr>
            <th scope="col">Operação</th>
            <th scope="col">Usuário</th>
            <th scope="col">Funcionalidade</th>
            <th scope="col">Data</th>
          </tr>
        </thead>
        <tbody>
         @forelse($logs as $log)
            <tr>
              <th scope="row">{{ $log->message }}</th>
              <td>{{ $log->context['user']['name'] }}</td>
              <td>{{ $log->context['funcionalidade'] }}</td>
              <td>{{ date('d/m/Y H:i:s', strtotime($log->created_at)) }}</td>
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
