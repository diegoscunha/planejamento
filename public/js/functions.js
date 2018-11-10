function ajax(method, url, object) {
    if (method.toLowerCase()=='post') {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
    }
    var events;
    $.ajax({
        type: method,
        url: url,
        data: object,
        async: false,
        success: function(data){
            events = data;
        },
        error: function(msg){
            swal({text: msg});
        }
    });
    return events;
}

function buscar_disciplinas() {
    semestre = $('#semestre').val().replace('.', '');
    unidade = $('#unidade').val();
    sala = $('#sala').val();

    return ajax('GET', '/planejamento/consultar-disciplinas/json', {semestre:semestre,unidade:unidade,sala:sala});
}

function buscar_disciplinas_nao_alocadas() {
    semestre = $('#semestre').val().replace('.', '');
    unidade = $('#unidade').val();
    sala = $('#sala').val();

    return ajax('GET', '/planejamento/consultar-disciplinas/json', {semestre:semestre,unidade:unidade,sala:sala});
}

function refresh_calendar(events) {
    scheduler.clearAll();
    scheduler.parse(events, "json");
}

function refresh_grid(events) {
    mygrid.clearAll();
    mygrid.parse(events,"json");
    $('a.alocar').on('click', function() {
        $('#exampleModalCenter').modal('show');
    });
}

function montar_grid(semestre, params) {
    var disciplinas = ajax('GET', '/planejamento/consultar-disciplinas-grid/json', {semestre: semestre, disciplinas: params});

    $('#tb-disciplinas > tbody').html('');

    $.each(disciplinas, function(i, value) {
        linha = "<tr>";
        linha += "<th scope='row'>" + value.periodo_letivo + "</th>";
        linha += "<td>" + value.codigo_disciplina + "</td>";
        linha += "<td>" + value.disciplina + "</td>";
        linha += "<td>" + value.unidade + "</td>";
        linha += "<td>" + value.numero_sala + "</td>";
        linha += "<td>" + value.turma + "</td>";
        linha += "<td>" + value.dia + "</td>";
        linha += "<td>" + value.horario + "</td>";
        linha += "<td>" + value.docente + "</td>";
        $('#tb-disciplinas > tbody').append(linha);
    });
}

// Exportação
var header = "<p><h1>Sistema de Planejamento Acadêmico</h1></p><h3>Universidade Federal da Bahia</h3><h3>{UNIDADE}</h3><h3>{SALA}</h3>",
    footer = "Sistema de Planejamento Acadêmico UFBA";

function exportScheduler(type, unidade, sala) {
    header = header.replace('{UNIDADE}', unidade);
    header = header.replace('{SALA}', sala);

    if (type == "pdf") {
        scheduler.exportToPDF({
            format:'A4',
            orientation:'landscape',
            header: header,
            footer:footer,
        });
    } else if (type == "png") {
        scheduler.exportToPNG({
            format:'A4',
            orientation:'landscape',
            header: header,
            footer: footer,
        });
    } else {
        scheduler.exportToExcel({
            name:"My document.xlsx",
            columns:[
              { id:"start_date", header:"Start date", width:80 },
              { id:"end_date", header:"End date", width:80 },
              { id:"text", header:"Title", width:80 },
            ],
        });
    }
}

function init_consultar(events) {
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

    scheduler.config.touch = "force";

    scheduler.attachEvent("onLightbox", function (id){
        $('.dhx_cal_ltext').attr('style', '');
    });

    scheduler.config.day_date = "%D";

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

function init_ajustar(events) {
    var dias = [
      { key: 1, label: 'Domingo' },
      { key: 2, label: 'Segunda-Feira' },
      { key: 3, label: 'Terça-Feira' },
      { key: 4, label: 'Quarta-Feira' },
      { key: 5, label: 'Quinta-Feira' },
      { key: 6, label: 'Sexta-Feira' },
      { key: 7, label: 'Sábado' }
    ];

    scheduler.config.lightbox.sections=[
        { name: "Semestre", map_to:"periodo_letivo", type:"textarea" },
        { name: "Unidade", map_to: "unidade", type: "textarea" },
        { name: "Sala", map_to: "numero_sala", type: "textarea" },
        { name: "Tipo sala", map_to: "tipo_sala", type: "textarea" },
        { name: "Disciplina", map_to:"text", type:"textarea" },
        { name: "Docente", map_to: "docente", type: "textarea" },
        { name: "Dia", map_to:"dia_semana", type:"select", options: dias  },
        { name: "time", type:"time", map_to:"auto", time_format: [ "%d", "%m", "%Y", "%H:%i"] }
    ];

    scheduler.config.touch = "force";

    scheduler.attachEvent("onLightbox", function (id){
        $('.dhx_cal_ltext').attr('style', '');
    });

    scheduler.config.day_date = "%D";

    scheduler.config.readonly_form = false;

    scheduler.config.buttons_left=[];
    scheduler.config.buttons_right=["dhx_cancel_btn", "dhx_save_btn"];

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

    var dp = new dataProcessor("/api/planejamento");
    dp.init(scheduler);

    var csrf = $('meta[name="csrf-token"]').attr('content');
    dp.setTransactionMode({
            mode:"JSON",
            headers: {
              "X-CSRF-TOKEN": csrf
            }
    }, true);

    dp.attachEvent("onAfterUpdate", function(id, action, tid, response){
      swal({
        title: "Você tem certeza?",
        text: "O registo será salvo com os dados informados!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      }).then((resposta) => {
        if (resposta) {
            $('body').loading({
                message: 'Carregando...'
            });
            var events = buscar_disciplinas();
            refresh_calendar(events);
            swal("Registro salvo com sucesso!", {
              icon: "success",
            });
            $('body').loading('stop');
          }
      });
    });
}

function init_grid_ajuste() {
    mygrid = new dhtmlXGridObject('gridbox');

    //the path to images required by grid
    mygrid.setImagePath("./codebase/imgs/");
    mygrid.setHeader("Id,Disciplina,Dia,DiaNum,HoraInicial,HoraFinal,Semestre,Unidade,Docente,Horário,Ação");//the headers of columns
    mygrid.setInitWidths("150,150,155,120");          //the widths of columns
    mygrid.setColAlign("left,left,left,left,left,left,left,left,left,left,left");       //the alignment of columns
    mygrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro");                //the types of columns
    mygrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str");          //the sorting types
    mygrid.setColumnIds("id,codigo_disciplina,dia_semana_ext,dia_semana,hora_inicial,hora_final,periodo_letivo,unidade,docente,horario,acao");
    mygrid.setColumnHidden(0,true);
    mygrid.setColumnHidden(3,true);
    mygrid.setColumnHidden(4,true);
    mygrid.setColumnHidden(5,true);
    mygrid.setColumnHidden(6,true);
    mygrid.setColumnHidden(7,true);
    mygrid.setColumnHidden(8,true);
    mygrid.init();      //finishes initialization and renders the grid on the page

    mygrid.attachEvent("onRowSelect", function(id,ind){
        limpar_modal();
        var disc = mygrid.getRowData(mygrid.getSelectedRowId());
        console.log(disc);
        $('#modal_id').val(disc.id);
        $('#modal_title').html(disc.horario + ' ' + disc.codigo_disciplina);
        $('#modal_periodo_letivo').html(disc.periodo_letivo);
        $('#modal_unidade').val(disc.unidade);
        $('#modal_docente').html(disc.docente);

        $('#modal_dia_semana').val(disc.dia_semana);
        $('#modal_hora_inicial').val(disc.hora_inicial);
        $('#modal_hora_final').val(disc.hora_final);
        $('#disciplina').html('Disciplina: ' + disc.codigo_disciplina + '<br>Dia: ' + disc.dia_semana);
    });
}

function limpar_modal() {
    $('#modal_id').val('');
    $('#modal_title').html('');
    $('#modal_periodo_letivo').html('');
    $('#modal_unidade').val('');
    $('#modal_docente').html('');
    $('#modal_dia_semana').val('');
    $('#modal_hora_inicial').val('');
    $('#modal_hora_final').val('');
}
