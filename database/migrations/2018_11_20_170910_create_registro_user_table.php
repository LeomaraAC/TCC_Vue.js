<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistroUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registro_user', function (Blueprint $table) {
            $table->integer('idRegistro')->unsigned();
            $table->integer('idUser')->unsigned();
            $table->text('resumo');
            $table->primary(['idRegistro', 'idUser']);

            $table->foreign('idRegistro')->references('idRegistro')->on('registro_atendimento')->onDelete('cascade');

            $table->foreign('idUser')
                  ->references('idUser')->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registro_user');
    }
}
