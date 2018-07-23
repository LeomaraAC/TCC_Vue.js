<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGrupoUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupo_usuarios', function (Blueprint $table) {
            $table->integer('idGrupo')->unsigned();
            $table->integer('idUser')->unsigned();
            $table->primary(['idGrupo', 'idUser']);

            $table->foreign('idGrupo')->references('idGrupo')->on('grupo')->onDelete('cascade');
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
        Schema::dropIfExists('grupo_usuarios');
    }
}
