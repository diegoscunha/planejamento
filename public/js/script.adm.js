$(document).ready(function() {
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
                $('#sala').append($('<option>', {value: sala.numero_sala, text: 'Sala: ' + sala.numero_sala}));
                $('#modal_sala').append($('<option>', {value: sala.numero_sala, text: 'Sala: ' + sala.numero_sala}));
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
