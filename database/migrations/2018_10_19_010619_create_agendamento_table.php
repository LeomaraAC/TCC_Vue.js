<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgendamentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendamento', function (Blueprint $table) {
            $table->bigIncrements('idAgendamento');
            $table->integer('idTipo_atendimento')->unsigned();
            $table->integer('idUser')->unsigned();
            $table->date('dataPrevisto')->nullable();
            $table->time('horaPrevistaInicio')->nullable();
            $table->time('horaPrevistaFim')->nullable();
            $table->string('formaAtendimento',100);
            $table->String('responsavel', 15);
            $table->string('status', 45);
            $table->date('dataRemarcada')->nullable();
            $table->softDeletes();

            $table->foreign('idTipo_atendimento')->references('idTipo_atendimento')->on('tipo_atendimento')->onDelete('cascade');
            $table->foreign('idUser')->references('idUser')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agendamento');
    }
}
