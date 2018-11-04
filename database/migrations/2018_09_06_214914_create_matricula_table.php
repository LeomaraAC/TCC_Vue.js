<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatriculaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matricula', function (Blueprint $table) {
            $table->string('prontuario');
            $table->bigInteger('idAluno')->unsigned();
            $table->string('codigo_curso');
            $table->year('previsao_conclusao');
            $table->year('ano_ingresso');
            $table->string('data_integralizacao',10)->nullable();
            $table->string('forma_ingresso');
            $table->string('instituicao_anterior')->nullable();
            $table->string('situacao_curso');
            $table->string('situacao_periodo')->nullable();
            $table->string('turma')->nullable();
            $table->string('email_academico',60)->nullable();
            $table->text('observacao_historico')->nullable();
            $table->text('observacoes')->nullable();
            $table->softDeletes();

            $table->primary('prontuario');
            $table->foreign('idAluno')->references('idAluno')->on('alunos')->onDelete('cascade');
            $table->foreign('codigo_curso')->references('codigo')->on('cursos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matricula');
    }
}
