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
            $table->bigInteger('cpf');
            $table->string('rg')->nullable();
            $table->string('nome', 60);
            $table->string('data_nascimento');
            $table->string('nome_mae',60)->nullable();
            $table->string('nome_pai',60)->nullable();
            $table->enum('sexo', ['F', 'M']);
            $table->string('responsavel',60)->nullable();
            $table->string('email_pessoal', 60)->nullable();
            $table->string('email_responsavel',60)->nullable();
            $table->string('estado_civil',20);
            $table->string('naturalidade')->nullable();
            $table->string('deficiencia')->nullable();
            $table->string('etnia',20);
            $table->string('necessidades_especiais')->nullable();
            $table->float('renda_bruta')->nullable();
            $table->float('renda_per_capta')->nullable();
            $table->string('superdotacao')->nullable();
            $table->string('tipo_escola_origem')->nullable();
            $table->string('transtorno')->nullable();
            $table->string('endereco');

            $table->softDeletes();

            $table->primary('cpf');
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
