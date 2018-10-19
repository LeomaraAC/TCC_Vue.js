<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgendamentoMatriculaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendamento_matricula', function (Blueprint $table) {
            $table->bigInteger('idAgendamento')->unsigned();
            $table->string('prontuario');
            $table->primary(['idAgendamento', 'prontuario']);

            $table->foreign('idAgendamento')->references('idAgendamento')->on('agendamento')->onDelete('cascade');
            $table->foreign('prontuario')->references('prontuario')->on('matricula')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agendamento_matricula');
    }
}
