@extends('samples')

@section('content')
<link href="{{ asset('css/mapa-calor.css') }}" rel="stylesheet">
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
                @forelse($planejamento->unidades as $unidade)
                  <tr>
                    <th scope="row">{{ $unidade->unidade . ' - ' . $unidade->nome }}</th>
                    <td>{{ $unidade->qtd_salas }}</td>
                    <td>{{ $unidade->qtd_disciplinas }}</td>
                    <td>{{ $unidade->qtd_turmas }}</td>
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
        <br>
        <div class="row">
           <div class="col-md-6">
               <input type="hidden" id="semestre" name="semestre" value="{{ $planejamento->semestre }}">
               <div class="form-group row">
                  <label for="unidade_det" class="col-sm-2 col-form-label"><strong>Unidade</strong></label>
                  <div class="col-sm-6">
                     <select id="unidade_det" name="unidade_det" class="form-control form-control-sm" required>
                        <option value="">:: Selecione ::</option>
                        @foreach($planejamento->unidades as $unidade)
                        <option value="{{ $unidade->unidade }}">{{ $unidade->unidade . ' - ' . $unidade->nome }}</option>
                        @endforeach
                     </select>
                  </div>
               </div>
           </div>
         </div>
         <div id="info-detalhes" class="row" style="display: none;">
              <div class="col-md-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="detalhes-unidade-tab" data-toggle="tab" href="#detalhes-unidade" role="tab" aria-controls="detalhes-unidade" aria-selected="true">Detalhes unidade</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="disciplinas-unidade-tab" data-toggle="tab" href="#disciplinas-unidade" role="tab" aria-controls="disciplinas-unidade" aria-selected="true">Disciplinas unidade</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="mapa-calor-tab" data-toggle="tab" href="#mapa-calor" role="tab" aria-controls="mapa-calor" aria-selected="false">Mapa de Calor Unidade</a>
                  </li>
                </ul>
                <div class="tab-content" id="tab-informacoes">
                    <div class="tab-pane fade show active" id="detalhes-unidade" role="tabpanel" aria-labelledby="detalhes-unidade-tab">
                        <div class="col-12">
                            <h5 class="titulo-unidade"></h5>
                            <p class="text-muted">Informações semanais detalhadas sobre cada sala da unidade selecionada</p>
                            <table id="tb-detalhes" class="table table-sm table-bordered table-hover">
                                <thead>
                                  <tr>
                                    <th scope="col">Sala</th>
                                    <th>Tipo de sala</th>
                                    <th>Disciplinas</th>
                                    <th>Turmas</th>
                                    <th>Aulas</th>
                                    <th>Taxa de utilização (%)</th>
                                  </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="disciplinas-unidade" role="tabpanel" aria-labelledby="disciplinas-unidade-tab">
                        <div class="col-12">
                            <h5 class="titulo-unidade"></h5>
                            <p class="text-muted">Lista de disciplinas da unidade selecionada</p>
                            <table id="tb-disciplinas" class="table table-sm table-bordered table-hover">
                                <thead>
                                  <tr>
                                    <th scope="col">Código</th>
                                    <th>Nome</th>
                                  </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="mapa-calor" role="tabpanel" aria-labelledby="mapa-calor-tab">
                      <div class="col-12">
                          <h5 class="titulo-unidade"></h5>
                          <p class="text-muted">Mapa de calor semanal da unidade selecionada</p>
                          <table id="tb-mapa-calor" class="table table-bordered table-responsive-sm table-sm text-center">
                              <thead>
                                <tr>
                                  <th scope="col">Horários</th>
                                  <th scope="col">SEG</th>
                                  <th scope="col">TER</th>
                                  <th scope="col">QUA</th>
                                  <th scope="col">QUI</th>
                                  <th scope="col">SEX</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr><th scope="row">07:00 - 07:55</th><td id="seg-700"></td><td id="ter-700"></td><td id="qua-700"></td><td id="qui-700"></td><td id="sex-700"></td></tr>
                                <tr><th scope="row">07:55 - 08:50</th><td id="seg-755"></td><td id="ter-755"></td><td id="qua-755"></td><td id="qui-755"></td><td id="sex-755"></td></tr>
                                <tr><th scope="row">08:50 - 09:45</th><td id="seg-850"></td><td id="ter-850"></td><td id="qua-850"></td><td id="qui-850"></td><td id="sex-850"></td></tr>
                                <tr><th scope="row">09:45 - 10:40</th><td id="seg-945"></td><td id="ter-945"></td><td id="qua-945"></td><td id="qui-945"></td><td id="sex-945"></td></tr>
                                <tr><th scope="row">10:40 - 11:35</th><td id="seg-1040"></td><td id="ter-1040"></td><td id="qua-1040"></td><td id="qui-1040"></td><td id="sex-1040"></td></tr>
                                <tr><th scope="row">11:35 - 12:30</th><td id="seg-1135"></td><td id="ter-1135"></td><td id="qua-1135"></td><td id="qui-1135"></td><td id="sex-1135"></td></tr>
                                <tr><th scope="row">13:00 - 13:55</th><td id="seg-1300"></td><td id="ter-1300"></td><td id="qua-1300"></td><td id="qui-1300"></td><td id="sex-1300"></td></tr>
                                <tr><th scope="row">13:55 - 14:50</th><td id="seg-1355"></td><td id="ter-1355"></td><td id="qua-1355"></td><td id="qui-1355"></td><td id="sex-1355"></td></tr>
                                <tr><th scope="row">14:50 - 15:45</th><td id="seg-1450"></td><td id="ter-1450"></td><td id="qua-1450"></td><td id="qui-1450"></td><td id="sex-1450"></td></tr>
                                <tr><th scope="row">15:45 - 16:40</th><td id="seg-1545"></td><td id="ter-1545"></td><td id="qua-1545"></td><td id="qui-1545"></td><td id="sex-1545"></td></tr>
                                <tr><th scope="row">16:40 - 17:35</th><td id="seg-1640"></td><td id="ter-1640"></td><td id="qua-1640"></td><td id="qui-1640"></td><td id="sex-1640"></td></tr>
                                <tr><th scope="row">17:35 - 18:30</th><td id="seg-1735"></td><td id="ter-1735"></td><td id="qua-1735"></td><td id="qui-1735"></td><td id="sex-1735"></td></tr>
                                <tr><th scope="row">18:30 - 19:25</th><td id="seg-1830"></td><td id="ter-1830"></td><td id="qua-1830"></td><td id="qui-1830"></td><td id="sex-1830"></td></tr>
                                <tr><th scope="row">19:25 - 20:20</th><td id="seg-1925"></td><td id="ter-1925"></td><td id="qua-1925"></td><td id="qui-1925"></td><td id="sex-1925"></td></tr>
                                <tr><th scope="row">20:20 - 21:15</th><td id="seg-2020"></td><td id="ter-2020"></td><td id="qua-2020"></td><td id="qui-2020"></td><td id="sex-2020"></td></tr>
                                <tr><th scope="row">21:15 - 22:10</th><td id="seg-2115"></td><td id="ter-2115"></td><td id="qua-2115"></td><td id="qui-2115"></td><td id="sex-2115"></td></tr>
                              </tbody>
                          </table>
                      </div>
                    </div>
                </div>
              </div>
         </div>
      </div>
    </div>
</div>
@endsection
