<?php

if (! function_exists('format_periodo_letivo')) {
    function format_periodo_letivo($periodo_letivo='') {
        return substr_replace($periodo_letivo, '.', 4, 0);
    }
}

if (! function_exists('format_hora')) {
    function format_hora($hora='') {
        if (strlen($hora)==3)
            $hora = '0' . $hora;
        if ($hora=='0')
            $hora = '0000';
            
        return substr_replace($hora, ':', 2, 0);
    }
}

if (! function_exists('format_date_scheduler')) {
    function format_date_scheduler($dia_semana='') {
        $data_atual = date('Y-m-d');
        $diasemana_numero = date('w', time()) + 1;

        $diff = abs($diasemana_numero - $dia_semana);
        if(intval($dia_semana) <= intval($diasemana_numero)) {
            $date_disciplina = date('Y-m-d', strtotime($data_atual . '- ' . $diff . ' days'));
        } else {
            $date_disciplina = date('Y-m-d', strtotime($data_atual . '+ ' . $diff . ' days'));
        }

        return $date_disciplina;
    }
}
