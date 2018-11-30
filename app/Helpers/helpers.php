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

if (! function_exists('horarios_remover')) {
    function horarios_remover($inicial, $final) {
        $ini = explode(':', $inicial);
      	$fim = explode(':', $final);

      	$a = new DateTime();
      	$b = new DateTime();
      	$c = new DateTime();

      	$a->setTime($ini[0], $ini[1]);
      	$b->setTime($fim[0], $fim[1]);

      	$diff = $a->diff($b);

      	$num_aulas = ceil((($diff->h * 60) + $diff->i) / 55);

      	$c->setTime($ini[0], $ini[1]);
      	$result = [];
      	for($i=0;$i<$num_aulas;$i++) {
  		      $result[] = str_replace(':', '', $c->format('H:i'));
    		    $c->add(new \DateInterval('PT55M'));
      	}

      	return $result;
    }
}
