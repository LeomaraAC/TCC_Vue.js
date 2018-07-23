<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissoesGrupoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissoes_grupo', function (Blueprint $table) {
            $table->integer('idGrupo')->unsigned();
            $table->integer('idTelas')->unsigned();
            $table->primary(['idGrupo', 'idTelas']);

            $table->foreign('idGrupo')->references('idGrupo')->on('grupo')->onDelete('cascade');
            $table->foreign('idTelas')->references('idTelas')->on('permissoes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissoes_grupo');
    }
}
