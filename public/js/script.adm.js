$(document).ready(function() {

    $('#unidade_det').change(function(evt){
        evt.preventDefault();
        semestre = $('#semestre').val().replace('.','');
        unidade = $(this).val();
        if(unidade!='') {
            $('body').loading({
                message: 'Carregando...'
            });
            $('.titulo-unidade').html($('#unidade_det :selected').text());
            var salas = ajax('GET', '/adm/planejamento/detalhes-unidade/' + semestre + '/'+unidade, {});
            montar_tabela_detalhes_unidade($('#tb-detalhes > tbody'), salas);
            var disciplinas = ajax('GET', '/adm/planejamento/disciplinas-unidade/' + semestre + '/'+unidade, {});
            montar_tabela_disicplinas_unidade($('#tb-disciplinas > tbody'), disciplinas);
            var mapa_calor = ajax('GET', '/adm/planejamento/mapa-calor/' + semestre + '/'+unidade, {});
            montar_mapa_calor($('#tb-mapa-calor > tbody'), mapa_calor);
            $('#info-detalhes').show();
            $('body').loading('stop');
        } else {
            $('#info-detalhes').hide();
        }
    });

    $('#gerar_r').click(function(evt){
        evt.preventDefault();

        var inputs = $('.filtro_r'),
            isValid = true;

        $(".filtro_r").removeClass("is-invalid");
        for(var i=0; i<inputs.length; i++){
            if (!inputs[i].validity.valid){
                isValid = false;
                $(inputs[i]).addClass("is-invalid");
            }
        }

        if (isValid) {
            $('body').loading({
                message: 'Carregando...'
            });
            semestre = $('#semestre').val().replace('.','');
            unidade = $('#unidade_r').val();
            window.open('/adm/planejamento/relatorio/' + semestre + '/' + unidade, '_blank');
            $('body').loading('stop');
        }
    });

    $("#unidade_o").change(function() {
        $('#sala_o').html('');
        $('#sala_o').append($('<option>', {value: '', text: ':: Selecione ::'}));
        if ($('#unidade_o').val()) {
            $('#sala_o').loading({
                message: 'Carregando...'
            });
            semestre = $('#semestre_o').val().replace('.', '');
            unidade = $('#unidade_o').val();
            var salas = ajax('GET', '/planejamento/obter-salas/json', {semestre: semestre, unidade: unidade});

            $.each(salas, function(i, sala) {
                $('#sala_o').append($('<option>', {value: sala.numero_sala, text: 'Sala: ' + sala.numero_sala}));
            });
            $('#sala_o').loading('stop');
        }
    });

    $('#consultar_o').click(function(evt) {
        evt.preventDefault();
        var inputs = $('.filtro_o'),
            isValid = true;

        $(".filtro_o").removeClass("is-invalid");
        for(var i=0; i<inputs.length; i++){
            if (!inputs[i].validity.valid){
                isValid = false;
                $(inputs[i]).addClass("is-invalid");
            }
        }

        if (isValid) {
            $('body').loading({
                message: 'Carregando...'
            });
            carregar_horarios_ociosos();
            //$('#result-ociosos').css('visibility', 'visible');
            $('#result-ociosos').css('display', 'block');
            $('body').loading('stop');
        }
    });

    $('#liberado').click(function(evt) {
        var semestre = $('#semestre').val().replace('.', ''),
            url = '/api/planejamento/' + semestre + '/liberar';
        ajax('GET', url);
    });

    $('#modal_sala, #modal_dia_semana, #modal_hora_inicial').change(function() {
        var semestre = $('#semestre').val().replace('.', ''),
            url = '/api/planejamento/choque-horario/' + $('#modal_id').val();

        if($('#modal_sala').val()!='' && $('#modal_dia_semana').val()!='' && $('#modal_hora_inicial').val()!='') {
          var result = ajax('GET', url, {periodo_letivo: semestre, unidade: $('#modal_unidade').val(), sala: $('#modal_sala').val() , dia_semana: $('#modal_dia_semana').val(), hora_inicial: $('#modal_hora_inicial').val() });

          if(result.choque_horario) {
              $('#alert_choque').css('display', 'block');
              $('#alert_choque').html('<span class="fa fa-exclamation-triangle"></span> Choque de hórario. Há outra(s) disciplinas neste hórario!');
          } else {
              $('#alert_choque').css('display', 'none');
          }
        }
    });

    $(function () {
      $('[data-toggle="tooltip"]').tooltip();
    });

    $('a.excluir-planejamento').click(function(evt) {
        evt.preventDefault();
        href_excluir = $(this).attr('href');
        swal({
          title: "Você tem certeza que deseja excluir?",
          text: "O Planejamento será excluído da base de dados!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        }).then((resposta) => {
            if(resposta) {
              window.location.href = href_excluir;
            }
        });
    });

    $('#alocar').click(function(evt) {
        evt.preventDefault();
        var inputs = $('.formmodal'),
            isValid = true;

        $(".formmodal").removeClass("is-invalid");
        for(var i=0; i<inputs.length; i++){
            if (!inputs[i].validity.valid){
                isValid = false;
                $(inputs[i]).addClass("is-invalid");
            }
        }

        if (parseInt($('#modal_hora_inicial').val())>=parseInt($('#modal_hora_final').val())) {
            isValid = false;
            $('#modal_hora_inicial').addClass("is-invalid");
            $('#modal_hora_final').addClass("is-invalid");
        }

        if (isValid) {
            swal({
              title: "Você tem certeza?",
              text: "A disciplina será alocada com os dados informados!",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            }).then((resposta) => {
              if (resposta) {
                $('body').loading({
                    message: 'Carregando...'
                });
                var url = '/api/planejamento/' + $('#semestre').val().replace('.', '') + '/' + $('#modal_unidade').val() + '/alocar';
                var r = ajax('POST', url, $('#form-modal').serialize());
                var events = buscar_disciplinas();
                var disc = ajax('GET', '/api/planejamento/' + semestre + '/nao-alocadas/' + unidade);
                refresh_calendar(events);
                refresh_grid(disc);
                $('#exampleModalCenter').modal('hide');
                swal("Disciplina alocada com sucesso!", {
                  icon: "success",
                });
                $('body').loading('stop');
              }
            });
        }
    });

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
            $('body').loading({
                message: 'Carregando...'
            });
            var events = buscar_disciplinas();
            refresh_calendar(events);
            $('body').loading('stop');
        }
    });

    $("#unidade").change(function() {
        $('#sala').html('');
        $('#sala').append($('<option>', {value: '', text: ':: Selecione ::'}));
        $('#modal_sala').html('');
        $('#modal_sala').append($('<option>', {value: '', text: ':: Selecione ::'}));
        if ($('#unidade').val()) {
            $('#sala, #gridbox').loading({
                message: 'Carregando...'
            });
            semestre = $('#semestre').val().replace('.', '');
            unidade = $('#unidade').val();
            var salas = ajax('GET', '/planejamento/obter-salas/json', {semestre: semestre, unidade: unidade});

            $.each(salas, function(i, sala) {
                $('#sala').append($('<option>', {value: sala.numero_sala, text: sala.numero_sala + ' - ' + sala.tipo_sala}));
                $('#modal_sala').append($('<option>', {value: sala.numero_sala, text: sala.numero_sala + ' - ' + sala.tipo_sala}));
            });
            var disc = ajax('GET', '/api/planejamento/' + semestre + '/nao-alocadas/' + unidade);
            refresh_grid(disc);
            $('#sala, #gridbox').loading('stop');
        }
    });

    $("#modal_unidade").change(function() {
        $('#modal_sala').html('');
        $('#modal_sala').append($('<option>', {value: '', text: ':: Selecione ::'}));
        if ($('#modal_unidade').val()) {
            semestre = $('#semestre').val().replace('.', '');
            unidade = $('#modal_unidade').val();
            var salas = ajax('GET', '/planejamento/obter-salas/json', {semestre: semestre, unidade: unidade});
            $.each(salas, function(i, sala) {
                $('#modal_sala').append($('<option>', {value: sala.numero_sala, text: sala.numero_sala}));
            });
        }
    });
});
