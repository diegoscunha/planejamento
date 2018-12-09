<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Relatorio de Alocacao</title>
    <!-- Styles -->
    <!--link href="{{ asset('css/app.css') }}" rel="stylesheet"-->
    <style media="screen">
      /*body {
        margin: 0;
        font-family: Raleway,sans-serif;
        font-size: .9rem;
        font-weight: 400;
        line-height: 1.6;
        color: #212529;
        text-align: left;
        background-color: #f5f8fa;
      }
      .container {
        max-width: 1140px;
      }
      .container {
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
      }
      .row {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
      }
      .col-sm {
        -ms-flex-preferred-size: 0;
        flex-basis: 0;
        -webkit-box-flex: 1;
        -ms-flex-positive: 1;
        flex-grow: 1;
        max-width: 100%;
      }
      .text-center {
        text-align: center!important;
      }
      .h3, h3 {
        font-size: 1.575rem;
      }
      .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
        margin-bottom: .5rem;
        font-family: inherit;
        font-weight: 500;
        line-height: 1.2;
        color: inherit;
      }
      h1, h2, h3, h4, h5, h6 {
        margin-top: 0;
        margin-bottom: .5rem;
      }
      .h4, h4 {
        font-size: 1.35rem;
      }
      b, strong {
        font-weight: bolder;
      }
      .table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 1rem;
        background-color: transparent;
      }
      .table-bordered, .table-bordered td, .table-bordered th {
          border: 1px solid #dee2e6;
      }
      table {
          border-collapse: collapse;
      }
      .table-bordered thead td, .table-bordered thead th {
          border-bottom-width: 2px;
      }
      .table thead th {
          vertical-align: bottom;
          border-bottom: 2px solid #dee2e6;
      }
      .table-bordered, .table-bordered td, .table-bordered th {
          border: 1px solid #dee2e6;
      }
      .table-sm td, .table-sm th {
          padding: .3rem;
      }
      .table td, .table th {
          padding: .75rem;
          vertical-align: top;
          border-top: 1px solid #dee2e6;
      }
      th {
          text-align: inherit;
      }*/
    </style>
</head>
<body style="font-family: Times, 'Times New Roman', Georgia, serif;">
    <div class="container">
        <div class="row">
            <div class="col-sm text-center">
                <h3><strong>Relatório de Alocação</strong></h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sm text-center">
                <h4>Semestre: {{ $semestre }}</h4>
            </div>
        </div>
        @foreach ($dados as $value)
        <div class="row">
            <div class="col-sm">
                <h4>{{ $value[0]['nome'] }} </h4>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <table class="table table-sm table-bordered" style="font-size: 13px;">
                    <thead>
                      <tr>
                        <th>Disciplina</th>
                        <th>Sala</th>
                        <th>Turma</th>
                        <th>Dia da semana</th>
                        <th>Horario</th>
                        <th>Docente</th>
                      </tr>
                    </thead>
                   <tbody>
                     @php
                     $disciplina = "";
                     @endphp
                     @foreach ($value as $key => $dis)
                      <tr>
                        <td>
                          @if($disciplina!=$dis['codigo_disciplina'])
                          {{ $dis['codigo_disciplina'] . ' - ' . $dis['nome_disciplina']}}
                          @endif
                          @php
                          $disciplina = $dis['codigo_disciplina'];
                          @endphp
                        </td>
                        <td>{{ $dis['numero_sala'] }}</td>
                        <td>{{ $dis['turma'] }}</td>
                        <td>{{ $dis['dia_semana_ext'] }}</td>
                        <td>{{ format_hora($dis['hora_inicial']) . ' - ' . format_hora($dis['hora_final']) }}</td>
                        <td>{{ $dis['docente'] }}</td>
                      </tr>
                      @endforeach
                   </tbody>
                </table>
            </div>
        </div>
        @endforeach
    </div>
</body>
</html>
