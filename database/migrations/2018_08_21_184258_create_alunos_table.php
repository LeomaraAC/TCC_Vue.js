<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlunosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alunos', function (Blueprint $table) {
            $table->increments('idAluno');
            $table->integer('idCurso')->unsigned();
            $table->string('prontuario', 10)->unique();
            $table->string('nome', 60);
            $table->string('email', 60)->unique();
            $table->string('telefone', 20)->nullable();
            $table->integer('semestreAno');
            $table->text('Observacoes', 300)->nullable();
            $table->string('statusMatricula', 45);
            $table->text('motivoCancelamento', 300)->nullable();
            $table->foreign('idCurso')->references('idCurso')->on('cursos')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alunos');
    }
}
