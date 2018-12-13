$(document).ready(function() {

    $('#op_disciplina, #op_sala').change(function (evt) {
        if($(this).attr('id')=='op_disciplina') {
            $('#filtro_sala').hide();
            $('#filtro_disciplina').show();
        } else if ($(this).attr('id')=='op_sala') {
            $('#filtro_disciplina').hide();
            $('#filtro_sala').show();
        }

    });

    $('#basic').magicsearch({
        //dataSource: dataSource,
        dataSource: '/api/disciplinas',
        type: 'ajax',
        // ajax options
        ajaxOptions: {
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        },
        fields: ['codigo', 'descricao'],
        id: 'codigo',
        format: '%codigo% - %descricao%',
        // show selected options
        showSelected: false,
        multiple: true,
        multiField: 'codigo',
        multiStyle: {
            space: 5,
            width: 80
        },
        // show dropdown button
        dropdownBtn: true,
        // max number of results
        maxShow: 5,
        // text when no results
        noResult: 'Disciplina não encontrada!',
    });
    $('#filtro_disciplina').css('visibility', 'visible');
    $('#filtro_disciplina').hide();

    $('#pesquisar').click(function(evt) {
        evt.preventDefault();
        var inputsFiltro = $('.filtro'),
            inputsFiltroSala = $('.filtro-sala'),
            inputsFiltroDisciplina = $('.filtro-disciplina'),
            isValid = true;

        $(".filtro, .filtro-sala, .filtro-disciplina").removeClass("is-invalid");
        $('#rdb-feadback').css('display', 'none');
        $('#ftr-disciplina').css('display', 'none');
        for(var i=0; i<inputsFiltro.length; i++){
            if (!inputsFiltro[i].validity.valid){
                isValid = false;
                $(inputsFiltro[i]).addClass("is-invalid");
                if($(inputsFiltro[i]).attr('type')=='radio')
                    $('#rdb-feadback').css('display', 'block');
            }
        }

        if(!isValid)
            return;

        var tipoFiltro = $('input[type=radio][name=op_filtro]:checked').val();

        if(tipoFiltro=='D') {
            for(var i=0; i<inputsFiltroDisciplina.length; i++){
                if ($(inputsFiltroDisciplina[i]).attr('data-id')==''){
                    isValid = false;
                    $(inputsFiltroDisciplina[i]).addClass("is-invalid");
                    $('#ftr-disciplina').css('display', 'block');
                }
            }
        } else if(tipoFiltro=='S') {
            $('#basic').trigger('clear');
            for(var i=0; i<inputsFiltroSala.length; i++){
                if (!inputsFiltroSala[i].validity.valid){
                    isValid = false;
                    $(inputsFiltroSala[i]).addClass("is-invalid");
                }
            }
        }

        if (isValid) {
            $('body').loading({
                message: 'Carregando...'
            });
            if(tipoFiltro=='S') {
                $('#table-disc').hide();
                var events = buscar_disciplinas();
                $('#btn-exports').show();
                $('#scheduler_here').show();
                refresh_calendar(events);
            } else if(tipoFiltro=='D') {
                $('#btn-exports').hide();
                $('#scheduler_here').hide();
                montar_grid($('#semestre').val().replace('.', ''), $('#basic').attr('data-id'));
                $('#table-disc').show();
            }
            $('body').loading('stop');
        }
    });

    $("#semestre").change(function() {
        $('#unidade').html('');
        $('#unidade').append($('<option>', {value: '', text: ':: Selecione ::'}));
        $('#sala').html('');
        $('#sala').append($('<option>', {value: '', text: ':: Selecione ::'}));
        if ($('#semestre').val()) {
            $('#unidade').loading({
                message: 'Carregando...'
            });
            var unidades = ajax('GET', '/planejamento/obter-unidades/json', {semestre:$('#semestre').val().replace('.','')});
            $.each(unidades, function(i, unidade) {
                $('#unidade').append($('<option>', {value: unidade.unidade, text: unidade.unidade + ' - ' + unidade.nome}));
            });
            $('#unidade').loading('stop');
        }
    });

    $("#unidade").change(function() {
        $('#sala').html('');
        $('#sala').append($('<option>', {value: '', text: ':: Selecione ::'}));
        if ($('#unidade').val()) {
            $('#sala').loading({
                message: 'Carregando...'
            });
            semestre = $('#semestre').val().replace('.', '');
            unidade = $('#unidade').val();
            var salas = ajax('GET', '/planejamento/obter-salas/json', {semestre: semestre, unidade: unidade});

            $.each(salas, function(i, sala) {
                $('#sala').append($('<option>', {value: sala.numero_sala, text: sala.numero_sala + ' - ' + sala.tipo_sala}));
            });
            $('#sala').loading('stop');
        }
    });
});
