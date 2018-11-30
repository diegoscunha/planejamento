@extends('samples')

@section('content')
<script type="text/javascript">
    // Exportação
    function exports(type) {
        exportScheduler(type, $('#unidade').val(), $('#sala').val());
    }
</script>
<div class="container">
   <div class="card">
      <div class="card-body">
         <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
               <a class="nav-link active" id="ajustar-tab" data-toggle="tab" href="#ajustar" role="tab" aria-controls="ajustar" aria-selected="true">Ajuste de Planejamento</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" id="ociosas-tab" data-toggle="tab" href="#ociosas" role="tab" aria-controls="ociosas" aria-selected="false">Consultar horários ociosos</a>
            </li>
         </ul>
         <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="ajustar" role="tabpanel" aria-labelledby="ajustar-tab">
               <div class="row">
                  <div class="col-6">
                     <h1 class="card-title mb-0">Ajustar Planejamento {{ $semestre }}</h1>
                     <div class="small text-muted">Realize ajustes nas alocações das turmas</div>
                  </div>
                  <div class="col">
                     <label for="liberado">Liberado para consulta?</label>
                     <br>
                     <label class="switch switch-text switch-success">
                     <input type="checkbox" class="switch-input" id="liberado" name="liberado" {{ $liberado ? 'checked' : '' }}>
                     <span class="switch-label" data-on="Sim" data-off="Não"></span>
                     <span class="switch-handle"></span>
                     </label>
                  </div>
               </div>
               <br>
               <div class="row">
                  <div class="col-md-6">
                     <form id="form-pesquisar" action="#" class="needs-validation" novalidate>
                        <input type="hidden" id="semestre" name="semestre" value="{{ $semestre }}">
                        <div class="form-group row">
                           <label for="unidade" class="col-sm-3 col-form-label">Unidade</label>
                           <div class="col-sm-6">
                              <select id="unidade" name="unidade" class="form-control form-control-sm filtro" required>
                                 <option value="">:: Selecione ::</option>
                                 @foreach($unidades as $unidade)
                                 <option value="{{ $unidade->unidade }}">{{ $unidade->unidade . ' - ' . $unidade->nome }}</option>
                                 @endforeach
                              </select>
                              <div class="invalid-feedback">
                                 Por favor, selecione uma unidade.
                              </div>
                           </div>
                        </div>
                        <div class="form-group row">
                           <label for="sala" class="col-sm-3 col-form-label">Sala</label>
                           <div class="col-sm-6">
                              <select id="sala" name="sala" class="form-control form-control-sm filtro" required>
                                 <option value="">:: Selecione ::</option>
                              </select>
                              <div class="invalid-feedback">
                                 Por favor, selecione uma sala.
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <button id="pesquisar" class="btn btn-primary">Pesquisar</button>
                        </div>
                     </form>
                  </div>
                  <div class="col-md-6">
                     <h5>Disciplinas Não Alocadas</h5>
                     <div id="gridbox" style="height:150px;"></div>
                     <!-- Modal -->
                     <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                           <div class="modal-content">
                              <div class="modal-header">
                                 <h5 class="modal-title" id="modal_title"></h5>
                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                 <span aria-hidden="true">&times;</span>
                                 </button>
                              </div>
                              <div class="modal-body">
                                 <form id="form-modal" class="needs-validation" action="#" novalidate>
                                    <input type="hidden" id="modal_id" name="modal_id" value="" >
                                    <div class="row">
                                       <div class="col-md-6 col-form-label">
                                          <label>Semestre: <br> <strong><span id="modal_periodo_letivo"></span></strong></label>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-md-6 col-form-label">
                                          <label for="modal_unidade">Unidade:</label>
                                          <select id="modal_unidade" name="modal_unidade" class="form-control form-control-sm formmodal" required>
                                             <option value="">:: Selecione ::</option>
                                             @foreach($unidades as $unidade)
                                             <option value="{{ $unidade->unidade }}">{{ $unidade->unidade . ' - ' . $unidade->nome }}</option>
                                             @endforeach
                                          </select>
                                          <div class="invalid-feedback">
                                             Por favor, selecione uma unidade.
                                          </div>
                                       </div>
                                       <div class="col-md-6 col-form-label">
                                          <label for="modal_sala">Sala:</label>
                                          <select id="modal_sala" name="modal_sala" class="form-control form-control-sm formmodal" required>
                                             <option value="">:: Selecione ::</option>
                                          </select>
                                          <div class="invalid-feedback">
                                             Por favor, selecione uma sala.
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-md-12 col-form-label">
                                          <label>Docente: <br><strong><span id="modal_docente"></span></strong></label>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-md-6 col-form-label">
                                          <label>Dia da Semana:</label>
                                          <select id="modal_dia_semana" name="modal_dia_semana" class="form-control form-control-sm formmodal" required>
                                             <option value="">:: Selecione ::</option>
                                             @foreach($dias as $key => $dia)
                                             <option value="{{ $key }}">{{ $dia }}</option>
                                             @endforeach
                                          </select>
                                          <div class="invalid-feedback">
                                             Por favor, selecione um dia da semana.
                                          </div>
                                       </div>
                                       <div class="col-md-6 col-form-label">
                                          <label>Horário: </label>
                                          <select id="modal_hora_inicial" name="modal_hora_inicial" class="form-control form-control-sm formmodal" required>
                                             <option value="">:: Selecione ::</option>
                                             @foreach($horas as $key => $hora)
                                             <option value="{{ $key }}">{{ $hora }}</option>
                                             @endforeach
                                          </select>
                                          <div class="invalid-feedback">
                                             Por favor, selecione um horário correto.
                                          </div>
                                          <select id="modal_hora_final" name="modal_hora_final" class="form-control form-control-sm formmodal" required>
                                             <option value="">:: Selecione ::</option>
                                             @foreach($horas as $key => $hora)
                                             <option value="{{ $key }}">{{ $hora }}</option>
                                             @endforeach
                                          </select>
                                          <div class="invalid-feedback">
                                             Por favor, selecione um horário correto.
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col">
                                          <div id="alert_choque" style="display: none;" class="alert alert-danger" role="alert">
                                          </div>
                                       </div>
                                    </div>
                                 </form>
                              </div>
                              <div class="modal-footer">
                                 <button type="button" class="btn btn-primary" id="alocar">Alocar</button>
                                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12" style='width:1000px; height:800px; padding:0px;'>
                     <div id="scheduler_here" class="dhx_cal_container" style='width:100%; height:100%;'>
                        <div class="dhx_cal_navline">
                           <!--<div class="dhx_cal_prev_button">&nbsp;</div>-->
                           <!--<div class="dhx_cal_next_button">&nbsp;</div>-->
                           <!--<div class="dhx_cal_today_button"></div>-->
                           <div class="dhx_cal_date"></div>
                           <!--<div class="dhx_cal_tab" name="day_tab" style="right:204px;"></div>-->
                           <!--<div class="dhx_cal_tab" name="week_tab" style="right:140px;"></div>-->
                           <!--<div class="dhx_cal_tab" name="month_tab" style="right:76px;"></div> -->
                        </div>
                        <div class="dhx_cal_header"></div>
                        <div class="dhx_cal_data"></div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="tab-pane fade" id="ociosas" role="tabpanel" aria-labelledby="ociosas-tab">
               <div class="row">
                   <div class="col-md-6">
                      <form id="horarios-ociosos" action="#" class="needs-validation" novalidate>
                        <input type="hidden" id="semestre_o" name="semestre_o" value="{{ $semestre }}">
                         <div class="form-group row">
                            <label for="unidade_o" class="col-sm-3 col-form-label">Unidade</label>
                            <div class="col-sm-6">
                               <select id="unidade_o" name="unidade_o" class="form-control form-control-sm filtro_o" required>
                                  <option value="">:: Selecione ::</option>
                                  @foreach($unidades as $unidade)
                                  <option value="{{ $unidade->unidade }}">{{ $unidade->unidade . ' - ' . $unidade->nome }}</option>
                                  @endforeach
                               </select>
                               <div class="invalid-feedback">
                                  Por favor, selecione uma unidade.
                               </div>
                            </div>
                         </div>
                         <div class="form-group row">
                            <label for="sala_o" class="col-sm-3 col-form-label">Sala</label>
                            <div class="col-sm-6">
                               <select id="sala_o" name="sala_o" class="form-control form-control-sm filtro_o" required>
                                  <option value="">:: Selecione ::</option>
                               </select>
                               <div class="invalid-feedback">
                                  Por favor, selecione uma sala.
                               </div>
                            </div>
                         </div>
                         <div class="form-group">
                            <button id="consultar_o" class="btn btn-primary">Consultar</button>
                         </div>
                      </form>
                   </div>
               </div>
               <div id="result-ociosos" class="row" style="display: none;">
                  <div class="col-12">
                      <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" id="segunda-feira-tab" data-toggle="tab" href="#segunda-feira" role="tab" aria-controls="segunda-feira" aria-selected="true">Segunda-Feira</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="terca-feira-tab" data-toggle="tab" href="#terca-feira" role="tab" aria-controls="terca-feira" aria-selected="false">Terça-Feira</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="quarta-feira-tab" data-toggle="tab" href="#quarta-feira" role="tab" aria-controls="quarta-feira" aria-selected="false">Quarta-Feira</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="quinta-feira-tab" data-toggle="tab" href="#quinta-feira" role="tab" aria-controls="quinta-feira" aria-selected="false">Quinta-Feira</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="sexta-feira-tab" data-toggle="tab" href="#sexta-feira" role="tab" aria-controls="sexta-feira" aria-selected="false">Sexta-Feira</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="sabado-tab" data-toggle="tab" href="#sabado" role="tab" aria-controls="sabado" aria-selected="false">Sábado</a>
                        </li>
                      </ul>
                      <div class="tab-content" id="myTabContent">
                          <div class="tab-pane fade show active" id="segunda-feira" role="tabpanel" aria-labelledby="segunda-feira-tab">
                              <div class="col-2">
                                  <table id="tb-2" class="table table-sm table-bordered">
                                      <thead>
                                        <tr>
                                          <th scope="col">Horários livres</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                      </tbody>
                                  </table>
                              </div>
                          </div>
                          <div class="tab-pane fade" id="terca-feira" role="tabpanel" aria-labelledby="terca-feira-tab">
                            <div class="col-2">
                                <table id="tb-3" class="table table-sm table-bordered">
                                    <thead>
                                      <tr>
                                        <th scope="col">Horários livres</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                          </div>
                          <div class="tab-pane fade" id="quarta-feira" role="tabpanel" aria-labelledby="quarta-feira-tab">
                            <div class="col-2">
                                <table id="tb-4" class="table table-sm table-bordered">
                                    <thead>
                                      <tr>
                                        <th scope="col">Horários livres</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                          </div>
                          <div class="tab-pane fade" id="quinta-feira" role="tabpanel" aria-labelledby="quinta-feira-tab">
                            <div class="col-2">
                                <table id="tb-5" class="table table-sm table-bordered">
                                    <thead>
                                      <tr>
                                        <th scope="col">Horários livres</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                          </div>
                          <div class="tab-pane fade" id="sexta-feira" role="tabpanel" aria-labelledby="sexta-feira-tab">
                            <div class="col-2">
                                <table id="tb-6" class="table table-sm table-bordered">
                                    <thead>
                                      <tr>
                                        <th scope="col">Horários livres</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                          </div>
                          <div class="tab-pane fade" id="sabado" role="tabpanel" aria-labelledby="sabado-tab">
                            <div class="col-2">
                                <table id="tb-7" class="table table-sm table-bordered">
                                    <thead>
                                      <tr>
                                        <th scope="col">Horários livres</th>
                                      </tr>
                                    </thead>
                                    <tbody>
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
   </div>
</div>
@endsection
