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
            $('body').loading({
                message: 'Carregando...'
            });
            var events = buscar_disciplinas();
            refresh_calendar(events);
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
                $('#sala').append($('<option>', {value: sala.numero_sala, text: 'Sala: ' + sala.numero_sala}));
            });
            $('#sala').loading('stop');
        }
    });
});
