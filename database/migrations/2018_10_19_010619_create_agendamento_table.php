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
            $table->dateTime('horarioPrevisto');
            $table->string('formaAtendimento',100);
            $table->boolean('todos')->default(false);
            $table->string('status', 45);
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
