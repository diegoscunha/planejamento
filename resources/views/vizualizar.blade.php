@extends('layouts.scheduler')

@section('content')
	<script type="text/javascript">
		function init(events) {
      /*scheduler.form_blocks["my_editor"]={
          render:function(sns){
              return "<div class='dhx_cal_ltext' style='height:60px;'>Text&nbsp;<input type='text'><br/>Details&nbsp;<input type='text'></div>";
          },
          set_value:function(node,value,ev){
              node.childNodes[1].value=value||"";
              node.childNodes[4].value=ev.details||"";
          },
          get_value:function(node,ev){
              ev.location = node.childNodes[4].value;
              return node.childNodes[1].value;
          },
          focus:function(node){
              var a=node.childNodes[1]; a.select(); a.focus();
          }
      }*/

      /*scheduler.config.lightbox.sections=[
          { name:"Disciplina", map_to:"text", type:"textarea" , focus:true},
          { name: "Unidades", type: "select", options:[
            { key: "BIO", label: 'PAF 1' },
            { key: 2, label: 'PAF 2' },
            { key: 3, label: 'PAF 3' }
          ], map_to: "unidade"},
          { name: "Salas", type: "select", options:[
            { key: 1, label: '200' },
            { key: 2, label: '201' },
            { key: 3, label: '202' }
          ]},
          { name:"template", height: "70px", type:"template", map_to:"my_template"},
          { name:"time", type:"time", map_to:"auto"}
      ];*/
      //scheduler.locale.labels.section_template = 'Detalhes';// sets the name of the section
      /*scheduler.attachEvent("onBeforeLightbox", function(id, e) {
          var ev = scheduler.getEvent(id);
          ev.my_template = "Docente a contratar";
          return true;
      });*/


			scheduler.config.lightbox.sections=[
					{ name: "Semestre", map_to:"periodo_letivo", type:"textarea" },
					{ name: "Unidade", map_to: "unidade", type: "textarea" },
					{ name: "Sala", map_to: "numero_sala", type: "textarea" },
					{ name: "Tipo sala", map_to: "tipo_sala", type: "textarea" },
					{ name: "Disciplina", map_to:"text", type:"textarea" },
					{ name: "Docente", map_to: "docente", type: "textarea" },
					{ name: "Dia da semana", map_to:"dia_semana_ext", type:"textarea" },
					{ name: "time", type:"time", map_to:"auto", time_format: [ "%d", "%m", "%Y", "%H:%i"] }
			];

			scheduler.attachEvent("onLightbox", function (id){
			    $('.dhx_cal_ltext').attr('style', '');
			});

			//scheduler.config.day_date = "%D";

			//scheduler.config.readonly = true;
			scheduler.config.readonly_form = true;

			scheduler.config.buttons_left=[];
			scheduler.config.buttons_right=["dhx_cancel_btn"];

			scheduler.config.start_on_monday=false;

			scheduler.config.api_date="%Y-%m-%d %H:%i";
			scheduler.config.xml_date="%Y-%m-%d %H:%i";
			//  para abrir a caixa de luz após clicar duas vezes em um evento
			scheduler.config.details_on_dblclick = true;
			// para usar o formulário estendido ao criar novos eventos arrastando ou clicando duas vezes
			scheduler.config.details_on_create = false;
			// permite a possibilidade de criar eventos por duplo clique
			scheduler.config.dblclick_create = false;
			// permite a possibilidade de redimensionar eventos arrastando e soltando
			scheduler.config.drag_resize = false;
			// não permitir a possibilidade de criar novos eventos arrastando e soltando
			scheduler.config.drag_create = false;
			// não permitir a funcionalidade de arrastar e soltar evento
      scheduler.config.drag_move = false;
      // remover icones de edição do evento ['icon_custom', 'icon_save', 'icon_cancel']
      //scheduler.config.icons_edit = [];
      // remover icones de seleção ao lado do evento ['icon_details', 'icon_edit', 'icon_delete']
      //scheduler.config.icons_select = [];
			scheduler.attachEvent("onClick",function(){return false;})

			//scheduler.config.hour_date="%h:%i %A";
			//scheduler.xy.scale_width = 70;
			//scheduler.config.hour_size_px=25;

      scheduler.config.max_month_events = 3;
      scheduler.config.active_link_view = "day";

      scheduler.config.show_loading = true;
      scheduler.config.first_hour = 7;
			scheduler.config.last_hour = 23;
			scheduler.init('scheduler_here', new Date(), 'week');
      scheduler.parse(events, "json"); // takes the name and format of the data source
		}

    // Exportação
    function exportScheduler(type){
			var header = "<h1>UFBA - " + $('#unidade').val() + " SALA " + $('#sala').val() + "</h1>",
					footer = "Sistema de Planejamento Acadêmico UFBA";
  		if (type == "pdf")
  			scheduler.exportToPDF({
  				format:'A4',
  				orientation:'landscape',
          header: header,
          footer:footer,
  			});
  		else if (type == "png")
  			scheduler.exportToPNG({
          format:'A4',
  				orientation:'landscape',
          header: header,
          footer: footer,
  			});
      else
      scheduler.exportToExcel({
        name:"My document.xlsx",
        columns:[
          { id:"start_date", header:"Start date", width:80 },
          { id:"end_date", header:"End date", width:80 },
          { id:"text", header:"Title", width:80 },
        ],
      });
  	}

		$(document).ready(function() {
				$('#pesquisar').click(function(evt) {
						evt.preventDefault();
						var inputs = $('.filtro'),
								isValid = true;

						$(".filtro").removeClass("is-invalid");
			      for(var i=0; i<inputs.length; i++){
			          if (!inputs[i].validity.valid){
			              isValid = false;
			              $(inputs[i]).addClass("is-invalid");
								}
			      }

			      if (isValid) {
								semestre = $('#semestre').val().replace('.', '');
								unidade = $('#unidade').val();
								sala = $('#sala').val();
								$.ajax({
										type: 'GET',
										url: 'consultar-disciplinas',
										data: 'semestre=' + semestre + '&unidade=' + unidade + '&sala=' + sala,
										success: function(data){
												scheduler.clearAll();
												console.log(data);
												data.forEach(function(value, i, data) {
														scheduler.addEvent(value);
												});
										},
										error: function(msg){
												alert(msg);
										}
								});
			      } else {
								alert('invalido');
						}
				});

				$("#semestre").change(function() {
						$('#unidade').html('');
						$('#unidade').append($('<option>', {value: '', text: ':: Selecione ::'}));
						$('#sala').html('');
						$('#sala').append($('<option>', {value: '', text: ':: Selecione ::'}));
						if ($('#semestre').val()) {
								$.ajax({
										type: 'GET',
										url: 'obter-unidades',
										data: 'semestre=' + $('#semestre').val().replace('.',''),
										success: function(data){
												$.each(data, function(i, unidade) {
														$('#unidade').append($('<option>', {value: unidade.unidade, text: unidade.unidade}));
												});
										},
										error: function(msg){
												alert(msg);
										}
								});
						}
				});
				$("#unidade").change(function() {
						$('#sala').html('');
						$('#sala').append($('<option>', {value: '', text: ':: Selecione ::'}));
						if ($('#unidade').val()) {
								semestre = $('#semestre').val().replace('.', '');
								unidade = $('#unidade').val()
								$.ajax({
										type: 'GET',
										url: 'obter-salas',
										data: 'semestre=' + semestre + '&unidade=' + unidade,
										success: function(data){
												$.each(data, function(i, sala) {
														$('#sala').append($('<option>', {value: sala.numero_sala, text: sala.numero_sala}));
												});
										},
										error: function(msg){
												alert(msg);
										}
								});
						}
		  	});
		});
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
            <input type="button" value="Export to PDF" onclick='exportScheduler("pdf")'>
            <input type="button" value="Export to PNG" onclick='exportScheduler("png")'>
            <input type="button" value="Export to Excel" onclick='exportScheduler("excel")'>
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
