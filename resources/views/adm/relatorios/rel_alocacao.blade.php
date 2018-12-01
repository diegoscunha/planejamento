<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Relatorio de Alocacao</title>
    <!-- Styles -->
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
</head>
<body style="font-family: Times, 'Times New Roman', Georgia, serif;">
    <div class="container">
        <div class="row">
            <div class="col-sm text-center">
                <h3><strong>Relatorio de Alocacao</strong></h3>
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
