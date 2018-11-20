<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistroAtendimentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registro_atendimento', function (Blueprint $table) {
            $table->increments('idRegistro');
            $table->bigInteger('idAgendamento')->unsigned();
            $table->integer('idResumoAtendimento')->unsigned();
            $table->date('dataRealizado');
            $table->time('horaRealizado');
            $table->boolean('comparecimentoFamiliar')->nullable();
            $table->string('grauParentesco',60)->nullable();
            $table->softDeletes();
            
            $table->foreign('idAgendamento')->references('idAgendamento')->on('agendamento')->onDelete('cascade');
            $table->foreign('idResumoAtendimento')->references('idResumoAtendimento')->on('resumo_atendimento');            
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registro_atendimento');
    }
}
