@extends('layouts.guest')

@section('content')
<script type="text/javascript">
// Exportação
function exports(type) {
		exportScheduler(type, $('#unidade').val(), $('#sala').val());
}
</script>
<div class="container" style='width:1000px; height:800px; padding:0px;'>
    <form id="form-pesquisar" action="#" class="needs-validation" novalidate>
				<div class="form-group row">
						<label for="semestre" class="col-sm-1 col-form-label">Semestre</label>
						<div class="col-sm-4">
								<select id="semestre" name="semestre" class="form-control form-control-sm filtro" required>
										<option value="">:: Selecione ::</option>
										@foreach ($semestres as $value)
												<option value="{{ $value->ano }}.{{ $value->semestre }}">{{ $value->ano }}.{{ $value->semestre }}</option>
										@endforeach
								</select>
								<div class="invalid-feedback">
        						Por favor, selecione um semestre.
      					</div>
						</div>
				</div>
        <div class="form-group row">
            <label for="unidade" class="col-sm-1 col-form-label">Unidade</label>
            <div class="col-sm-4">
                <select id="unidade" name="unidade" class="form-control form-control-sm filtro" required>
                    <option value="">:: Selecione ::</option>
                </select>
								<div class="invalid-feedback">
        						Por favor, selecione uma unidade.
      					</div>
            </div>
        </div>

        <div class="form-group row">
            <label for="sala" class="col-sm-1 col-form-label">Sala</label>
            <div class="col-sm-4">
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
    <div class='controls'>
        <input type="button" value="Export to PDF" onclick='exports("pdf")'>
        <input type="button" value="Export to PNG" onclick='exports("png")'>
        <input type="button" value="Export to Excel" onclick='exports("excel")'>
    </div>
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
@endsection
