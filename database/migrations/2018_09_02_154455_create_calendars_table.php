<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendars', function (Blueprint $table) {
            $table->string('periodo_letivo', 100);
            $table->string('epoca', 100);
            $table->string('codigo_disciplina', 100);
            $table->string('inicio', 100);
            $table->string('turma', 100);
            $table->string('dia_semana', 100);
            $table->string('dia_semana_ext', 100);
            $table->string('hora_inicial', 100);
            $table->string('hora_final', 100);
            $table->string('unidade', 100);
            $table->string('numero_sala', 100);
            $table->string('tipo_sala', 100);
            $table->string('capacidade_sala', 100);
            $table->string('vagas_orfertadas', 100);
            $table->string('vagas_preenchidas', 100);
            $table->string('docente', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendars');
    }
}
