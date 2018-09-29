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

            var events = ajax('GET', 'planejamento/consultar-disciplinas/json', {semestre:semestre,unidade:unidade,sala:sala});
            scheduler.clearAll();
            events.forEach(function(value, i, events) {
                scheduler.addEvent(value);
            });
        }
    });

    $("#semestre").change(function() {
        $('#unidade').html('');
        $('#unidade').append($('<option>', {value: '', text: ':: Selecione ::'}));
        $('#sala').html('');
        $('#sala').append($('<option>', {value: '', text: ':: Selecione ::'}));
        if ($('#semestre').val()) {
            var unidades = ajax('GET', 'planejamento/obter-unidades/json', {semestre:$('#semestre').val().replace('.','')});
            $.each(unidades, function(i, unidade) {
                $('#unidade').append($('<option>', {value: unidade.unidade, text: unidade.unidade}));
            });
        }
    });

    $("#unidade").change(function() {
        $('#sala').html('');
        $('#sala').append($('<option>', {value: '', text: ':: Selecione ::'}));
        if ($('#unidade').val()) {
            semestre = $('#semestre').val().replace('.', '');
            unidade = $('#unidade').val();
            var salas = ajax('GET', 'planejamento/obter-salas/json', {semestre:$('#semestre').val().replace('.',''), unidade: unidade});
            $.each(salas, function(i, sala) {
                $('#sala').append($('<option>', {value: sala.numero_sala, text: sala.numero_sala}));
            });
        }
    });
});

function ajax(method, url, object) {
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
            alert(msg);
        }
    });
    return events;
}

// Exportação
var header = "<h1>UFBA - {UNIDADE} Sala {SALA} </h1>",
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
