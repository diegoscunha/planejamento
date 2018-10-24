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
      <div class="row">
        <div class="col-sm-12">
          <h1 class="card-title mb-0">Ajustar Planejamento {{ $semestre }}</h1>
          <div class="small text-muted">Realize ajustes nas alocações das turmas</div>
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
          <div id="gridbox" style="width:height:150px;"></div>
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
                    <div class="dhx_cal_prev_button">&nbsp;</div>
                    <div class="dhx_cal_next_button">&nbsp;</div>
                    <div class="dhx_cal_today_button"></div>
                    <div class="dhx_cal_date"></div>
                    <div class="dhx_cal_tab" name="day_tab" style="right:204px;"></div>
                    <div class="dhx_cal_tab" name="week_tab" style="right:140px;"></div>
                    <!--<div class="dhx_cal_tab" name="month_tab" style="right:76px;"></div> -->
                </div>
                <div class="dhx_cal_header"></div>
                <div class="dhx_cal_data"></div>
            </div>
        </div>
        <!--<div class="col-md-6">-->
          <!-- Visualizar -->
          <!--<br>-->
          <!--<iframe name="interno" style="height: 100%;width: 100%;" marginwidth="0" marginheight="0" frameborder="0" scrollbar="no" scrolling="no" src="{{ route('index') }}"></iframe>-->
        <!--</div>-->
    </div>
</div>
@endsection
