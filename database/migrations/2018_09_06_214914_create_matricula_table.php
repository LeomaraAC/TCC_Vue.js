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
            $table->bigInteger('cpf');
            $table->string('codigo_curso');
            $table->year('previsao_conclusao');
            $table->year('ano_ingresso');
            $table->string('data_integralizacao')->nullable();
            $table->string('forma_ingresso');
            $table->string('instituicao_anterior')->nullable();
            $table->string('situacao_curso');
            $table->string('situacao_periodo')->nullable();
            $table->string('turma')->nullable();
            $table->string('email_academico')->nullable();
            $table->text('observacao_historico')->nullable();
            $table->text('observacoes', 300)->nullable();
            $table->softDeletes();

            $table->primary('prontuario');
            $table->foreign('cpf')->references('cpf')->on('alunos')->onDelete('cascade');
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
